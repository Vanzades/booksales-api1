<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BookController extends Controller
{
    public function index()
    {
        $books = Book::with(['author','genre'])->latest('id')->get();
        return response()->json(['data' => $books], 200);
    }

    public function store(Request $request)
    {
        $rules = [
            'title'       => ['required','string','max:255'],
            'description' => ['nullable','string'],
            'price'       => ['required','numeric','min:0'],
            'stock'       => ['required','integer','min:0'],
            'genre_id'    => ['required','exists:genres,id'],
            'author_id'   => ['required','exists:authors,id'],
        ];

        if ($request->hasFile('cover_photo')) {
            $rules['cover_photo'] = ['file','image','mimes:jpg,jpeg,png,webp','max:4096'];
        } elseif ($request->filled('cover_photo')) {
            $rules['cover_photo'] = ['string']; // base64
        } else {
            $rules['cover_photo'] = ['nullable'];
        }

        $validated = $request->validate($rules);

        $cover = null;
        if ($request->hasFile('cover_photo')) {
            $cover = $request->file('cover_photo')->store('book_covers', 'public');
        } elseif ($request->filled('cover_photo')) {
            $cover = $this->storeBase64($request->input('cover_photo'), 'book_covers');
        }

        $book = Book::create([
            'title'       => $validated['title'],
            'description' => $validated['description'] ?? null,
            'price'       => $validated['price'],
            'stock'       => $validated['stock'],
            'genre_id'    => $validated['genre_id'],
            'author_id'   => $validated['author_id'],
            'cover_photo' => $cover,
        ])->load(['author','genre']);

        return response()->json([
            'success' => true,
            'message' => 'Book created',
            'data'    => $book,
        ], 201);
    }

    private function storeBase64(?string $dataUrl, string $dir): ?string
    {
        if (!$dataUrl) return null;
        if (!preg_match('/^data:image\/(\w+);base64,/', $dataUrl, $m)) return null;
        $ext = strtolower($m[1]);
        if (!in_array($ext, ['jpg','jpeg','png','webp'])) return null;
        $data = base64_decode(substr($dataUrl, strpos($dataUrl, ',')+1), true);
        if ($data === false) return null;
        $filename = $dir.'/'.Str::uuid().'.'.$ext;
        Storage::disk('public')->put($filename, $data);
        return $filename;
    }
}
