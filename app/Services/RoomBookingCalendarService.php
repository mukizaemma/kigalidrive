<?php

namespace App\Services;

use App\Models\Hotel;
use App\Models\HotelBooking;
use App\Models\HotelRoom;
use App\Models\Property;
use App\Models\Unit;
use Carbon\Carbon;

class RoomBookingCalendarService
{
    public const VIEW_UPCOMING = 'upcoming';

    public const VIEW_HISTORY = 'history';

    /**
     * Upcoming: this month through year-end for current year (today → end for current month); full year for future years.
     * History: full past years; current year = Jan → prior month + partial current month through yesterday.
     */
    public static function calendarWindows(int $year, string $view, ?Carbon $now = null): array
    {
        $now = $now ?? Carbon::now()->startOfDay();
        $view = $view === self::VIEW_HISTORY ? self::VIEW_HISTORY : self::VIEW_UPCOMING;

        if ($view === self::VIEW_UPCOMING) {
            if ($year < $now->year) {
                return [];
            }
            if ($year > $now->year) {
                $windows = [];
                for ($m = 1; $m <= 12; $m++) {
                    $dim = (int) Carbon::createFromDate($year, $m, 1)->daysInMonth;
                    $windows[] = ['month' => $m, 'day_from' => 1, 'day_to' => $dim];
                }

                return $windows;
            }

            $windows = [];
            for ($m = $now->month; $m <= 12; $m++) {
                $dim = (int) Carbon::createFromDate($year, $m, 1)->daysInMonth;
                $from = ($m === $now->month) ? $now->day : 1;
                $windows[] = ['month' => $m, 'day_from' => $from, 'day_to' => $dim];
            }

            return $windows;
        }

        // history
        if ($year > $now->year) {
            $windows = [];
            for ($m = 1; $m <= 12; $m++) {
                $dim = (int) Carbon::createFromDate($year, $m, 1)->daysInMonth;
                $windows[] = ['month' => $m, 'day_from' => 1, 'day_to' => $dim];
            }

            return $windows;
        }

        if ($year < $now->year) {
            $windows = [];
            for ($m = 1; $m <= 12; $m++) {
                $dim = (int) Carbon::createFromDate($year, $m, 1)->daysInMonth;
                $windows[] = ['month' => $m, 'day_from' => 1, 'day_to' => $dim];
            }

            return $windows;
        }

        // same calendar year as today
        $windows = [];
        for ($m = 1; $m < $now->month; $m++) {
            $dim = (int) Carbon::createFromDate($year, $m, 1)->daysInMonth;
            $windows[] = ['month' => $m, 'day_from' => 1, 'day_to' => $dim];
        }
        if ($now->day > 1) {
            $windows[] = ['month' => $now->month, 'day_from' => 1, 'day_to' => $now->day - 1];
        }

        return $windows;
    }

