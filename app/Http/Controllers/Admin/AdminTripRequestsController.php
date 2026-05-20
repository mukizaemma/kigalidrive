<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\ReservationConfirmed;
use App\Models\Reservation;
use App\Models\Trip;
use App\Models\TripDestination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class AdminTripRequestsController extends Controller
{
    public function index(Request $request)
    {
        $query = Reservation::with(['tour', 'tripDestination'])
            ->where('service_type', 'tour_booking')
            ->orderByDesc('created_at');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $requests = $query->paginate(20)->appends($request->query());
        $setting = \App\Models\Setting::first();

        return view('admin.trip_requests.index', [
            'requests' => $requests,
            'setting' => $setting,
        ]);
    }

    public function show($id)
    {
        $requestItem = Reservation::with(['tour', 'tripDestination'])->findOrFail($id);
        $tripIds = $requestItem->selected_trip_ids ? json_decode($requestItem->selected_trip_ids, true) : [];
        $selectedTrips = $tripIds ? Trip::whereIn('id', $tripIds)->get() : collect();
        $setting = \App\Models\Setting::first();

        return view('admin.trip_requests.show', [
            'requestItem' => $requestItem,
            'selectedTrips' => $selectedTrips,
            'setting' => $setting,
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled',
            'admin_response' => 'nullable|string',
            'quoted_cost' => 'nullable|numeric|min:0',
        ]);

        $requestItem = Reservation::findOrFail($id);
        $requestItem->status = $request->status;
        $requestItem->admin_response = $request->admin_response;
        $requestItem->quoted_cost = $request->quoted_cost;
        $requestItem->responded_at = now();
        $requestItem->save();

        // Send confirmation email to guest when status is confirmed
        if ($request->status === 'confirmed' && $requestItem->email) {
            try {
                Mail::to($requestItem->email)->send(new ReservationConfirmed($requestItem));
            } catch (\Exception $e) {
                \Log::error('Failed to send reservation confirmation email: ' . $e->getMessage());
            }
        }

        return redirect()->route('admin.tripRequests.show', $id)
            ->with('success', 'Trip request updated successfully.');
    }
}
