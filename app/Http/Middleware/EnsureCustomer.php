<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureCustomer
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['success'=>false,'message'=>'Unauthenticated'], 401);
        }
        if ($user->is_admin) {
            return response()->json(['success'=>false,'message'=>'Forbidden: customers only'], 403);
        }
        return $next($request);
    }
}
