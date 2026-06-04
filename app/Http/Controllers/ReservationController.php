<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\CarRental;
use App\Models\HotelBooking;
use App\Models\Property;
use App\Models\Setting;
use App\Models\Unit;
use App\Services\BookingReferenceService;
use App\Services\CarRentalPackageService;
use App\Services\ReservationNotificationService;
use App\Services\SubmissionChannelService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ReservationController extends Controller
{
    public function __construct(
        protected BookingReferenceService $bookingRef,
        protected ReservationNotificationService $notifier,
        protected SubmissionChannelService $channels,
        protected CarRentalPackageService $packages
    ) {}

    public function storeCar(Request $request)
    {
        $car = Car::findOrFail($request->input('car_id'));
        $packageKeys = $this->packages->allowedKeys($car);

        $validated = $request->validate([
            'car_id' => 'required|exists:cars,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:50',
            'booking_type' => 'required|in:rent,view_car',
            'rental_package' => ['nullable', 'string', 'max:64', Rule::in($packageKeys), 'required_if:booking_type,rent'],
            'full_address' => 'nullable|string|max:500',
            'time_needed' => 'nullable|string|max:255',
            'rental_duration' => 'nullable|string|max:100',
            'with_driver' => 'nullable|in:0,1',
            'pickup_date' => 'nullable|date|required_if:booking_type,rent',
            'pickup_time' => 'nullable|date_format:H:i',
            'dropoff_date' => 'nullable|date|after_or_equal:pickup_date|required_if:booking_type,rent',
            'dropoff_time' => 'nullable|date_format:H:i',
            'preferred_date' => 'nullable|date|required_if:booking_type,view_car',
            'preferred_time' => 'nullable|string|max:20',
            'pickup_location' => 'nullable|string|max:255',
            'dropoff_location' => 'nullable|string|max:255',
            'additional_request' => 'nullable|string|max:2000',
            'channel' => 'required|in:email,whatsapp',
        ]);

        $setting = Setting::firstOrFail();
        $channelContext = 'car_booking';
        $this->channels->assertAnyChannelActive($setting, $channelContext);
        $this->channels->assertChannelActive($setting, $validated['channel'], $channelContext);

        if ($validated['booking_type'] === 'rent') {
            if ($packageKeys === []) {
                throw ValidationException::withMessages([
                    'rental_package' => 'This vehicle has no rental packages configured. Please contact us directly.',
                ]);
            }

            $package = $this->packages->assertValidPackage($car, $validated['rental_package']);
            $validated['rental_duration'] = $package['rental_duration'];
            $validated['with_driver'] = $package['with_driver'] ? '1' : '0';
        } elseif ($validated['booking_type'] === 'buy') {
            $validated['with_driver'] = $validated['with_driver'] ?? '0';
        } else {
            $validated['with_driver'] = $validated['with_driver'] ?? '0';
        }

        $validated['time_needed'] = $this->resolveCarTimeNeeded($validated);
        if (blank($validated['time_needed'])) {
            throw ValidationException::withMessages([
                'time_needed' => match ($validated['booking_type']) {
                    'buy' => 'Please tell us when you need the vehicle.',
                    'view_car' => 'Please select a preferred viewing date.',
                    default => 'Please select pickup and return dates.',
                },
            ]);
        }

        $bookingNumber = $this->bookingRef->generate();
        $packageLabel = $validated['booking_type'] === 'rent'
            ? $this->packages->labelFor($car, $validated['rental_package'] ?? null)
            : null;

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
            'rental_package' => $validated['rental_package'] ?? null,
            'with_driver' => isset($validated['with_driver']) ? (int) $validated['with_driver'] === 1 : null,
            'pickup_location' => $validated['pickup_location'] ?? null,
            'dropoff_location' => $validated['dropoff_location'] ?? null,
            'pickup_date' => $validated['pickup_date'] ?? null,
            'pickup_time' => $validated['pickup_time'] ?? null,
            'dropoff_date' => $validated['dropoff_date'] ?? null,
            'dropoff_time' => $validated['dropoff_time'] ?? null,
            'preferred_date' => $validated['preferred_date'] ?? null,
            'preferred_time' => $validated['preferred_time'] ?? null,
            'additional_request' => $validated['additional_request'] ?? null,
            'message' => $validated['additional_request'] ?? null,
            'submission_channel' => $validated['channel'],
            'rental_status' => 'pending',
            'payment_status' => 'pending',
        ]);

        $payload = $this->payload([
            'booking_number' => $rental->booking_number,
            'name' => $rental->name,
            'phone' => $rental->phone,
            'email' => $rental->email,
            'booking_type' => $rental->booking_type,
            'full_address' => $rental->full_address,
            'time_needed' => $rental->time_needed,
            'rental_package_label' => $packageLabel,
            'rental_duration' => $rental->rental_duration,
            'with_driver' => $rental->with_driver,
            'pickup_date' => $rental->pickup_date ? Carbon::parse($rental->pickup_date)->format('Y-m-d') : null,
            'pickup_time' => $rental->pickup_time ? Carbon::parse($rental->pickup_time)->format('g:i A') : null,
            'dropoff_date' => $rental->dropoff_date ? Carbon::parse($rental->dropoff_date)->format('Y-m-d') : null,
            'dropoff_time' => $rental->dropoff_time ? Carbon::parse($rental->dropoff_time)->format('g:i A') : null,
            'pickup_location' => $rental->pickup_location,
            'dropoff_location' => $rental->dropoff_location,
            'preferred_date' => $validated['preferred_date'] ?? null,
            'preferred_time' => $validated['preferred_time'] ?? null,
            'additional_request' => $rental->additional_request,
        ], 'Car: ' . $car->name);

        return $this->finish($request, $setting, $validated['channel'], $payload, $car->slug, 'car');
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

        return $this->finish($request, $setting, $validated['channel'], $payload, $slug, 'apartment');
    }

    protected function payload(array $data, string $productLabel): array
    {
        $data['product_label'] = $productLabel;

        return $data;
    }

    protected function resolveCarTimeNeeded(array $validated): string
    {
        if ($validated['booking_type'] === 'rent' && ! empty($validated['pickup_date']) && ! empty($validated['dropoff_date'])) {
            return $this->formatRentalPeriod(
                $validated['pickup_date'],
                $validated['dropoff_date'],
                $validated['pickup_time'] ?? null,
                $validated['dropoff_time'] ?? null
            );
        }

        if ($validated['booking_type'] === 'view_car') {
            $parts = [];
            if (! empty($validated['preferred_date'])) {
                $parts[] = 'Viewing on ' . Carbon::parse($validated['preferred_date'])->format('j M Y');
            }
            if (! empty($validated['preferred_time'])) {
                $parts[] = 'at ' . $validated['preferred_time'];
            }

            return trim(implode(' ', $parts));
        }

        return trim((string) ($validated['time_needed'] ?? ''));
    }

    protected function formatRentalPeriod(
        string $pickupDate,
        string $dropoffDate,
        ?string $pickupTime = null,
        ?string $dropoffTime = null
    ): string {
        $pickup = Carbon::parse($pickupDate)->startOfDay();
        $dropoff = Carbon::parse($dropoffDate)->startOfDay();
        $days = max(1, $pickup->diffInDays($dropoff));
        $dayLabel = $days === 1 ? '1 day' : $days . ' days';

        $pickupLabel = $pickup->format('j M Y') . ($pickupTime ? ' ' . Carbon::parse($pickupTime)->format('g:i A') : '');
        $dropoffLabel = $dropoff->format('j M Y') . ($dropoffTime ? ' ' . Carbon::parse($dropoffTime)->format('g:i A') : '');

        return $dayLabel . ' (' . $pickupLabel . ' – ' . $dropoffLabel . ')';
    }

    protected function finish(Request $request, Setting $setting, string $channel, array $payload, string $slug, string $type)
    {
        $message = $this->notifier->buildMessage($payload);
        $redirectUrl = $type === 'car' ? route('carDetails', $slug) : route('hotel', $slug);
        $successMessage = 'Reservation submitted! Your booking number is #' . $payload['booking_number'] . '. Keep it for your review.';
        $flash = [
            'booking_number' => $payload['booking_number'],
            'reservation_message' => $message,
        ];

        if ($channel === 'whatsapp') {
            $externalUrl = $this->channels->whatsappUrl($setting, $message);
            if ($externalUrl) {
                return $this->channels->submissionResponse($request, $redirectUrl, $successMessage, $externalUrl, $flash);
            }

            throw ValidationException::withMessages([
                'channel' => 'WhatsApp is not available right now. Please choose Email or contact us by phone.',
            ]);
        }

        if ($channel === 'email') {
            $adminEmail = $this->channels->adminEmail($setting, 'booking');
            if ($adminEmail) {
                $subject = 'Reservation #' . $payload['booking_number'] . ' — Kigali Drive Rentals';
                $externalUrl = 'mailto:' . $adminEmail
                    . '?subject=' . rawurlencode($subject)
                    . '&body=' . rawurlencode($message);

                return $this->channels->submissionResponse($request, $redirectUrl, $successMessage, $externalUrl, $flash);
            }

            throw ValidationException::withMessages([
                'channel' => 'Email is not available right now. Please choose WhatsApp or contact us by phone.',
            ]);
        }

        if ($channel === 'form') {
            try {
                $this->notifier->notifyAdmin($payload, $setting);
            } catch (\Throwable $e) {
                // guest booking still succeeds
            }
        }

        return $this->channels->submissionResponse($request, $redirectUrl, $successMessage, null, $flash);
    }
}
