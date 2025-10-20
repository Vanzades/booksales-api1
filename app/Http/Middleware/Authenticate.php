<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    // Untuk API: jangan redirect ke /login, cukup 401 JSON
    protected function redirectTo($request): ?string
    {
        return null;
    }
}
