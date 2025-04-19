<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect('login');
        }

        $user = Auth::user();
        
        foreach($roles as $role) {
            // Periksa apakah peran pengguna saat ini cocok dengan peran yang diizinkan
            $method = 'is' . ucfirst($role);
            if (method_exists($user, $method) && $user->$method()) {
                return $next($request);
            }
        }
        
        return abort(403, 'Unauthorized action.');
    }
}