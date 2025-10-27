<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TransactionController extends Controller
{
    public function index()
    {
        $tx = Transaction::with(['customer','book'])->latest('id')->get();
        return response()->json(['data' => $tx], 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => ['required','exists:users,id'],
            'book_id'     => ['required','exists:books,id'],
            'total_amount'=> ['required','numeric','min:0'],
        ]);

        $tx = Transaction::create([
            'order_number' => strtoupper(Str::random(10)),
            'customer_id'  => $validated['customer_id'],
            'book_id'      => $validated['book_id'],
            'total_amount' => $validated['total_amount'],
        ])->load(['customer','book']);

        return response()->json([
            'success' => true,
            'message' => 'Transaction created',
            'data'    => $tx,
        ], 201);
    }
}
