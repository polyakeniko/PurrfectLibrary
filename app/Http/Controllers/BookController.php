<?php

namespace App\Http\Controllers;

use App\Mail\NewBookNotification;
use App\Models\Book;
use App\Models\Category;
use App\Models\Loan;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $newestBooks = Book::orderBy('published_year', 'desc')->take(2)->get();

        $mostPopularBooks = Book::select('books.id', 'books.title', 'books.author', 'books.published_year', 'books.category_id', DB::raw('AVG(reviews.rating) as average_rating'))
            ->leftJoin('reviews', 'reviews.book_id', '=', 'books.id')
            ->groupBy('books.id', 'books.title', 'books.author', 'books.published_year', 'books.category_id')
            ->orderByDesc('average_rating')
            ->take(2)
            ->get();

        $booksQuery = Book::query();

        if ($request->has('search')) {
            $searchTerm = $request->input('search');
            $booksQuery->where('title', 'like', "%{$searchTerm}%")
                ->orWhere('author', 'like', "%{$searchTerm}%")
                ->orWhere('tags', 'like', "%{$searchTerm}%");
        }

        $books = $booksQuery->simplePaginate(6);

        return view('books.index', compact('books', 'newestBooks', 'mostPopularBooks'));
    }

    public function show(Book $book)
    {
        $book->load('reviews.user');

        $availableCopies = $book->copies()->where('status', 'available')->count();

        $userHasLoaned = false;
        $userHasReserved = false;

        if ($availableCopies > 0) {
            $firstAvailableCopy = $book->copies()->where('status', 'available')->first();

            $userHasLoaned = Loan::where('book_copy_id', $firstAvailableCopy->id)
                ->where('user_id', auth()->id())
                ->exists();

            $userHasReserved = Reservation::where('book_copy_id', $firstAvailableCopy->id)
                ->where('user_id', auth()->id())
                ->exists();
        }

        return view('books.show', compact('book', 'availableCopies', 'userHasLoaned', 'userHasReserved'));
    }

    public function reserve(Book $book)
    {
        $bookCopy = $book->copies()->where('status', 'available')->first();

        if (!$bookCopy) {
            return redirect()->route('books.show', $book)->with('error', 'No available copies for this book.');
        }

        $reservation = new Reservation();
        $reservation->book_copy_id = $bookCopy->id;
        $reservation->user_id = auth()->id();
        $reservation->save();

        return redirect()->route('books.show', $book)->with('success', 'You have successfully reserved the book.');
    }

    public function undoReservation(Book $book)
    {
        $reservation = Reservation::where('user_id', auth()->id())->first();

        if ($reservation && $reservation->bookCopy->book_id == $book->id) {

            $bookCopy = $reservation->bookCopy;
            $bookCopy->status = 'available';
            $bookCopy->save();

            $reservation->delete();

            return redirect()->route('books.show', $book)->with('success', 'You have successfully undone the reservation.');
        }

        return redirect()->route('books.show', $book)->with('error', 'Reservation not found.');
    }

    public function storeReview(Request $request, Book $book)
    {
        $request->validate([
            'rating' => 'required|integer|between:1,5',
            'review' => 'required|string|max:1000',
        ]);

        $existingReview = $book->reviews()->where('user_id', auth()->id())->first();

        if ($existingReview) {
            $existingReview->update([
                'rating' => $request->rating,
                'review' => $request->review,
            ]);

            return redirect()->route('books.show', $book)->with('success', 'Review updated successfully');
        } else {
            $book->reviews()->create([
                'user_id' => auth()->id(),
                'rating' => $request->rating,
                'review' => $request->review,
            ]);

            return redirect()->route('books.show', $book)->with('success', 'Review submitted successfully');
        }
    }

    public function edit(Book $book)
    {
        $categories = Category::all();
        return view('books.edit', compact('book', 'categories'));
    }

    public function update(Request $request, Book $book)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'tags' => 'nullable|string',
            'published_year' => 'nullable|integer|min:1000|max:' . date('Y'),
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $book->title = $request->input('title');
        $book->author = $request->input('author');
        $book->category_id = $request->input('category_id');
        $book->tags = $request->input('tags');
        $book->published_year = $request->input('published_year');
        $book->description = $request->input('description');

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images', 'public');
            $book->image = $imagePath;
        }

        $book->save();

        return redirect()->route('books.index')->with('success', 'Book updated successfully.');
    }

    public function destroy(Book $book)
    {
        $book->delete();

        return redirect()->route('books.index')->with('success', 'Book deleted successfully.');
    }

    public function create()
    {
        $categories = Category::all();

        return view('books.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'tags' => 'nullable|string|max:255',
            'published_year' => 'nullable|integer|min:1000|max:' . date('Y'),
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $book = new Book($validated);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images', 'public');
            $book->image = $imagePath;
        }

        $book->save();

        $users = User::all();
//        foreach ($users as $user) {
//            Mail::to($user->email)->send(new NewBookNotification($book));
//        }

        return redirect()->route('books.index')->with('success', 'Book added successfully.');
    }
}
