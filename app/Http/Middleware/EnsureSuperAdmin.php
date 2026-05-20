<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSuperAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! auth()->check() || auth()->user()->email !== 'admin@iremetech.com') {
            return redirect()->route('dashboard')
                ->with('error', 'Only the super admin can access this section.');
        }

        return $next($request);
    }
}
