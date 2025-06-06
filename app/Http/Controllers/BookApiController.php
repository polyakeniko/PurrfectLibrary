<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BookForSale;
use App\Models\Order;
use Illuminate\Http\Request;

class BookApiController extends Controller
{
    public function getPopularBooks()
    {
        $books = Book::with(['reviews' => function ($query) {
            $query->select('book_id', \DB::raw('AVG(rating) as average_rating'))
                ->groupBy('book_id');
        }])
            ->whereExists(function ($query) {
                $query->select(\DB::raw(1))
                    ->from('book_for_sales')->where('book_for_sales.is_active', true)
                    ->whereColumn('book_for_sales.book_id', 'books.id');
            })
            ->withCount('reviews')
            ->get();

        // Add average_rating to each book, round to two decimal places, and sort by average_rating
        $books->each(function ($book) {
            $book->average_rating = isset($book->reviews->first()->average_rating)
                ? round($book->reviews->first()->average_rating, 2)
                : 0;
        });

        $sortedBooks = $books->sortByDesc('average_rating')->take(3); // Sort by average_rating and take top 3

        return response()->json($sortedBooks->values()->all(),  200);
    }

    // Fetch the newest books
    public function getNewestBooks()
    {
        $books = Book::whereExists(function ($queryBuilder) {
            $queryBuilder->select(\DB::raw(1))
                ->from('book_for_sales')
                ->where('book_for_sales.is_active', true)
                ->whereColumn('book_for_sales.book_id', 'books.id');
        })
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        return response()->json($books);
    }

    // Search books by title or author
    public function searchBooks(Request $request)
    {
        $query = $request->input('query');
        $books = Book::where(function ($queryBuilder) use ($query) {
            $queryBuilder->where('title', 'like', "%{$query}%")
                ->orWhere('author', 'like', "%{$query}%");
        })
            ->whereExists(function ($queryBuilder) {
                $queryBuilder->select(\DB::raw(1))
                    ->from('book_for_sales')
                    ->where('book_for_sales.is_active', true)
                    ->whereColumn('book_for_sales.book_id', 'books.id');
            })
            ->get();
        return response()->json($books);
    }

    public function buyItems(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'cart' => 'required|array',
        ]);

        $cart = $validated['cart'];
        if (!$cart || !is_array($cart) || count($cart) === 0) {
            return response()->json([$cart]);
        }

        $totalPrice = 0;
        $items = [];
        foreach ($cart as $item) {
            $bookForSale = BookForSale::find($item['id']);
            if (!$bookForSale || !$bookForSale->is_active || $bookForSale->stock < $item['quantity']) {
                return response()->json(['cart' => 'One or more items are unavailable or out of stock.']);
            }
            $totalPrice += $bookForSale->price * $item['quantity'];
            $items[] = [
                'book_for_sale' => $bookForSale,
                'quantity' => $item['quantity'],
                'unit_price' => $bookForSale->price,
            ];
        }

        $order = Order::create([
            'user_id' => auth()->check() ? auth()->id() : null,
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'address' => $validated['address'],
            'total_price' => $totalPrice,
            'status' => 'pending',
        ]);

        foreach ($items as $item) {
            $order->orderItems()->create([
                'book_for_sale_id' => $item['book_for_sale']->id,
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
            ]);
            $item['book_for_sale']->decrement('stock', $item['quantity']);
            $item['book_for_sale']->refresh();
            if ($item['book_for_sale']->stock == 0) {
                $item['book_for_sale']->is_active = false;
                $item['book_for_sale']->save();
            }
        }

        return response()->json([
            'message' => 'Order placed successfully!',
            'order_id' => $order->id,
            'total_price' => $totalPrice,
        ], 201);
    }
}
