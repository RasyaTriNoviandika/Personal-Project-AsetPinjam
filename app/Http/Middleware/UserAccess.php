<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserAccess
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        
        // Allow both admin and regular users
        if (!in_array($user->role, ['admin', 'user', 'operator'])) {
            abort(403, 'Unauthorized access');
        }

        return $next($request);
    }
}