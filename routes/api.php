<?php
use App\Http\Controllers\API\Api_AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Api_ProfileController;
use App\Http\Controllers\API\Api_BooksController;


Route::post('register', [Api_AuthController::class, 'register']);
Route::post('login', [Api_AuthController::class, 'login']);
Route::post('logout', [Api_AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::get('books/popular', [Api_BooksController::class, 'getPopularBooks']);
Route::get('books/new', [Api_BooksController::class, 'getNewBooks']);
Route::get('books/{book}/reviews', [Api_BooksController::class, 'getBookReviews']);




Route::middleware('auth:sanctum')->group(function () {
    Route::get('profile', [Api_ProfileController::class, 'show']);
    Route::put('profile/name', [Api_ProfileController::class, 'updateName']);
    Route::put('profile/email', [Api_ProfileController::class, 'updateEmail']);
    Route::put('profile/password', [Api_ProfileController::class, 'updatePassword']);
    Route::post('books/{book}/reviews', [Api_BooksController::class, 'submitOrUpdateReview']);
});
