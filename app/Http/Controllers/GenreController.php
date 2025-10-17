<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use Illuminate\Http\Request;

class GenreController extends Controller
{
    public function index()
    {
        return response()->json(['success' => true, 'data' => Genre::all()]);
    }

    public function store(Request $request)
    {
        $data = $request->validate(['name' => 'required|string|max:255']);
        $genre = Genre::create($data);
        return response()->json(['success' => true, 'message' => 'Genre created', 'data' => $genre], 201);
    }

    public function show(Genre $genre)
    {
        return response()->json(['success' => true, 'data' => $genre]);
    }

    public function update(Request $request, Genre $genre)
    {
        $data = $request->validate(['name' => 'sometimes|required|string|max:255']);
        $genre->update($data);
        return response()->json(['success' => true, 'message' => 'Genre updated', 'data' => $genre]);
    }

    public function destroy(Genre $genre)
    {
        $genre->delete();
        return response()->json(['success' => true, 'message' => 'Genre deleted']);
    }
}
