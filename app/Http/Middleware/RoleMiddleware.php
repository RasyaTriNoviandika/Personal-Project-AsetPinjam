<?php
// app/Http/Middleware/RoleMiddleware.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string  $roles
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();
        
        // Check if user has role
        if (!$user->role) {
            abort(403, 'No role assigned to your account. Please contact administrator.');
        }

        // Check if user's role is in allowed roles
        if (!in_array($user->role, $roles)) {
            abort(403, 'You do not have permission to access this resource.');
        }

        return $next($request);
    }
}