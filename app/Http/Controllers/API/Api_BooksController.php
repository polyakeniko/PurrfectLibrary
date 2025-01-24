<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\BookCopy;
use App\Models\Reservation;
use App\Models\Review;
use Illuminate\Http\Request;
use App\Models\Book;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
class Api_BooksController extends Controller
{
    /**
     * Get popular books.
     */
    public function getPopularBooks()
    {
        $books = Book::with(['reviews' => function ($query) {
            $query->select('book_id', \DB::raw('AVG(rating) as average_rating'))
                ->groupBy('book_id');
        }])
            ->withCount('reviews')
            ->get();

        // Add average_rating to each book, round to two decimal places, and sort by average_rating
        $books->each(function ($book) {
            $book->average_rating = isset($book->reviews->first()->average_rating)
                ? round($book->reviews->first()->average_rating, 2)
                : 0;
        });

        $sortedBooks = $books->sortByDesc('average_rating')->take(3); // Sort by average_rating and take top 3

        return response()->json($sortedBooks->values()->all(), 200);
    }

    public function getNewBooks()
    {
        $newBooks = Book::orderBy('published_year', 'desc')
            ->take(10)
            ->get(['id', 'title', 'author', 'description', 'tags', 'category_id', 'published_year', 'image']);
        return response()->json($newBooks);
    }
    public function getBookReviews(Book $book)
    {
        $reviews = $book->reviews()->with('user:id,name')->get(['user_id', 'rating', 'review', 'review_date']);
        return response()->json($reviews, 200);
    }
    public function submitOrUpdateReview(Request $request, Book $book)
    {
        $user = $request->user();

        if (!$user) {
            Log::info('Invalid token: No authenticated user found');
            return response()->json(['message' => 'Invalid token'], 401);
        }

        $validator = Validator::make($request->all(), [
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'required|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $review = Review::updateOrCreate(
                ['user_id' => $user->id, 'book_id' => $book->id],
                ['rating' => $request->rating, 'review' => $request->review, 'review_date' => now()]
            );

            return response()->json(['message' => 'Review submitted successfully', 'review' => $review], 200);
        } catch (\Exception $e) {
            Log::error('Error submitting review: ' . $e->getMessage());
            return response()->json(['message' => 'Error submitting review'], 500);
        }
    }
    public function getAvailableCopies(Book $book)
    {
        $availableCopies = $book->copies()->where('status', 'available')->count();
        return response()->json(['available_copies' => $availableCopies], 200);
    }

    public function likeBook(Request $request, Book $book)
    {
        $user = $request->user();

        if (!$user) {
            Log::info('Invalid token: No authenticated user found');
            return response()->json(['message' => 'Invalid token'], 401);
        }


        if ($user->likedBooks()->where('book_id', $book->id)->exists()) {
            return response()->json(['message' => 'Book already liked'], 409);
        }

        $user->likedBooks()->attach($book->id);

        return response()->json(['message' => 'Book liked successfully'], 200);
    }
    public function unlikeBook(Request $request, Book $book)
    {
        $user = $request->user();

        if (!$user) {
            Log::info('Invalid token: No authenticated user found');
            return response()->json(['message' => 'Invalid token'], 401);
        }

        if (!$user->likedBooks()->where('book_id', $book->id)->exists()) {
            return response()->json(['message' => 'Book not liked yet'], 409);
        }

        $user->likedBooks()->detach($book->id);

        return response()->json(['message' => 'Book unliked successfully'], 200);
    }

    public function reserveCopy(Request $request, $book_id)
    {
        $bookCopy = BookCopy::where('book_id', $book_id)->first();

        if (!$bookCopy) {
            return response()->json(['message' => 'Book copy not found'], 404);
        }

        $bookCopy->status = 'reserved';
        $bookCopy->save();

        return response()->json(['message' => 'Book copy reserved successfully'], 200);
    }

    public function makeCopyAvailable(Request $request, $book_id)
    {
        $bookCopy = BookCopy::where('book_id', $book_id)->first();

        if (!$bookCopy) {
            return response()->json(['message' => 'Book copy not found'], 404);
        }

        $bookCopy->status = 'available';
        $bookCopy->save();

        return response()->json(['message' => 'Book copy made available successfully'], 200);
    }

    public function addReservation(Request $request, $book_copy_id)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['message' => 'Invalid token'], 401);
        }

        $bookCopy = BookCopy::find($book_copy_id);

        if (!$bookCopy) {
            return response()->json(['message' => 'Book copy not found'], 404);
        }

        $reservation = new Reservation();
        $reservation->user_id = $user->id;
        $reservation->book_copy_id = $book_copy_id;
        $reservation->reservation_date = now();
        $reservation->status = 'pending';
        $reservation->save();

        return response()->json(['message' => 'Reservation added successfully'], 200);
    }
    public function deleteReservation(Request $request, $book_copy_id)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['message' => 'Invalid token'], 401);
        }

        $reservation = Reservation::where('book_copy_id', $book_copy_id)
            ->where('user_id', $user->id)
            ->first();

        if (!$reservation) {
            return response()->json(['message' => 'Reservation not found'], 404);
        }

        $reservation->delete();

        return response()->json(['message' => 'Reservation deleted successfully'], 200);
    }
    public function getLikedBooks(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['message' => 'Invalid token'], 401);
        }

        $likedBooks = $user->likedBooks()->get(['books.id', 'title', 'author', 'description', 'published_year' , 'image']);

        return response()->json($likedBooks, 200);
    }
    public function getBorrowedBookCopies(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['message' => 'Invalid token'], 401);
        }

        $borrowedBookCopies = BookCopy::where('status', 'borrowed')
            ->whereHas('reservations', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->with(['book:id,title,author,description,published_year,image'])
            ->get();

        if ($borrowedBookCopies->isEmpty()) {
            return response()->json(['message' => 'Don\'t have borrowed books'], 200);
        }

        return response()->json($borrowedBookCopies, 200);
    }
    public function getAllBooks(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['message' => 'Invalid token'], 401);
        }

        $books = Book::select( 'id','title', 'author', 'description', 'published_year', 'image')->get();
        return response()->json($books, 200);
    }
    public function getMostLikedBooks()
    {
        $mostLikedBooks = Book::withCount('likedByUsers')
            ->orderBy('liked_by_users_count', 'desc')
            ->take(3)
            ->get(['id', 'title', 'author', 'description', 'published_year', 'image']);

        return response()->json($mostLikedBooks, 200);
    }
}
