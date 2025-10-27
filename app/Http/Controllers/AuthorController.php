<?php

namespace App\Http\Controllers;

use App\Models\Author;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AuthorController extends Controller
{
    public function index()
    {
        $authors = Author::withCount('books')->latest('id')->get();
        return response()->json(['data' => $authors], 200);
    }

    public function store(Request $request)
    {
        $rules = [
            'name'  => ['required', 'string', 'max:255'],
            'bio'   => ['nullable', 'string'],
            'email' => ['nullable', 'email', 'max:255', Rule::unique('authors', 'email')], // jika kamu nanti menambah kolom email
        ];

        if ($request->hasFile('photo')) {
            $rules['photo'] = ['file', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'];
        } elseif ($request->filled('photo')) {
            $rules['photo'] = ['string']; // base64
        } else {
            $rules['photo'] = ['nullable'];
        }

        $validated = $request->validate($rules);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('authors', 'public');
        } elseif ($request->filled('photo')) {
            $photoPath = $this->storeBase64($request->input('photo'), 'authors');
        }

        $author = Author::create([
            'name'  => $validated['name'],
            'bio'   => $validated['bio'] ?? null,
            'photo' => $photoPath,
        ])->loadCount('books');

        return response()->json([
            'success' => true,
            'message' => 'Author created',
            'data'    => $author,
        ], 201);
    }

    private function storeBase64(?string $dataUrl, string $dir): ?string
    {
        if (!$dataUrl) return null;
        if (!preg_match('/^data:image\/(\w+);base64,/', $dataUrl, $m)) return null;
        $ext = strtolower($m[1]);
        if (!in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) return null;
        $data = base64_decode(substr($dataUrl, strpos($dataUrl, ',') + 1), true);
        if ($data === false) return null;
        $filename = $dir . '/' . Str::uuid() . '.' . $ext;
        Storage::disk('public')->put($filename, $data);
        return $filename;
    }

    public function show($id)
    {
        $author = \App\Models\Author::withCount('books')->findOrFail($id);
        return response()->json(['data' => $author]);
    }

    public function update(Request $request, $id)
    {
        $author = \App\Models\Author::findOrFail($id);

        $rules = [
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'bio'  => ['nullable', 'string'],
            'photo' => ['nullable'], // file image atau base64 (opsional)
        ];
        if ($request->hasFile('photo')) {
            $rules['photo'] = ['file', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'];
        }
        $validated = $request->validate($rules);

        // update fields biasa
        if (isset($validated['name'])) $author->name = $validated['name'];
        if (array_key_exists('bio', $validated)) $author->bio = $validated['bio'];

        // update foto (opsional)
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('authors', 'public');
            $author->photo = $path;
        } elseif ($request->filled('photo') && preg_match('/^data:image\/(\w+);base64,/', $request->photo)) {
            $ext = strtolower(explode('/', explode(';', $request->photo)[0])[1]);
            $data = base64_decode(substr($request->photo, strpos($request->photo, ',') + 1));
            $filename = 'authors/' . \Illuminate\Support\Str::uuid() . '.' . $ext;
            \Illuminate\Support\Facades\Storage::disk('public')->put($filename, $data);
            $author->photo = $filename;
        }

        $author->save();
        $author->loadCount('books');

        return response()->json(['success' => true, 'message' => 'Author updated', 'data' => $author]);
    }

    public function destroy($id)
    {
        $author = \App\Models\Author::findOrFail($id);
        $author->delete();
        return response()->json(['success' => true, 'message' => 'Author deleted']);
    }
}
