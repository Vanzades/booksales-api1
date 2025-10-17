<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use App\Models\Genre;
use Illuminate\Http\Request;


class GenreController extends Controller
{
    // READ ALL
    public function index()
    {
        try {
            $genres = \App\Models\Genre::query()
                ->select('id', 'name', 'created_at', 'updated_at')
                ->orderBy('id')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $genres,
            ], 200, [], JSON_UNESCAPED_UNICODE);
        } catch (\Throwable $e) {
            Log::error('GET /api/genres failed: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json(
                ['success' => false, 'message' => 'Internal error on genres index'],
                500
            );
        }
    }

    // CREATE
    public function store(Request $request)
    {
        $data = $request->validate(['name' => 'required|string|max:255']);

        $genre = \App\Models\Genre::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Genre created',
            'data' => $genre,
        ], 201);
    }
}