    /**
     * Build calendar grid for a hotel (room types).
     */
    public static function buildForHotel(Hotel $hotel, int $year, string $calendarView = self::VIEW_UPCOMING): array
    {
        $hotel->loadMissing('rooms');
        $rooms = $hotel->rooms->sortBy('room_type')->values();
        $now = Carbon::now()->startOfDay();

        $yearStart = Carbon::createFromDate($year, 1, 1)->startOfDay();
        $yearEnd = Carbon::createFromDate($year, 12, 31)->endOfDay();

        $windows = self::calendarWindows($year, $calendarView, $now);

        $roomIds = $rooms->pluck('id')->map(fn ($id) => (int) $id)->all();
        $caps = BookingInventoryService::loadCapsFor(
            HotelRoom::class,
            $roomIds,
            $yearStart->toDateString(),
            $yearEnd->toDateString()
        );

        $bookings = HotelBooking::query()
            ->where('hotel_id', $hotel->id)
            ->whereNotNull('room_id')
            ->where('booking_status', '!=', 'cancelled')
            ->whereDate('check_in', '<=', $yearEnd->toDateString())
            ->whereDate('check_out', '>', $yearStart->toDateString())
            ->get(['room_id', 'check_in', 'check_out']);

        $months = [];

        foreach ($windows as $w) {
            $month = $w['month'];
            $dayMin = $w['day_from'];
            $dayMax = $w['day_to'];
            $daysInMonth = (int) Carbon::createFromDate($year, $month, 1)->daysInMonth;
            $monthStart = Carbon::createFromDate($year, $month, 1)->startOfDay();
            $monthEnd = Carbon::createFromDate($year, $month, $daysInMonth)->endOfDay();

            $roomRows = [];
            $capacitySum = 0;

            foreach ($rooms as $room) {
                $capacity = BookingInventoryService::physicalCapacityHotelRoom($room);
                $capacitySum += $capacity;
                $bookedByDay = array_fill(1, $daysInMonth, 0);

                foreach ($bookings as $b) {
                    if ((int) $b->room_id !== (int) $room->id) {
                        continue;
                    }
                    $checkIn = Carbon::parse($b->check_in)->startOfDay();
                    $checkOut = Carbon::parse($b->check_out)->startOfDay();
                    $lastNight = $checkOut->copy()->subDay();

                    $cursor = $checkIn->copy()->max($monthStart);
                    while ($cursor->lte($lastNight) && $cursor->lte($monthEnd)) {
                        if ($cursor->month === $month && $cursor->year === $year) {
                            $d = $cursor->day;
                            if ($d >= 1 && $d <= $daysInMonth) {
                                $bookedByDay[$d]++;
                            }
                        }
                        $cursor->addDay();
                    }
                }

                $remainingByDay = [];
                for ($d = 1; $d <= $daysInMonth; $d++) {
                    $ymd = sprintf('%04d-%02d-%02d', $year, $month, $d);
                    $manual = $caps[(int) $room->id][$ymd] ?? null;
                    $remainingByDay[$d] = BookingInventoryService::effectiveRemainingHotelRoom($room, $ymd, $manual);
                }

                $roomRows[] = [
                    'id' => $room->id,
                    'label' => ($room->room_type ?: 'Room #'.$room->id).' ('.$capacity.' rooms)',
                    'total_rooms' => $capacity,
                    'inventory_kind' => 'hotel_room',
                    'inventory_id' => (int) $room->id,
                    'booked' => $bookedByDay,
                    'remaining' => $remainingByDay,
                ];
            }

            $visibleDays = range($dayMin, $dayMax);
            $occupancy = [];
            foreach ($visibleDays as $d) {
                $bookedUnits = 0;
                foreach ($roomRows as $row) {
                    $b = (int) ($row['booked'][$d] ?? 0);
                    $bookedUnits += min($b, (int) $row['total_rooms']);
                }
                $occupancy[$d] = $capacitySum > 0
                    ? round(($bookedUnits / $capacitySum) * 100, 2)
                    : 0.0;
            }

            $months[$month] = [
                'name' => Carbon::createFromDate($year, $month, 1)->format('F'),
                'days_in_month' => $daysInMonth,
                'visible_days' => $visibleDays,
                'rooms' => $roomRows,
                'occupancy' => $occupancy,
            ];
        }

        return [
            'year' => $year,
            'source' => 'hotel',
            'hotel_id' => $hotel->id,
            'property_id' => null,
            'hotel_name' => $hotel->name,
            'calendar_view' => $calendarView === self::VIEW_HISTORY ? self::VIEW_HISTORY : self::VIEW_UPCOMING,
            'months' => $months,
        ];
    }

