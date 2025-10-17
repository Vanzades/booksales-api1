<?php

namespace App\Http\Controllers;

use App\Models\Author;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AuthorController extends Controller
{
    // READ ALL (sudah ada)
    public function index(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => Author::withCount('books')->get()
        ]);
    }

    // CREATE (sudah ada)
    public function store(Request $request): JsonResponse
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

    // 🔎 SHOW
    public function show($id): JsonResponse
    {
        $author = Author::withCount('books')->find($id);
        if (!$author) {
            return response()->json([
                'success' => false,
                'message' => 'Author not found'
            ], 404);
        }

        return response()->json(['success' => true, 'data' => $author]);
    }

    // ✏️ UPDATE
    public function update(Request $request, $id): JsonResponse
    {
        $author = Author::find($id);
        if (!$author) {
            return response()->json([
                'success' => false,
                'message' => 'Author not found'
            ], 404);
        }

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

    // 🗑️ DESTROY
    public function destroy($id): JsonResponse
    {
        $author = Author::find($id);
        if (!$author) {
            return response()->json([
                'success' => false,
                'message' => 'Author not found'
            ], 404);
        }

        $author->delete();

        return response()->json([
            'success' => true,
            'message' => 'Author deleted'
        ]);
    }
}
