<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request for Authorization.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string $role
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!auth()->check()) {
            return redirect('login');
        }

        $userRole = auth()->user()->role; // 'lender', 'borrower', 'librarian', 'technician'

        // Basic authorization check
        if ($userRole !== $role) {
            abort(403, 'Unauthorized Access. You do not have the required role.');
        }

        return $next($request);
    }
}
