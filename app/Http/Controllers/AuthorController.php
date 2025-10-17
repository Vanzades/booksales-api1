<?php

namespace App\Http\Controllers;

use App\Models\Author;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    // READ ALL
    public function index()
    {
        $authors = Author::withCount('books')->get();
        return response()->json(['success'=>true,'data'=>$authors], 200);
    }

    // CREATE
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'nullable|email|unique:authors,email',
            'bio'   => 'nullable|string'
        ]);

        $author = Author::create($data);
        return response()->json(['success'=>true,'message'=>'Author created','data'=>$author], 201);
    }
}
