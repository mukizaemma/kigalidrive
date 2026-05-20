<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use App\Models\UnitAvailability;
use Illuminate\Http\Request;
use Carbon\Carbon;

class UnitAvailabilityController extends Controller
{
    /**
     * Store availability for a date range.
     */
    public function store(Request $request, $unitId)
    {
        $unit = Unit::findOrFail($unitId);

        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'status' => 'required|in:available,unavailable,booked',
            'available_units' => 'nullable|integer|min:0',
        ]);

        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);

        // Create availability for each date in range
        $dates = [];
        $currentDate = $startDate->copy();
        while ($currentDate->lte($endDate)) {
            UnitAvailability::updateOrCreate(
                [
                    'unit_id' => $unitId,
                    'date' => $currentDate->format('Y-m-d'),
                ],
                [
                    'status' => $request->status,
                    'available_units' => $request->available_units ?? $unit->available_units,
                ]
            );
            $currentDate->addDay();
        }

        return redirect()->back()
            ->with('success', 'Availability updated successfully');
    }

    /**
     * Bulk update availability.
     */
    public function bulkUpdate(Request $request, $unitId)
    {
        $unit = Unit::findOrFail($unitId);

        $request->validate([
            'dates' => 'required|array',
            'dates.*' => 'date',
            'status' => 'required|in:available,unavailable,booked',
        ]);

        foreach ($request->dates as $date) {
            UnitAvailability::updateOrCreate(
                [
                    'unit_id' => $unitId,
                    'date' => $date,
                ],
                [
                    'status' => $request->status,
                    'available_units' => $request->available_units ?? $unit->available_units,
                ]
            );
        }

        return redirect()->back()
            ->with('success', 'Availability updated successfully');
    }

    /**
     * Remove availability.
     */
    public function destroy($id)
    {
        $availability = UnitAvailability::findOrFail($id);
        $availability->delete();

        return redirect()->back()
            ->with('success', 'Availability removed successfully');
    }
}










