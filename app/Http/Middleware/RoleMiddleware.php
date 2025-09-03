<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();
        
        // Jika user memiliki role dan role sesuai dengan yang diizinkan
        if (property_exists($user, 'role') && in_array($user->role, $roles)) {
            return $next($request);
        }

        // Jika tidak ada role system atau untuk backward compatibility
        if (!property_exists($user, 'role') || !$user->role) {
            return $next($request);
        }

        abort(403, 'Akses tidak diizinkan');
    }
}
