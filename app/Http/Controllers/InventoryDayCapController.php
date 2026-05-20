<?php

namespace App\Http\Controllers;

use App\Models\HotelBooking;
use App\Models\HotelRoom;
use App\Models\Unit;
use App\Services\BookingInventoryService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class InventoryDayCapController extends Controller
{
    protected function isPropertySuperAdmin(): bool
    {
        $user = auth()->user();

        return $user && ($user->role == '1' || $user->role === 1);
    }

    protected function mayEditHotelRoom(HotelRoom $room): bool
    {
        $room->loadMissing('hotel');
        if ($this->isPropertySuperAdmin()) {
            return true;
        }
        $uid = auth()->id();

        return $room->hotel && (int) $room->hotel->added_by === (int) $uid;
    }

    protected function mayEditUnit(Unit $unit): bool
    {
        $unit->loadMissing('property');
        if ($this->isPropertySuperAdmin()) {
            return true;
        }
        $uid = auth()->id();

        return $unit->property && (int) $unit->property->owner_id === (int) $uid;
    }

    /**
     * JSON for booking calendar modal: guest rows + vacant math for one room type / unit on one calendar date.
     */
    public function show(Request $request)
    {
        $validated = $request->validate([
            'bookable_type' => 'required|in:App\Models\HotelRoom,App\Models\Unit',
            'bookable_id' => 'required|integer|min:1',
            'date' => 'required|date',
        ]);

        if ($validated['bookable_type'] === HotelRoom::class) {
            $room = HotelRoom::query()->with('hotel')->findOrFail($validated['bookable_id']);
            if (! $this->mayEditHotelRoom($room)) {
                abort(403);
            }

            return response()->json($this->buildHotelRoomDayPayload($room, $validated['date']));
        }

        $unit = Unit::query()->with(['property', 'unitType'])->findOrFail($validated['bookable_id']);
        if (! $this->mayEditUnit($unit)) {
            abort(403);
        }

        return response()->json($this->buildUnitDayPayload($unit, $validated['date']));
    }

    protected function buildHotelRoomDayPayload(HotelRoom $room, string $date): array
    {
        $dateStr = Carbon::parse($date)->toDateString();
        $capacity = BookingInventoryService::physicalCapacityHotelRoom($room);

        $bookings = HotelBooking::query()
            ->where('room_id', $room->id)
            ->whereNotNull('room_id')
            ->where('booking_status', '!=', 'cancelled')
            ->whereDate('check_in', '<=', $dateStr)
            ->whereDate('check_out', '>', $dateStr)
            ->orderBy('check_in')
            ->get([
                'guest_name', 'guest_email', 'guest_phone', 'check_in', 'check_out',
                'total_amount', 'reference_number', 'booking_status',
            ]);

        $caps = BookingInventoryService::loadCapsFor(HotelRoom::class, [(int) $room->id], $dateStr, $dateStr);
        $manualCap = $caps[(int) $room->id][$dateStr] ?? null;

        $computedVacant = BookingInventoryService::computedRemainingHotelRoom($room, $dateStr);
        $effectiveVacant = BookingInventoryService::effectiveRemainingHotelRoom($room, $dateStr, $manualCap);

        $label = ($room->room_type ?: 'Room #'.$room->id).' ('.$capacity.' rooms)';

        return [
            'bookable_type' => HotelRoom::class,
            'room_label' => $label,
            'date' => $dateStr,
            'date_formatted' => Carbon::parse($dateStr)->format('l, F j, Y'),
            'capacity' => $capacity,
            'Kigali Drive Rentals_bookings_overlapping' => $bookings->count(),
            'computed_vacant' => $computedVacant,
            'effective_vacant' => $effectiveVacant,
            'has_manual_cap' => $manualCap !== null,
            'bookings' => $bookings->map(function ($b) {
                $ci = Carbon::parse($b->check_in)->startOfDay();
                $co = Carbon::parse($b->check_out)->startOfDay();
                $nights = max(1, $ci->diffInDays($co));

                return [
                    'guest_name' => $b->guest_name,
                    'guest_email' => $b->guest_email,
                    'guest_phone' => $b->guest_phone,
                    'check_in' => $b->check_in,
                    'check_out' => $b->check_out,
                    'check_in_formatted' => $ci->format('M j, Y'),
                    'check_out_formatted' => $co->format('M j, Y'),
                    'nights' => $nights,
                    'total_amount' => round((float) $b->total_amount, 2),
                    'total_amount_formatted' => number_format((float) $b->total_amount, 2),
                    'reference_number' => $b->reference_number,
                    'booking_status' => $b->booking_status,
                ];
            })->values()->all(),
        ];
    }

    protected function buildUnitDayPayload(Unit $unit, string $date): array
    {
        $dateStr = Carbon::parse($date)->toDateString();
        $capacity = BookingInventoryService::physicalCapacityUnit($unit);

        $bookings = HotelBooking::query()
            ->where('unit_id', $unit->id)
            ->whereNotNull('unit_id')
            ->where('booking_status', '!=', 'cancelled')
            ->whereDate('check_in', '<=', $dateStr)
            ->whereDate('check_out', '>', $dateStr)
            ->orderBy('check_in')
            ->get([
                'guest_name', 'guest_email', 'guest_phone', 'check_in', 'check_out',
                'total_amount', 'reference_number', 'booking_status',
            ]);

        $caps = BookingInventoryService::loadCapsFor(Unit::class, [(int) $unit->id], $dateStr, $dateStr);
        $manualCap = $caps[(int) $unit->id][$dateStr] ?? null;

        $computedVacant = BookingInventoryService::computedRemainingUnit($unit, $dateStr);
        $effectiveVacant = BookingInventoryService::effectiveRemainingUnit($unit, $dateStr, $manualCap);

        $typeName = $unit->unitType?->name;
        $baseLabel = $unit->name ?: ($typeName ? $typeName.' #'.$unit->id : 'Unit #'.$unit->id);
        $label = $baseLabel.' ('.$capacity.' units)';

        return [
            'bookable_type' => Unit::class,
            'room_label' => $label,
            'date' => $dateStr,
            'date_formatted' => Carbon::parse($dateStr)->format('l, F j, Y'),
            'capacity' => $capacity,
            'Kigali Drive Rentals_bookings_overlapping' => $bookings->count(),
            'computed_vacant' => $computedVacant,
            'effective_vacant' => $effectiveVacant,
            'has_manual_cap' => $manualCap !== null,
            'bookings' => $bookings->map(function ($b) {
                $ci = Carbon::parse($b->check_in)->startOfDay();
                $co = Carbon::parse($b->check_out)->startOfDay();
                $nights = max(1, $ci->diffInDays($co));

                return [
                    'guest_name' => $b->guest_name,
                    'guest_email' => $b->guest_email,
                    'guest_phone' => $b->guest_phone,
                    'check_in' => $b->check_in,
                    'check_out' => $b->check_out,
                    'check_in_formatted' => $ci->format('M j, Y'),
                    'check_out_formatted' => $co->format('M j, Y'),
                    'nights' => $nights,
                    'total_amount' => round((float) $b->total_amount, 2),
                    'total_amount_formatted' => number_format((float) $b->total_amount, 2),
                    'reference_number' => $b->reference_number,
                    'booking_status' => $b->booking_status,
                ];
            })->values()->all(),
        ];
    }

    public function update(Request $request)
    {
        $base = $request->validate([
            'bookable_type' => 'required|in:App\Models\HotelRoom,App\Models\Unit',
            'bookable_id' => 'required|integer|min:1',
            'date' => 'required|date',
        ]);

        if ($request->boolean('automatic')) {
            $target = null;
        } else {
            $request->validate([
                'target_remaining' => 'required|integer|min:0|max:100000',
            ]);
            $target = (int) $request->input('target_remaining');
        }

        if ($base['bookable_type'] === HotelRoom::class) {
            $room = HotelRoom::query()->findOrFail($base['bookable_id']);
            if (! $this->mayEditHotelRoom($room)) {
                abort(403);
            }
        } else {
            $unit = Unit::query()->findOrFail($base['bookable_id']);
            if (! $this->mayEditUnit($unit)) {
                abort(403);
            }
        }

        BookingInventoryService::syncCapFromTargetRemaining(
            $base['bookable_type'],
            (int) $base['bookable_id'],
            $base['date'],
            $target
        );

        if ($request->wantsJson()) {
            return response()->json(['ok' => true]);
        }

        return redirect()->back()->with('success', 'Inventory updated.');
    }
}
