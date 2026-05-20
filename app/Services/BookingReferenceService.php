<?php

namespace App\Services;

use App\Models\CarRental;
use App\Models\HotelBooking;

class BookingReferenceService
{
    public function generate(): int
    {
        do {
            $number = random_int(10, 1000);
        } while ($this->exists($number));

        return $number;
    }

    public function exists(int $number): bool
    {
        return CarRental::where('booking_number', $number)->exists()
            || HotelBooking::where('booking_number', $number)->exists();
    }

    public function isValid(int $number): bool
    {
        return $this->exists($number);
    }

    public function findReservation(int $number): ?array
    {
        $car = CarRental::where('booking_number', $number)->first();
        if ($car) {
            return ['type' => 'car', 'model' => $car];
        }

        $apartment = HotelBooking::where('booking_number', $number)->first();
        if ($apartment) {
            return ['type' => 'apartment', 'model' => $apartment];
        }

        return null;
    }
}
