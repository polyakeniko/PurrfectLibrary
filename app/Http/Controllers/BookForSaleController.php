<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BookForSale;
use App\Models\Order;
use Illuminate\Http\Request;

class BookForSaleController extends Controller
{
    public function index()
    {
        $booksForSale = BookForSale::with('book')
            ->where('is_active', true)
            ->where('stock', '>', 0)
            ->get();

        return view('books-for-sale.index', compact('booksForSale'));
    }

    public function create()
    {
        $books = Book::all();
        return view('books-for-sale.create', compact('books'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'book_id' => 'required|exists:books,id',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ]);

        BookForSale::create($validated);

        return redirect()->route('books-for-sale.index')->with('success', 'Book added for sale.');
    }

    public function showBuyForm(BookForSale $bookForSale)
    {
        return view('books-for-sale.buy', compact('bookForSale'));
    }

    public function buy(Request $request, BookForSale $bookForSale)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1|max:' . $bookForSale->stock,
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:255',
            'address' => 'required|string|max:255',
        ]);

        $totalPrice = $bookForSale->price * $validated['quantity'];

        // Create the order
        $order = Order::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'address' => $validated['address'],
            'total_price' => $totalPrice,
            'status' => 'pending',
        ]);

        $order->orderItems()->create([
            'book_for_sale_id' => $bookForSale->id,
            'quantity' => $validated['quantity'],
            'unit_price' => $bookForSale->price,
        ]);

        $bookForSale->decrement('stock', $validated['quantity']);

        return redirect()->route('books.show', $bookForSale->book_id)
            ->with('success', 'Purchase successful!');
    }

    public function edit($id)
    {
        $bookForSale = BookForSale::with('book')->findOrFail($id);
        return view('books-for-sale.edit', compact('bookForSale'));
    }

    public function update(Request $request, $id)
    {
        $bookForSale = BookForSale::findOrFail($id);

        $validated = $request->validate([
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'is_active' => 'required|boolean',
        ]);

        $bookForSale->update($validated);

        return redirect()->route('books-for-sale.index')
            ->with('success', 'Sale updated successfully.');
    }

    public function checkout(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'cart' => 'required|json',
        ]);

        $cart = json_decode($validated['cart'], true);
        if (!$cart || !is_array($cart) || count($cart) === 0) {
            return back()->withErrors(['cart' => 'Your cart is empty.']);
        }

        $totalPrice = 0;
        $items = [];
        foreach ($cart as $item) {
            $bookForSale = BookForSale::find($item['id']);
            if (!$bookForSale || !$bookForSale->is_active || $bookForSale->stock < $item['quantity']) {
                return back()->withErrors(['cart' => 'One or more items are unavailable or out of stock.']);
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

        return redirect()->route('cart.show')->with('success', 'Order placed!');
    }
}
