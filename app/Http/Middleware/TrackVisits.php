<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class TrackVisits
{

    public function handle(Request $request, Closure $next): Response
    {
        $ipAddress = $request->ip();
        $userAgent = $request->userAgent();
        $referrer = $request->headers->get('referer');
        $timestamp = now();

        DB::table('visits')->insert([
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
            'referrer' => $referrer,
            'timestamp' => $timestamp,
        ]);
        return $next($request);
    }
}
