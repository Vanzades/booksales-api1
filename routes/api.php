<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\GenreController;

// opsional: health check
Route::get('ping', fn() => response()->json(['pong' => true]));

// CRUD lengkap (index, store, show, update, destroy)
Route::apiResource('authors', AuthorController::class);
Route::apiResource('genres',  GenreController::class);
