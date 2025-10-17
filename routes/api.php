<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\GenreController;

Route::get('ping', fn() => response()->json(['pong' => true]));

// CRUD lengkap: index, store, show, update, destroy
Route::apiResource('authors', AuthorController::class);
Route::apiResource('books',   BookController::class);
Route::apiResource('genres',  GenreController::class);
