<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckIfUserSuspended
{
    // Block suspended users
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->isSuspended()) {
            return response()->json(['message' => 'User is suspended'], 403);
        }

        return $next($request);
    }
}
