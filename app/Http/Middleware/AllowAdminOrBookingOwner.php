<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AllowAdminOrBookingOwner
{
    /**
     * Allow admin (role 1 or 2) or property owner for the booking being accessed.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        if (!$user) {
            return redirect()->route('login');
        }

        if ($user->role == '1' || $user->role == 2) {
            return $next($request);
        }

        $bookingId = $request->route('id');
        if (!$bookingId) {
            return $next($request);
        }

        $booking = \App\Models\HotelBooking::with('property')->find($bookingId);
        if (!$booking || !$booking->property) {
            return $next($request);
        }

        if ((int) $booking->property->owner_id === (int) $user->id) {
            return $next($request);
        }

        abort(403, 'You do not have access to this booking.');
    }
}
