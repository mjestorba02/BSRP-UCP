<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckUserToken
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken(); // gets token from Authorization: Bearer ...

        if ($token !== config('auth.api_token')) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return $next($request);
    }
}