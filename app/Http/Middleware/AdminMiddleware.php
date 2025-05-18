<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            // Check if the user has the 'admin' role
            if (Auth::user()->role !== 'admin') {
                // Redirect them if they are not an admin
                return redirect()->route('home')->with('error', 'You do not have admin access.');
            }
        }

        // Allow the request to proceed if the user is an admin
        return $next($request);    }
}
