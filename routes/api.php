<?php
use App\Http\Controllers\API\Api_AuthController;
use App\Http\Controllers\API\Api_ProfileController;
use App\Http\Controllers\API\Api_BooksController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\VerifyEmailController;




Route::post('register', [Api_AuthController::class, 'register']);
Route::post('login', [Api_AuthController::class, 'login']);
Route::post('logout', [Api_AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::get('books/popular', [Api_BooksController::class, 'getPopularBooks']);
Route::get('books/new', [Api_BooksController::class, 'getNewBooks']);
Route::get('books/{book}/reviews', [Api_BooksController::class, 'getBookReviews']);
Route::get('books/{book}/available-copies', [Api_BooksController::class, 'getAvailableCopies']);
Route::get('verify-email', [VerifyEmailController::class, '__invoke'])->name('verification.verify');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('profile', [Api_ProfileController::class, 'show']);
    Route::put('profile/name', [Api_ProfileController::class, 'updateName']);
    Route::put('profile/email', [Api_ProfileController::class, 'updateEmail']);
    Route::put('profile/password', [Api_ProfileController::class, 'updatePassword']);
    Route::post('books/{book}/reviews', [Api_BooksController::class, 'submitOrUpdateReview']);
    Route::post('books/{book}/like', [Api_BooksController::class, 'likeBook']);
    Route::delete('books/{book}/like', [Api_BooksController::class, 'unlikeBook']);
    Route::post('book-copies/{book_id}/reserve', [Api_BooksController::class, 'reserveCopy']);
    Route::post('book-copies/{book_id}/available', [Api_BooksController::class, 'makeCopyAvailable']);
    Route::post('reservations/{book_copy_id}', [Api_BooksController::class, 'addReservation']);
    Route::delete('reservations/book-copy/{book_copy_id}', [Api_BooksController::class, 'deleteReservation']);
    Route::get('books/liked', [Api_BooksController::class, 'getLikedBooks']);
    Route::get('book-copies/borrowed', [Api_BooksController::class, 'getBorrowedBookCopies']);
    Route::get('books', [Api_BooksController::class, 'getAllBooks']);
    Route::get('books/most-liked', [Api_BooksController::class, 'getMostLikedBooks']);
});
