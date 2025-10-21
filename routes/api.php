<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    AuthController,
    AuthorController,
    GenreController,
    TransactionController
};

// -----------------------------
//  AUTHENTICATION ROUTES
// -----------------------------
Route::post('auth/register', [AuthController::class, 'register']);
Route::post('auth/login', [AuthController::class, 'login']);
Route::post('auth/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

// Optional health check
Route::get('ping', fn() => response()->json(['pong' => true]));

// -----------------------------
//  PUBLIC ROUTES (tanpa login)
// -----------------------------
Route::apiResource('authors', AuthorController::class)->only(['index', 'show']);
Route::apiResource('genres',  GenreController::class)->only(['index', 'show']);

// -----------------------------
//  ADMIN ROUTES (login + is_admin=true)
// -----------------------------
Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    // CRUD for authors & genres
    Route::apiResource('authors', AuthorController::class)->only(['store', 'update', 'destroy']);
    Route::apiResource('genres',  GenreController::class)->only(['store', 'update', 'destroy']);

    // ADMIN-only for transactions
    Route::apiResource('transactions', TransactionController::class)->only(['index', 'destroy']);
});

// -----------------------------
//  CUSTOMER ROUTES (login + !admin)
// -----------------------------
Route::middleware(['auth:sanctum', 'customer'])->group(function () {
    // CUSTOMER-only for transactions
    Route::apiResource('transactions', TransactionController::class)->only(['store', 'update', 'show']);
});
