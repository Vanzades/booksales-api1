<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use Illuminate\Http\Request;

class GenreController extends Controller
{
    public function index()
    {
        $genres = Genre::withCount('books')->latest('id')->get();
        return response()->json(['data' => $genres], 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        $genre = Genre::create($validated)->loadCount('books');

        return response()->json([
            'success' => true,
            'message' => 'Genre created',
            'data'    => $genre,
        ], 201);
    }

    public function show($id)
    {
        $genre = \App\Models\Genre::withCount('books')->findOrFail($id);
        return response()->json(['data' => $genre]);
    }

    public function update(Request $request, $id)
    {
        $genre = \App\Models\Genre::findOrFail($id);
        $validated = $request->validate([
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);
        $genre->fill($validated)->save();
        $genre->loadCount('books');
        return response()->json(['success' => true, 'message' => 'Genre updated', 'data' => $genre]);
    }

    public function destroy($id)
    {
        $genre = \App\Models\Genre::findOrFail($id);
        $genre->delete();
        return response()->json(['success' => true, 'message' => 'Genre deleted']);
    }
}
