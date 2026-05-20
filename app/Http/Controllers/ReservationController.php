<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\CarRental;
use App\Models\HotelBooking;
use App\Models\Property;
use App\Models\Setting;
use App\Models\Unit;
use App\Services\BookingReferenceService;
use App\Services\ReservationNotificationService;
use App\Services\SubmissionChannelService;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function __construct(
        protected BookingReferenceService $bookingRef,
        protected ReservationNotificationService $notifier,
        protected SubmissionChannelService $channels
    ) {}

    public function storeCar(Request $request)
    {
        $validated = $request->validate([
            'car_id' => 'required|exists:cars,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:50',
            'booking_type' => 'required|in:rent,buy,view_car',
            'full_address' => 'nullable|string|max:500',
            'time_needed' => 'required|string|max:255',
            'rental_duration' => 'nullable|string|max:100',
            'with_driver' => 'nullable|boolean',
            'pickup_date' => 'nullable|date',
            'dropoff_date' => 'nullable|date|after_or_equal:pickup_date',
            'additional_request' => 'nullable|string|max:2000',
            'channel' => 'required|in:email,whatsapp,form',
        ]);

        $setting = Setting::firstOrFail();
        $this->channels->assertAnyChannelActive($setting, 'booking');
        $this->channels->assertChannelActive($setting, $validated['channel'], 'booking');

        $car = Car::findOrFail($validated['car_id']);
        $bookingNumber = $this->bookingRef->generate();

        $rental = CarRental::create([
            'car_id' => $car->id,
            'user_id' => auth()->id(),
            'booking_number' => $bookingNumber,
            'booking_type' => $validated['booking_type'],
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'full_address' => $validated['full_address'] ?? null,
            'time_needed' => $validated['time_needed'],
            'rental_duration' => $validated['rental_duration'] ?? null,
            'with_driver' => $request->boolean('with_driver'),
            'pickup_date' => $validated['pickup_date'] ?? null,
            'dropoff_date' => $validated['dropoff_date'] ?? null,
            'additional_request' => $validated['additional_request'] ?? null,
            'message' => $validated['additional_request'] ?? null,
            'submission_channel' => $validated['channel'],
            'rental_status' => 'pending',
            'payment_status' => 'pending',
        ]);

        $payload = $this->payload($rental->only([
            'booking_number', 'name', 'phone', 'email', 'booking_type', 'full_address', 'time_needed', 'additional_request',
        ]), 'Car: ' . $car->name);

        return $this->finish($setting, $validated['channel'], $payload, $car->slug, 'car');
    }

    public function storeApartment(Request $request)
    {
        $validated = $request->validate([
            'property_id' => 'required|exists:properties,id',
            'unit_id' => 'nullable|exists:units,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:50',
            'booking_type' => 'required|in:rent_day,rent_night,buy,viewing',
            'full_address' => 'nullable|string|max:500',
            'time_needed' => 'required|string|max:255',
            'check_in' => 'nullable|date',
            'check_out' => 'nullable|date|after_or_equal:check_in',
            'additional_request' => 'nullable|string|max:2000',
            'channel' => 'required|in:email,whatsapp,form',
        ]);

        $setting = Setting::firstOrFail();
        $this->channels->assertAnyChannelActive($setting, 'booking');
        $this->channels->assertChannelActive($setting, $validated['channel'], 'booking');

        $property = Property::findOrFail($validated['property_id']);
        $unit = $validated['unit_id'] ? Unit::find($validated['unit_id']) : null;
        $bookingNumber = $this->bookingRef->generate();

        $booking = HotelBooking::create([
            'property_id' => $property->id,
            'unit_id' => $unit?->id,
            'user_id' => auth()->id(),
            'booking_number' => $bookingNumber,
            'reference_number' => 'KDR-' . $bookingNumber . '-' . time(),
            'guest_name' => $validated['name'],
            'guest_email' => $validated['email'],
            'guest_phone' => $validated['phone'],
            'full_address' => $validated['full_address'] ?? null,
            'time_needed' => $validated['time_needed'],
            'additional_request' => $validated['additional_request'] ?? null,
            'submission_channel' => $validated['channel'],
            'check_in' => $validated['check_in'] ?? now()->toDateString(),
            'check_out' => $validated['check_out'] ?? ($validated['check_in'] ?? now()->toDateString()),
            'booking_status' => 'pending',
            'special_requests' => $validated['additional_request'] ?? null,
        ]);

        $label = 'Apartment: ' . $property->name . ($unit ? ' — ' . $unit->name : '');
        $payload = $this->payload([
            'booking_number' => $bookingNumber,
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'email' => $validated['email'],
            'booking_type' => $validated['booking_type'],
            'full_address' => $validated['full_address'] ?? null,
            'time_needed' => $validated['time_needed'],
            'additional_request' => $validated['additional_request'] ?? null,
        ], $label);

        $slug = $property->slug;

        return $this->finish($setting, $validated['channel'], $payload, $slug, 'apartment');
    }

    protected function payload(array $data, string $productLabel): array
    {
        $data['product_label'] = $productLabel;

        return $data;
    }

    protected function finish(Setting $setting, string $channel, array $payload, string $slug, string $type)
    {
        $message = $this->notifier->buildMessage($payload);

        if ($channel === 'email' || $channel === 'form') {
            try {
                $this->notifier->notifyAdmin($payload, $setting);
            } catch (\Throwable $e) {
                // guest booking still succeeds
            }
        }

        if ($channel === 'whatsapp') {
            $url = $this->channels->whatsappUrl($setting, $message);
            session()->flash('booking_number', $payload['booking_number']);
            session()->flash('reservation_message', $message);

            return redirect()->away($url);
        }

        $route = $type === 'car' ? route('carDetails', $slug) : route('hotel', $slug);

        return redirect($route)->with('success', 'Reservation submitted! Your booking number is #' . $payload['booking_number'] . '. Keep it for your review.')
            ->with('booking_number', $payload['booking_number']);
    }
}
