<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->access_level === 'ADMIN') {
            return $next($request);
        }

        return redirect('/')->with('error', 'Você não tem permissão para acessar esta página.');
    }
}