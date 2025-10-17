<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BookController extends Controller
{
    public function index()
    {
        $books = Book::with('author')->get();

        return response()->json([
            'success' => true,
            'data' => $books
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'author_id'      => 'required|exists:authors,id',
            'title'          => 'required|string|max:255',
            'isbn'           => 'required|string|max:20|unique:books,isbn',
            'stock'          => 'nullable|integer|min:0',
            'price'          => 'nullable|numeric|min:0',
            'published_year' => 'nullable|integer|min:1000|max:9999'
        ]);

        $book = Book::create($data)->load('author');

        return response()->json([
            'success' => true,
            'message' => 'Book created',
            'data' => $book
        ], 201);
    }

    public function show(Book $book)
    {
        $book->load('author');

        return response()->json([
            'success' => true,
            'data' => $book
        ]);
    }

    public function update(Request $request, Book $book)
    {
        $data = $request->validate([
            'author_id'      => 'sometimes|required|exists:authors,id',
            'title'          => 'sometimes|required|string|max:255',
            'isbn'           => [
                'sometimes','required','string','max:20',
                Rule::unique('books','isbn')->ignore($book->id)
            ],
            'stock'          => 'nullable|integer|min:0',
            'price'          => 'nullable|numeric|min:0',
            'published_year' => 'nullable|integer|min:1000|max:9999'
        ]);

        $book->update($data);
        $book->load('author');

        return response()->json([
            'success' => true,
            'message' => 'Book updated',
            'data' => $book
        ]);
    }

    public function destroy(Book $book)
    {
        $book->delete();

        return response()->json([
            'success' => true,
            'message' => 'Book deleted'
        ]);
    }
}
