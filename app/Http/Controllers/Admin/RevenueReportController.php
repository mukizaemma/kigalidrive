<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HotelBooking;
use App\Models\Property;
use Illuminate\Http\Request;

class RevenueReportController extends Controller
{
    public function index(Request $request)
    {
        $start = $request->input('start', now()->startOfMonth()->format('Y-m-d'));
        $end = $request->input('end', now()->format('Y-m-d'));
        $propertyId = $request->input('property_id');

        $query = HotelBooking::query()
            ->whereNotNull('property_id')
            ->where('booking_status', '!=', 'cancelled')
            ->whereBetween('check_out', [$start, $end]);

        if ($propertyId) {
            $query->where('property_id', $propertyId);
        }

        $totals = (clone $query)->selectRaw('
            COALESCE(SUM(total_amount), 0) as total_revenue,
            COALESCE(SUM(commission_amount), 0) as total_commission
        ')->first();

        $byProperty = (clone $query)
            ->select('property_id')
            ->selectRaw('COALESCE(SUM(total_amount), 0) as revenue')
            ->selectRaw('COALESCE(SUM(commission_amount), 0) as commission')
            ->groupBy('property_id')
            ->get();

        $propertyIds = $byProperty->pluck('property_id')->filter()->unique()->values();
        $properties = Property::whereIn('id', $propertyIds)->get()->keyBy('id');

        $propertiesList = Property::active()->orderBy('name')->get();

        return view('admin.reports.revenue', [
            'start' => $start,
            'end' => $end,
            'propertyId' => $propertyId,
            'totals' => $totals,
            'byProperty' => $byProperty,
            'properties' => $properties,
            'propertiesList' => $propertiesList,
        ]);
    }
}
