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
            // Check if the current user role matches any of the allowed roles
            if ($role === 'pegawai' && $user->isPegawai()) {
                return $next($request);
            } else if ($role === 'atasan' && $user->isAtasan()) {
                return $next($request);
            } else if ($role === 'kepalaDepartemen' && $user->isKepalaDepartemen()) {
                return $next($request);
            } else if ($role === 'hrd' && $user->isHRD()) {
                return $next($request);
            }
        }
        
        return abort(403, 'Unauthorized action.');
    }
}