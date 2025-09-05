<?php
// app/Http/Middleware/RoleMiddleware.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Check if user is active
        if (!$user->isActive()) {
            Auth::logout();
            return redirect()->route('login')->with('error', 'Your account is inactive. Please contact administrator.');
        }

        // Check if user has required role
        if (!empty($roles) && !$user->hasAnyRole($roles)) {
            abort(403, 'Unauthorized. You do not have permission to access this resource.');
        }

        return $next($request);
    }
}