<?php

namespace App\Http\Controllers;

use App\Models\Author;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    public function index()
    {
        $authors = Author::withCount('books')->get();

        return response()->json([
            'success' => true,
            'data' => $authors
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'nullable|email|unique:authors,email',
            'bio'   => 'nullable|string'
        ]);

        $author = Author::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Author created',
            'data' => $author
        ], 201);
    }

    public function show(Author $author)
    {
        $author->loadCount('books');

        return response()->json([
            'success' => true,
            'data' => $author
        ]);
    }

    public function update(Request $request, Author $author)
    {
        $data = $request->validate([
            'name'  => 'sometimes|required|string|max:255',
            'email' => 'nullable|email|unique:authors,email,' . $author->id,
            'bio'   => 'nullable|string'
        ]);

        $author->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Author updated',
            'data' => $author
        ]);
    }

    public function destroy(Author $author)
    {
        $author->delete();

        return response()->json([
            'success' => true,
            'message' => 'Author deleted'
        ]);
    }
}
