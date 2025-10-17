<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\GenreController;

// sanity check (opsional)
Route::get('ping', fn() => response()->json(['pong'=>true]));

// AUTHORS
Route::get ('authors', [AuthorController::class, 'index']); // READ ALL
Route::post('authors', [AuthorController::class, 'store']); // CREATE

// GENRES
Route::get ('genres',  [GenreController::class, 'index']);  // READ ALL
Route::post('genres',  [GenreController::class, 'store']);  // CREATE
