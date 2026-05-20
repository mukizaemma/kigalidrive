<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HotelBooking;
use App\Models\Property;
use App\Models\StaynetsInvoice;
use App\Models\StaynetsInvoiceBooking;
use Illuminate\Http\Request;

class InvoicesController extends Controller
{
    public function index()
    {
        $invoices = StaynetsInvoice::with('property', 'creator')
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('admin.invoices.index', ['invoices' => $invoices]);
    }

    public function create(Request $request)
    {
        $start = $request->input('period_start', now()->startOfMonth()->format('Y-m-d'));
        $end = $request->input('period_end', now()->format('Y-m-d'));
        $propertyId = $request->input('property_id');

        $properties = Property::active()->orderBy('name')->get();

        $bookingsQuery = HotelBooking::query()
            ->with(['property', 'unit'])
            ->whereNotNull('property_id')
            ->where('booking_status', 'confirmed')
            ->whereBetween('check_out', [$start, $end])
            ->whereNotIn('id', function ($q) {
                $q->select('hotel_booking_id')->from('staynets_invoice_bookings');
            });

        if ($propertyId) {
            $bookingsQuery->where('property_id', $propertyId);
        }

        $bookings = $bookingsQuery->orderBy('check_out')->get();

        $byProperty = $bookings->groupBy('property_id');
        $summary = [];
        foreach ($byProperty as $pid => $items) {
            $summary[$pid] = [
                'property' => $items->first()->property,
                'bookings' => $items,
                'total_amount' => $items->sum('total_amount'),
                'commission' => $items->sum('commission_amount'),
            ];
        }

        return view('admin.invoices.create', [
            'period_start' => $start,
            'period_end' => $end,
            'property_id' => $propertyId,
            'properties' => $properties,
            'summary' => $summary,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'property_id' => 'required|exists:properties,id',
            'period_start' => 'required|date',
            'period_end' => 'required|date|after_or_equal:period_start',
        ]);

        $bookings = HotelBooking::query()
            ->where('property_id', $request->property_id)
            ->where('booking_status', 'confirmed')
            ->whereBetween('check_out', [$request->period_start, $request->period_end])
            ->whereNotIn('id', function ($q) {
                $q->select('hotel_booking_id')->from('staynets_invoice_bookings');
            })
            ->get();

        if ($bookings->isEmpty()) {
            return redirect()->back()
                ->with('error', 'No uninvoiced bookings found for this property and period.')
                ->withInput();
        }

        $totalAmount = $bookings->sum('total_amount');
        $commissionAmount = $bookings->sum('commission_amount');

        $invoiceNumber = 'INV-' . strtoupper(uniqid());

        $invoice = StaynetsInvoice::create([
            'invoice_number' => $invoiceNumber,
            'property_id' => $request->property_id,
            'period_start' => $request->period_start,
            'period_end' => $request->period_end,
            'total_booking_amount' => $totalAmount,
            'commission_amount' => $commissionAmount,
            'status' => 'draft',
            'created_by' => auth()->id(),
            'notes' => $request->notes,
        ]);

        foreach ($bookings as $b) {
            StaynetsInvoiceBooking::create([
                'staynets_invoice_id' => $invoice->id,
                'hotel_booking_id' => $b->id,
                'booking_total' => $b->total_amount,
                'commission' => $b->commission_amount ?? 0,
            ]);
        }

        return redirect()->route('admin.invoices.show', $invoice->id)
            ->with('success', 'Invoice created as draft.');
    }

    public function show($id)
    {
        $invoice = StaynetsInvoice::with(['property', 'creator', 'invoiceBookings.hotelBooking'])->findOrFail($id);

        return view('admin.invoices.show', ['invoice' => $invoice]);
    }

    public function send($id)
    {
        $invoice = StaynetsInvoice::with('property')->findOrFail($id);
        if ($invoice->status !== 'draft') {
            return redirect()->back()->with('error', 'Only draft invoices can be sent.');
        }

        $invoice->update([
            'status' => 'sent',
            'sent_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Invoice marked as sent.');
    }
}
