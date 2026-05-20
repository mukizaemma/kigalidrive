<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HotelBooking;
use App\Models\BookingComment;
use App\Models\BookingStayModification;
use App\Models\Hotel;
use App\Models\Property;
use App\Models\Unit;
use App\Support\SchemaHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\BookingCommentNotification;

class AdminBookingsController extends Controller
{
    public function index(Request $request)
    {
        $with = ['user', 'property.owner', 'unit'];
        if (SchemaHelper::legacyHotelsEnabled()) {
            $with[] = 'hotel';
            if (SchemaHelper::hasTable('hotel_rooms')) {
                $with[] = 'room';
            }
        }
        $query = HotelBooking::with($with);

        $user = auth()->user();
        $isAdmin = $user && ($user->role == '1' || $user->role == 2);
        if (! $isAdmin) {
            $query->where(function ($q) use ($user) {
                $q->whereHas('property', function ($q2) use ($user) {
                    $q2->where('owner_id', $user->id);
                });
                if (SchemaHelper::legacyHotelsEnabled()) {
                    $q->orWhereHas('hotel', function ($q2) use ($user) {
                        $q2->where('added_by', $user->id);
                    });
                }
            });
        }

        if ($request->filled('listing')) {
            $listing = $request->input('listing');
            if (str_starts_with($listing, 'hotel:') && SchemaHelper::hasColumn('hotel_bookings', 'hotel_id')) {
                $query->where('hotel_id', (int) substr($listing, 6));
            } elseif (str_starts_with($listing, 'property:')) {
                $query->where('property_id', (int) substr($listing, 9));
            }
        } elseif ($request->filled('property_id')) {
            $query->where('property_id', $request->property_id);
        }

        if ($request->filled('date_from') && $request->filled('date_to')) {
            $from = $request->date_from;
            $to = $request->date_to;
            $query->whereDate('check_in', '<=', $to)->whereDate('check_out', '>=', $from);
        } elseif ($request->filled('date_from')) {
            $query->whereDate('check_out', '>=', $request->date_from);
        } elseif ($request->filled('date_to')) {
            $query->whereDate('check_in', '<=', $request->date_to);
        }

        if ($request->has('status') && $request->status) {
            $query->where('booking_status', $request->status);
        }

        if ($request->has('payment_status') && $request->payment_status) {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('reference_number', 'LIKE', "%$search%")
                  ->orWhere('guest_name', 'LIKE', "%$search%")
                  ->orWhere('guest_email', 'LIKE', "%$search%")
                  ->orWhereHas('user', function ($userQuery) use ($search) {
                      $userQuery->where('name', 'LIKE', "%$search%")
                                ->orWhere('email', 'LIKE', "%$search%");
                  });
            });
        }

        $bookings = $query->latest('created_at')->paginate(20)->withQueryString();

        $properties = $isAdmin ? Property::active()->get() : Property::where('owner_id', $user->id)->get();
        $hotelsForFilter = collect();
        if (SchemaHelper::legacyHotelsEnabled()) {
            $hotelsForFilter = $isAdmin
                ? Hotel::query()->orderBy('name')->get(['id', 'name'])
                : Hotel::query()->where('added_by', $user->id)->orderBy('name')->get(['id', 'name']);
        }

        $setting = \App\Models\Setting::first();

        return view('admin.bookings.index', [
            'bookings' => $bookings,
            'properties' => $properties,
            'hotelsForFilter' => $hotelsForFilter,
            'setting' => $setting,
        ]);
    }

    public function show($id)
    {
        try {
            $booking = HotelBooking::with(['user', 'property.owner', 'unit', 'comments.user', 'stayModifications.requester'])->findOrFail($id);
        } catch (\Illuminate\Database\QueryException $e) {
            $booking = HotelBooking::with(['user', 'property.owner', 'unit'])->findOrFail($id);
            $booking->setRelation('comments', collect());
            if (!\Schema::hasTable('booking_stay_modifications')) {
                $booking->setRelation('stayModifications', collect());
            }
        }
        $user = auth()->user();
        $isAdmin = $user && ($user->role == 1 || $user->role == 2);
        $isOwner = !$isAdmin && $booking->property && $booking->property->owner_id == $user->id;
        $setting = \App\Models\Setting::first();

        return view('admin.bookings.show', [
            'booking' => $booking,
            'setting' => $setting,
            'isAdmin' => $isAdmin,
            'isOwner' => $isOwner,
        ]);
    }

    /**
     * Update booking status (approve/reject).
     */
    public function updateStatus(Request $request, $id)
    {
        $booking = HotelBooking::findOrFail($id);

        $request->validate([
            'booking_status' => 'required|in:pending,confirmed,cancelled',
            'payment_status' => 'nullable|in:pending,paid,refunded',
        ]);

        $booking->update([
            'booking_status' => $request->booking_status,
            'payment_status' => $request->payment_status ?? $booking->payment_status,
        ]);

        // If confirmed, update unit availability
        if ($request->booking_status === 'confirmed' && $booking->unit_id) {
            $unit = Unit::find($booking->unit_id);
            if ($unit && $unit->available_units > 0) {
                $unit->decrement('available_units');
            }
        }

        // If cancelled, restore unit availability
        if ($request->booking_status === 'cancelled' && $booking->unit_id) {
            $unit = Unit::find($booking->unit_id);
            if ($unit) {
                $unit->increment('available_units');
            }
        }

        return redirect()->back()
            ->with('success', 'Booking status updated successfully');
    }

    /**
     * Store a comment on the booking and notify the guest.
     */
    public function storeComment(Request $request, $id)
    {
        $booking = HotelBooking::with(['property'])->findOrFail($id);

        $request->validate([
            'comment' => 'required|string|max:5000',
        ]);

        $user = auth()->user();
        $isAdmin = $user && ($user->role == '1' || $user->role == 2);
        $authorType = $isAdmin ? 'staff' : 'owner';
        $authorLabel = $isAdmin ? 'Kigali Drive Rentals team' : 'Property';
        $authorName = $user->name ?? 'Team';

        try {
            $comment = BookingComment::create([
                'hotel_booking_id' => $booking->id,
                'user_id' => $user->id,
                'author_type' => $authorType,
                'comment' => $request->comment,
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            \Log::error('Booking comment create failed: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Could not save comment. Please ensure the database is migrated (run: php artisan migrate).')
                ->withInput();
        }

        $guestEmail = $booking->guest_email ?? ($booking->user->email ?? null);
        try {
            if ($guestEmail) {
                $createdAtFormatted = $comment->created_at->format('F d, Y h:i A');
                Mail::to($guestEmail)->send(new BookingCommentNotification(
                    $booking,
                    $authorLabel,
                    $authorName,
                    $comment->comment,
                    $createdAtFormatted
                ));
            }
        } catch (\Exception $e) {
            \Log::error('Failed to send booking comment notification: ' . $e->getMessage());
        }

        return redirect()->back()->with('success', $guestEmail ? 'Comment added and guest notified.' : 'Comment added.');
    }

    /**
     * Request a stay modification (guest left early) - owner or admin.
     */
    public function storeStayModification(Request $request, $id)
    {
        $booking = HotelBooking::with('property')->findOrFail($id);

        $request->validate([
            'actual_check_out' => 'required|date|after_or_equal:' . $booking->check_in,
            'reason' => 'required|string|max:2000',
        ]);

        $user = auth()->user();
        $isAdmin = $user && ($user->role == 1 || $user->role == 2);
        $isOwner = $booking->property && $booking->property->owner_id == $user->id;
        if (!$isAdmin && !$isOwner) {
            abort(403);
        }

        if ($booking->actual_check_out && \Carbon\Carbon::parse($request->actual_check_out)->gt($booking->check_out)) {
            return redirect()->back()->with('error', 'Actual check-out cannot be later than original check-out.');
        }

        BookingStayModification::create([
            'hotel_booking_id' => $booking->id,
            'requested_by' => $user->id,
            'actual_check_out' => $request->actual_check_out,
            'reason' => $request->reason,
            'status' => 'pending',
        ]);

        return redirect()->back()->with('success', 'Stay modification requested. Kigali Drive Rentals will review it.');
    }

    /**
     * Approve a stay modification (admin only).
     */
    public function approveStayModification($bookingId, $modificationId)
    {
        $user = auth()->user();
        if (!$user || ($user->role != 1 && $user->role != 2)) {
            abort(403);
        }
        $mod = BookingStayModification::where('hotel_booking_id', $bookingId)->findOrFail($modificationId);
        if ($mod->status !== 'pending') {
            return redirect()->back()->with('error', 'This modification has already been processed.');
        }
        $booking = $mod->hotelBooking;

        $checkIn = \Carbon\Carbon::parse($booking->check_in);
        $checkOut = \Carbon\Carbon::parse($mod->actual_check_out);
        $nights = $checkIn->diffInDays($checkOut);
        $unit = $booking->unit;
        $pricePerNight = $unit && $unit->base_price_per_night ? (float) $unit->base_price_per_night : 0;
        $adjustedTotal = $nights > 0 ? round($pricePerNight * $nights, 2) : 0;
        $commissionRate = 10;
        $adjustedCommission = round($adjustedTotal * ($commissionRate / 100), 2);

        $mod->update([
            'status' => 'approved',
            'reviewed_by' => $user->id,
            'reviewed_at' => now(),
            'adjusted_total_amount' => $adjustedTotal,
            'adjusted_commission_amount' => $adjustedCommission,
        ]);

        $booking->update([
            'total_amount' => $adjustedTotal,
            'commission_amount' => $adjustedCommission,
            'check_out' => $mod->actual_check_out,
        ]);

        return redirect()->back()->with('success', 'Stay modification approved. Booking amounts updated.');
    }

    /**
     * Reject a stay modification (admin only).
     */
    public function rejectStayModification(Request $request, $bookingId, $modificationId)
    {
        $user = auth()->user();
        if (!$user || ($user->role != 1 && $user->role != 2)) {
            abort(403);
        }
        $mod = BookingStayModification::where('hotel_booking_id', $bookingId)->findOrFail($modificationId);
        if ($mod->status !== 'pending') {
            return redirect()->back()->with('error', 'This modification has already been processed.');
        }
        $mod->update([
            'status' => 'rejected',
            'reviewed_by' => $user->id,
            'reviewed_at' => now(),
            'admin_notes' => $request->input('admin_notes'),
        ]);
        return redirect()->back()->with('success', 'Stay modification rejected.');
    }

    /**
     * Remove the specified booking.
     */
    public function destroy($id)
    {
        $booking = HotelBooking::findOrFail($id);
        $booking->delete();

        return redirect()->route('admin.bookings.index')
            ->with('success', 'Booking deleted successfully');
    }
}










