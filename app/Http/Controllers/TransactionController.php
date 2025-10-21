<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    // ADMIN: read all
    public function index(): JsonResponse
    {
        $tx = Transaction::with(['user:id,name,email', 'book:id,title,isbn'])
            ->orderByDesc('id')
            ->get();

        return response()->json(['success'=>true,'data'=>$tx], 200);
    }

    // CUSTOMER: create
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'book_id'  => 'required|exists:books,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $book = Book::find($data['book_id']);
        $total = ($book->price ?? 0) * $data['quantity'];

        $tx = Transaction::create([
            'user_id'     => Auth::id(),
            'book_id'     => $data['book_id'],
            'quantity'    => $data['quantity'],
            'total_price' => $total,
            'status'      => 'pending',
        ])->load(['user:id,name,email','book:id,title,isbn']);

        return response()->json(['success'=>true,'message'=>'Transaction created','data'=>$tx], 201);
    }

    // CUSTOMER: show own OR ADMIN can see any
    public function show($id): JsonResponse
    {
        $tx = Transaction::with(['user:id,name,email','book:id,title,isbn'])->find($id);
        if (!$tx) {
            return response()->json(['success'=>false,'message'=>'Transaction not found'], 404);
        }

        $user = Auth::user();
        if (!$user->is_admin && $tx->user_id !== $user->id) {
            return response()->json(['success'=>false,'message'=>'Forbidden: not your transaction'], 403);
        }

        return response()->json(['success'=>true,'data'=>$tx], 200);
    }

    // CUSTOMER: update own
    public function update(Request $request, $id): JsonResponse
    {
        $tx = Transaction::find($id);
        if (!$tx) {
            return response()->json(['success'=>false,'message'=>'Transaction not found'], 404);
        }

        $user = Auth::user();
        if (!$user->is_admin && $tx->user_id !== $user->id) {
            return response()->json(['success'=>false,'message'=>'Forbidden: not your transaction'], 403);
        }

        $data = $request->validate([
            'book_id'  => 'sometimes|required|exists:books,id',
            'quantity' => 'sometimes|required|integer|min:1',
            'status'   => 'sometimes|required|string|in:pending,paid,cancelled',
        ]);

        if (isset($data['book_id'])) {
            $book = Book::find($data['book_id']);
            $tx->total_price = ($book->price ?? 0) * ($data['quantity'] ?? $tx->quantity);
        }
        if (isset($data['quantity']) && !isset($data['book_id'])) {
            $book = $tx->book; // relasi yang sudah ada
            $tx->total_price = ($book->price ?? 0) * $data['quantity'];
        }

        $tx->update($data);

        return response()->json(['success'=>true,'message'=>'Transaction updated','data'=>$tx->fresh()->load(['user:id,name,email','book:id,title,isbn'])]);
    }

    // ADMIN: destroy any
    public function destroy($id): JsonResponse
    {
        $tx = Transaction::find($id);
        if (!$tx) {
            return response()->json(['success'=>false,'message'=>'Transaction not found'], 404);
        }

        $tx->delete();
        return response()->json(['success'=>true,'message'=>'Transaction deleted'], 200);
    }
}
