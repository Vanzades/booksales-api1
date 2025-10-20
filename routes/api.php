<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{AuthorController, GenreController, AuthController};

Route::get('ping', fn() => response()->json(['pong'=>true]));

// Auth (untuk ambil token)
Route::post('auth/register', [AuthController::class, 'register']);
Route::post('auth/login',    [AuthController::class, 'login']);
Route::post('auth/logout',   [AuthController::class, 'logout'])->middleware('auth:sanctum');

// PUBLIC (tanpa login): Read All & Show
Route::get('authors',        [AuthorController::class, 'index']);
Route::get('authors/{id}',   [AuthorController::class, 'show']);
Route::get('genres',         [GenreController::class,  'index']);
Route::get('genres/{id}',    [GenreController::class,  'show']);

// ADMIN ONLY: Create, Update, Destroy
Route::middleware(['auth:sanctum','admin'])->group(function () {
    Route::post('authors',           [AuthorController::class, 'store']);
    Route::put('authors/{id}',       [AuthorController::class, 'update']);
    Route::delete('authors/{id}',    [AuthorController::class, 'destroy']);

    Route::post('genres',            [GenreController::class, 'store']);
    Route::put('genres/{id}',        [GenreController::class, 'update']);
    Route::delete('genres/{id}',     [GenreController::class, 'destroy']);
});
