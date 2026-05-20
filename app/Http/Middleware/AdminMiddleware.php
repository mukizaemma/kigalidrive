<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            $role = (int) (auth()->user()->role ?? 0);

            if (in_array($role, [1, 2], true)) {
                return $next($request);
            }
        }
    
        return redirect('/')->with('error', 'Logged in as a Guest.');
    }
}
