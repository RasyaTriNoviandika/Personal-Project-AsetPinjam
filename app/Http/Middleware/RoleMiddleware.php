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
        
        // Pastikan user memiliki role
        if (!$user->role) {
            abort(403, 'Role tidak ditemukan');
        }

        // Check jika role user ada di dalam daftar role yang diizinkan
        if (!in_array($user->role, $roles)) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Akses tidak diizinkan untuk role ' . $user->role], 403);
            }
            
            return redirect()->route('dashboard')->with('error', 'Akses tidak diizinkan untuk role ' . $user->role);
        }

        return $next($request);
    }
}