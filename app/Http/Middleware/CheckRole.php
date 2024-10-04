<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle($request, Closure $next, $role)
    {
        if (Auth::check() && Auth::user()->role == $role) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses pada menu ini');
        }

        return $next($request);
    }
}
