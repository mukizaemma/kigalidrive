<?php

namespace App\Http\Controllers;

use App\Models\CarRentalRequest;
use Illuminate\Http\Request;

class CarRentalRequestController extends Controller
{
    /**
     * Store a new generic car rental request from the public Car Rental page.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:50',
            'car_type' => 'required|string|max:255',
            'people' => 'nullable|integer|min:1',
            'rental_date' => 'nullable|date|after_or_equal:today',
            'message' => 'nullable|string|max:2000',
        ]);

        CarRentalRequest::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'car_type' => $validated['car_type'],
            'people' => $validated['people'] ?? null,
            'rental_date' => $validated['rental_date'] ?? null,
            'message' => $validated['message'] ?? null,
            'status' => 'pending',
        ]);

        return redirect()->back()->with('success', '✅ Your car rental request has been submitted. Our team will contact you shortly.');
    }
}

