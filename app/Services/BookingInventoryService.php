<?php

namespace App\Services;

use App\Models\HotelBooking;
use App\Models\HotelRoom;
use App\Models\InventoryDayCap;
use App\Models\Unit;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class BookingInventoryService
{
    public static function physicalCapacityHotelRoom(HotelRoom $room): int
    {
        return max(1, (int) ($room->total_rooms ?? 1));
    }

    public static function physicalCapacityUnit(Unit $unit): int
    {
        return max(1, (int) ($unit->total_units ?? 1));
    }

    public static function bookedCountHotelRoomOnDate(int $hotelRoomId, string $date): int
    {
        $d = Carbon::parse($date)->toDateString();

        return HotelBooking::query()
            ->where('room_id', $hotelRoomId)
            ->whereNotNull('room_id')
            ->where('booking_status', '!=', 'cancelled')
            ->whereDate('check_in', '<=', $d)
            ->whereDate('check_out', '>', $d)
            ->count();
    }

    public static function bookedCountUnitOnDate(int $unitId, string $date): int
    {
        $d = Carbon::parse($date)->toDateString();

        return HotelBooking::query()
            ->where('unit_id', $unitId)
            ->whereNotNull('unit_id')
            ->where('booking_status', '!=', 'cancelled')
            ->whereDate('check_in', '<=', $d)
            ->whereDate('check_out', '>', $d)
            ->count();
    }

    /**
     * Raw remaining before any manual cap: capacity - booked.
     */
    public static function computedRemainingHotelRoom(HotelRoom $room, string $date): int
    {
        $cap = self::physicalCapacityHotelRoom($room);
        $booked = self::bookedCountHotelRoomOnDate((int) $room->id, $date);

        return max(0, $cap - $booked);
    }

    public static function computedRemainingUnit(Unit $unit, string $date): int
    {
        $cap = self::physicalCapacityUnit($unit);
        $booked = self::bookedCountUnitOnDate((int) $unit->id, $date);

        return max(0, $cap - $booked);
    }

    /**
     * When a day cap exists: min(computed, cap). Otherwise computed.
     */
    public static function effectiveRemainingHotelRoom(HotelRoom $room, string $date, ?int $capMaxRemaining): int
    {
        $computed = self::computedRemainingHotelRoom($room, $date);
        if ($capMaxRemaining === null) {
            return $computed;
        }

        return min($computed, max(0, $capMaxRemaining));
    }

    public static function effectiveRemainingUnit(Unit $unit, string $date, ?int $capMaxRemaining): int
    {
        $computed = self::computedRemainingUnit($unit, $date);
        if ($capMaxRemaining === null) {
            return $computed;
        }

        return min($computed, max(0, $capMaxRemaining));
    }

    /**
     * @return int Minimum nights' remaining inventory across [check_in, check_out) — each night must allow one more booking.
     */
    public static function minEffectiveRemainingHotelRoomStay(
        HotelRoom $room,
        Carbon $checkIn,
        Carbon $checkOut,
    ): int {
        $map = self::loadCapsFor(
            HotelRoom::class,
            [(int) $room->id],
            $checkIn->toDateString(),
            $checkOut->copy()->subDay()->toDateString()
        );

        $min = PHP_INT_MAX;
        foreach (CarbonPeriod::create($checkIn->toDateString(), $checkOut->copy()->subDay()->toDateString()) as $day) {
            $d = $day->toDateString();
            $cap = $map[(int) $room->id][$d] ?? null;
            $rem = self::effectiveRemainingHotelRoom($room, $d, $cap);
            $min = min($min, $rem);
        }

        return $min === PHP_INT_MAX ? 0 : $min;
    }

    public static function minEffectiveRemainingUnitStay(
        Unit $unit,
        Carbon $checkIn,
        Carbon $checkOut,
    ): int {
        $map = self::loadCapsFor(
            Unit::class,
            [(int) $unit->id],
            $checkIn->toDateString(),
            $checkOut->copy()->subDay()->toDateString()
        );

        $min = PHP_INT_MAX;
        foreach (CarbonPeriod::create($checkIn->toDateString(), $checkOut->copy()->subDay()->toDateString()) as $day) {
            $d = $day->toDateString();
            $cap = $map[(int) $unit->id][$d] ?? null;
            $rem = self::effectiveRemainingUnit($unit, $d, $cap);
            $min = min($min, $rem);
        }

        return $min === PHP_INT_MAX ? 0 : $min;
    }

    /**
     * @param  class-string  $bookableClass  HotelRoom::class or Unit::class
     * @param  array<int>  $ids
     * @return array<int, array<string, int|null>> id => [ 'Y-m-d' => cap or null ]
     */
    public static function loadCapsFor(string $bookableClass, array $ids, string $dateFrom, string $dateTo): array
    {
        if ($ids === []) {
            return [];
        }

        $rows = InventoryDayCap::query()
            ->where('bookable_type', $bookableClass)
            ->whereIn('bookable_id', $ids)
            ->whereDate('date', '>=', $dateFrom)
            ->whereDate('date', '<=', $dateTo)
            ->get(['bookable_id', 'date', 'max_remaining']);

        $out = [];
        foreach ($ids as $id) {
            $out[(int) $id] = [];
        }

        foreach ($rows as $row) {
            $bid = (int) $row->bookable_id;
            $d = $row->date instanceof Carbon ? $row->date->toDateString() : Carbon::parse($row->date)->toDateString();
            $out[$bid][$d] = (int) $row->max_remaining;
        }

        return $out;
    }

    /**
     * Persist manual cap so that effective remaining matches target when target < computed; remove row when automatic.
     *
     * @param  int|null  $targetRemaining  null = remove cap (fully automatic from bookings)
     */
    public static function syncCapFromTargetRemaining(
        string $bookableClass,
        int $bookableId,
        string $date,
        ?int $targetRemaining,
    ): void {
        if (! in_array($bookableClass, [HotelRoom::class, Unit::class], true)) {
            abort(400);
        }

        $bookable = $bookableClass::findOrFail($bookableId);

        $capacity = $bookable instanceof HotelRoom
            ? self::physicalCapacityHotelRoom($bookable)
            : self::physicalCapacityUnit($bookable);

        $booked = $bookable instanceof HotelRoom
            ? self::bookedCountHotelRoomOnDate($bookableId, $date)
            : self::bookedCountUnitOnDate($bookableId, $date);

        $computed = max(0, $capacity - $booked);

        if ($targetRemaining === null) {
            InventoryDayCap::query()
                ->where('bookable_type', $bookableClass)
                ->where('bookable_id', $bookableId)
                ->whereDate('date', $date)
                ->delete();

            return;
        }

        $targetRemaining = max(0, min((int) $targetRemaining, $capacity));

        if ($targetRemaining >= $computed) {
            InventoryDayCap::query()
                ->where('bookable_type', $bookableClass)
                ->where('bookable_id', $bookableId)
                ->whereDate('date', $date)
                ->delete();

            return;
        }

        InventoryDayCap::updateOrCreate(
            [
                'bookable_type' => $bookableClass,
                'bookable_id' => $bookableId,
                'date' => Carbon::parse($date)->toDateString(),
            ],
            ['max_remaining' => $targetRemaining]
        );
    }
}
