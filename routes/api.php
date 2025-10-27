<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\TransactionController;

// Read-only data
Route::get('/authors',        [AuthorController::class, 'index']);
Route::get('/authors/{id}',   [AuthorController::class, 'show']);
Route::get('/genres',         [GenreController::class, 'index']);
Route::get('/genres/{id}',    [GenreController::class, 'show']);
Route::get('/books',          [BookController::class, 'index']);
Route::get('/books/{id}',     [BookController::class, 'show']);

// Transaksi TIDAK DIHALANGI (public)
Route::get('/transactions',       [TransactionController::class, 'index']);
Route::get('/transactions/{id}',  [TransactionController::class, 'show']);
Route::post('/transactions',      [TransactionController::class, 'store']);
// (opsional) kalau kamu ingin update/delete transaksi juga publik, tambahkan baris di bawah.
// Route::put('/transactions/{id}',   [TransactionController::class, 'update']);
// Route::delete('/transactions/{id}',[TransactionController::class, 'destroy']);

Route::middleware(['auth:sanctum'])->group(function () {
    // Authors
    Route::post('/authors',        [AuthorController::class, 'store']);
    Route::put('/authors/{id}',    [AuthorController::class, 'update']);
    Route::delete('/authors/{id}', [AuthorController::class, 'destroy']);

    // Genres
    Route::post('/genres',         [GenreController::class, 'store']);
    Route::put('/genres/{id}',     [GenreController::class, 'update']);
    Route::delete('/genres/{id}',  [GenreController::class, 'destroy']);

    // Books
    Route::post('/books',          [BookController::class, 'store']);
    Route::put('/books/{id}',      [BookController::class, 'update']);
    Route::delete('/books/{id}',   [BookController::class, 'destroy']);
});
