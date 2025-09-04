<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();
        
        // Jika tidak ada role di user atau untuk backward compatibility
        if (!isset($user->role) || !$user->role) {
            // Allow access if no role system is implemented
            return $next($request);
        }

        // Check if user has any of the required roles
        if (!in_array($user->role, $roles)) {
            abort(403, 'Akses tidak diizinkan untuk role Anda');
        }

        return $next($request);
    }
}