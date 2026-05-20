<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use App\Models\UnitPricing;
use Illuminate\Http\Request;

class UnitPricingController extends Controller
{
    /**
     * Store pricing for a unit.
     */
    public function store(Request $request, $unitId)
    {
        $unit = Unit::findOrFail($unitId);

        $request->validate([
            'price_per_night' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'is_active' => 'nullable|boolean',
        ]);

        UnitPricing::create([
            'unit_id' => $unitId,
            'price_per_night' => $request->price_per_night,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'is_active' => $request->has('is_active', true),
        ]);

        return redirect()->back()
            ->with('success', 'Pricing rule added successfully');
    }

    /**
     * Update pricing.
     */
    public function update(Request $request, $id)
    {
        $pricing = UnitPricing::findOrFail($id);

        $request->validate([
            'price_per_night' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'is_active' => 'nullable|boolean',
        ]);

        $pricing->update([
            'price_per_night' => $request->price_per_night,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->back()
            ->with('success', 'Pricing rule updated successfully');
    }

    /**
     * Remove pricing.
     */
    public function destroy($id)
    {
        $pricing = UnitPricing::findOrFail($id);
        $pricing->delete();

        return redirect()->back()
            ->with('success', 'Pricing rule deleted successfully');
    }
}










