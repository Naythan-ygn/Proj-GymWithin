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
        // 1. Check if the user is logged in
        // 2. Check if the user's role is NOT 'admin'
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            // Redirect non-admins to the welcome/home page
            return redirect()->route('home')->with('error', 'Access denied. Admins only.');
        }

        return $next($request);
    }
}
