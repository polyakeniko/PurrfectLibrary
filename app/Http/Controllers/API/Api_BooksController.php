<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
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
}
