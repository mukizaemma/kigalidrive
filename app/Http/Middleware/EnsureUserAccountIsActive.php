<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserAccountIsActive
{
    /**
     * Log out users whose account status is Inactive (suspended).
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $status = Auth::user()->status ?? 'Active';
            if ($status === 'Inactive') {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('login')
                    ->with('error', 'Your account has been suspended. Please contact support if you believe this is a mistake.');
            }
        }

        return $next($request);
    }
}