    public static function buildForProperty(Property $property, int $year, string $calendarView = self::VIEW_UPCOMING): array
    {
        $property->loadMissing(['units.unitType']);
        $units = $property->units->sortBy(function ($unit) {
            $type = $unit->unitType?->name ?? '';

            return [$type, $unit->name ?? '', $unit->id];
        })->values();

        $now = Carbon::now()->startOfDay();

        $yearStart = Carbon::createFromDate($year, 1, 1)->startOfDay();
        $yearEnd = Carbon::createFromDate($year, 12, 31)->endOfDay();

        $windows = self::calendarWindows($year, $calendarView, $now);

        $unitIds = $units->pluck('id')->map(fn ($id) => (int) $id)->all();
        $caps = BookingInventoryService::loadCapsFor(
            Unit::class,
            $unitIds,
            $yearStart->toDateString(),
            $yearEnd->toDateString()
        );

        $bookings = HotelBooking::query()
            ->where('property_id', $property->id)
            ->whereNotNull('unit_id')
            ->where('booking_status', '!=', 'cancelled')
            ->whereDate('check_in', '<=', $yearEnd->toDateString())
            ->whereDate('check_out', '>', $yearStart->toDateString())
            ->get(['unit_id', 'check_in', 'check_out']);

        $months = [];

        foreach ($windows as $w) {
            $month = $w['month'];
            $dayMin = $w['day_from'];
            $dayMax = $w['day_to'];
            $daysInMonth = (int) Carbon::createFromDate($year, $month, 1)->daysInMonth;
            $monthStart = Carbon::createFromDate($year, $month, 1)->startOfDay();
            $monthEnd = Carbon::createFromDate($year, $month, $daysInMonth)->endOfDay();

            $roomRows = [];
            $capacitySum = 0;

            foreach ($units as $unit) {
                $capacity = BookingInventoryService::physicalCapacityUnit($unit);
                $capacitySum += $capacity;
                $bookedByDay = array_fill(1, $daysInMonth, 0);

                foreach ($bookings as $b) {
                    if ((int) $b->unit_id !== (int) $unit->id) {
                        continue;
                    }
                    $checkIn = Carbon::parse($b->check_in)->startOfDay();
                    $checkOut = Carbon::parse($b->check_out)->startOfDay();
                    $lastNight = $checkOut->copy()->subDay();

                    $cursor = $checkIn->copy()->max($monthStart);
                    while ($cursor->lte($lastNight) && $cursor->lte($monthEnd)) {
                        if ($cursor->month === $month && $cursor->year === $year) {
                            $d = $cursor->day;
                            if ($d >= 1 && $d <= $daysInMonth) {
                                $bookedByDay[$d]++;
                            }
                        }
                        $cursor->addDay();
                    }
                }

                $typeName = $unit->unitType?->name;
                $baseLabel = $unit->name ?: ($typeName ? $typeName.' #'.$unit->id : 'Unit #'.$unit->id);

                $remainingByDay = [];
                for ($d = 1; $d <= $daysInMonth; $d++) {
                    $ymd = sprintf('%04d-%02d-%02d', $year, $month, $d);
                    $manual = $caps[(int) $unit->id][$ymd] ?? null;
                    $remainingByDay[$d] = BookingInventoryService::effectiveRemainingUnit($unit, $ymd, $manual);
                }

                $roomRows[] = [
                    'id' => $unit->id,
                    'label' => $baseLabel.' ('.$capacity.' units)',
                    'total_rooms' => $capacity,
                    'inventory_kind' => 'unit',
                    'inventory_id' => (int) $unit->id,
                    'booked' => $bookedByDay,
                    'remaining' => $remainingByDay,
                ];
            }

            $visibleDays = range($dayMin, $dayMax);
            $occupancy = [];
            foreach ($visibleDays as $d) {
                $bookedUnits = 0;
                foreach ($roomRows as $row) {
                    $b = (int) ($row['booked'][$d] ?? 0);
                    $bookedUnits += min($b, (int) $row['total_rooms']);
                }
                $occupancy[$d] = $capacitySum > 0
                    ? round(($bookedUnits / $capacitySum) * 100, 2)
                    : 0.0;
            }

            $months[$month] = [
                'name' => Carbon::createFromDate($year, $month, 1)->format('F'),
                'days_in_month' => $daysInMonth,
                'visible_days' => $visibleDays,
                'rooms' => $roomRows,
                'occupancy' => $occupancy,
            ];
        }

        return [
            'year' => $year,
            'source' => 'property',
            'hotel_id' => null,
            'property_id' => $property->id,
            'hotel_name' => $property->name,
            'calendar_view' => $calendarView === self::VIEW_HISTORY ? self::VIEW_HISTORY : self::VIEW_UPCOMING,
            'months' => $months,
        ];
    }

    /**
     * @return array<int, array> hotel_id => buildForHotel payload
     */
    public static function buildForHotels(iterable $hotels, int $year): array
    {
        $out = [];
        foreach ($hotels as $hotel) {
            if ($hotel instanceof Hotel) {
                $out[$hotel->id] = self::buildForHotel($hotel, $year);
            }
        }

        return $out;
    }
}
