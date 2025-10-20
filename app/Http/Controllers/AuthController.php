<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $r): JsonResponse
    {
        $data = $r->validate([
            'name' => 'required|string|max:255',
            'email'=> 'required|email|unique:users,email',
            'password'=>'required|string|min:6',
            'is_admin'=>'sometimes|boolean',
        ]);

        $user = User::create([
            'name'=>$data['name'],
            'email'=>$data['email'],
            'password'=>Hash::make($data['password']),
            'is_admin'=>$data['is_admin'] ?? false,
        ]);

        $token = $user->createToken('api')->plainTextToken;

        return response()->json(['success'=>true,'token'=>$token,'user'=>$user], 201);
    }

    public function login(Request $r): JsonResponse
    {
        $data = $r->validate(['email'=>'required|email','password'=>'required|string']);
        $user = User::where('email',$data['email'])->first();

        if (!$user || !Hash::check($data['password'],$user->password)) {
            return response()->json(['success'=>false,'message'=>'Invalid credentials'], 401);
        }

        $token = $user->createToken('api')->plainTextToken;
        return response()->json(['success'=>true,'token'=>$token,'user'=>$user]);
    }

    public function logout(Request $r): JsonResponse
    {
        $r->user()?->currentAccessToken()?->delete();
        return response()->json(['success'=>true,'message'=>'Logged out']);
    }
}
