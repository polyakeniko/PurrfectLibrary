<?php

use App\Http\Controllers\AdminSettingController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\LibrarianController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QrCodeController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\StatisticsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WelcomeController;
use App\Http\Middleware\EnsureUserIsAdmin;
use App\Http\Middleware\EnsureUserIsLibrarian;
use Illuminate\Support\Facades\Route;

Route::get('/', [WelcomeController::class, 'index'])->name('home');

Route::get('/books', [BookController::class, 'index'])->name('books.index');
Route::get('/books/{book}', [BookController::class, 'show'])->name('books.show');
Route::get('/', [AdminSettingController::class, 'getDescription'])->name('home');
//user
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/user/borrowed', [UserController::class, 'show'])->name('user.borrowed');
    Route::post('/books/{book}/reserve', [BookController::class, 'reserve'])->name('books.reserve');
    Route::delete('/books/{book}/undoReservation', [BookController::class, 'undoReservation'])->name('books.undoReservation');
    Route::post('/books/{book}/review', [BookController::class, 'storeReview'])->name('books.review');
});

//librarian
Route::middleware(['auth', 'verified', EnsureUserIsLibrarian::class])->group(function () {
Route::get('/books/{book}/edit', [BookController::class, 'edit'])->name('books.edit');
Route::put('/books/{book}', [BookController::class, 'update'])->name('books.update');
Route::delete('/books/{book}', [BookController::class, 'destroy'])->name('books.destroy');
Route::get('create', [BookController::class, 'create'])->name('books.create');
Route::post('/books', [BookController::class, 'store'])->name('books.store');
Route::get('/qr-codes', [QrCodeController::class, 'index'])->name('qr-codes.index');

Route::get('/members', [UserController::class, 'index'])->name('members.index');

Route::get('/loans', [LoanController::class, 'index'])->name('loans.index');
Route::post('/loans/{loan}/return', [LoanController::class, 'markAsReturned'])->name('loans.return');
Route::post('/loans/{loan}/unreturn', [LoanController::class, 'markAsNotReturned'])->name('loans.unreturn');


Route::get('/reservations', [ReservationController::class, 'index'])->name('reservations.index');
Route::post('/reservations/{reservation}/status', [ReservationController::class, 'updateStatus'])->name('reservations.updateStatus');
});

Route::middleware('auth')->group(function () {
    Route::get('/members/create', [UserController::class, 'create'])->name('members.create');
    Route::post('/members', [UserController::class, 'store'])->name('members.store');
    Route::get('/members/{user}/edit', [UserController::class, 'edit'])->name('members.edit');
    Route::put('/members/{user}', [UserController::class, 'update'])->name('members.update');
    Route::post('/members/{user}/block', [UserController::class, 'block'])->name('members.block');
    Route::post('/members/{user}/unblock', [UserController::class, 'unblock'])->name('members.unblock');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::middleware(['auth', 'verified', EnsureUserIsAdmin::class])->group(function () {
    Route::resource('librarians', LibrarianController::class)->names([
        'index' => 'librarians.index',
        'create' => 'librarians.create',
        'store' => 'librarians.store',
        'show' => 'librarians.show',
        'edit' => 'librarians.edit',
        'update' => 'librarians.update',
        'destroy' => 'librarians.destroy',
    ]);
    Route::put('librarians/{librarian}/activate', [LibrarianController::class, 'activate'])->name('librarians.activate');
    Route::get('admin/statistics', [StatisticsController::class, 'index'])->name('admin.statistics');
    Route::get('/admin/settings', [AdminSettingController::class, 'index'])->name('admin_settings.index');
    Route::put('/admin/settings/{adminSetting}', [AdminSettingController::class, 'update'])->name('admin_settings.update');
    Route::get('admin/device-detections', [App\Http\Controllers\DeviceDetectionController::class, 'index'])->name('admin.device-detections.index');
});

//qr codes
Route::get('/qr-codes', [QrCodeController::class, 'index'])->name('qr-codes.index');
Route::post('/qr-codes/{book}', [QrCodeController::class, 'generateQrCode'])->name('qr-codes.store');
