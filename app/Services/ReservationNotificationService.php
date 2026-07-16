<?php

namespace App\Services;

use App\Mail\AdminNotification;
use App\Mail\CarBookingClientMail;
use App\Models\CarRental;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class ReservationNotificationService
{
    public function buildMessage(array $data): string
    {
        $lines = [
            'Kigali Drive Rentals — New Reservation',
            'Booking #: ' . $data['booking_number'],
            '',
            'Full name: ' . $data['name'],
            'Phone: ' . $data['phone'],
            'Email: ' . $data['email'],
            'Booking type: ' . $data['booking_type'],
            'Address: ' . ($data['full_address'] ?? '—'),
            'Time needed: ' . ($data['time_needed'] ?? '—'),
        ];

        if (!empty($data['product_label'])) {
            $lines[] = 'Product: ' . $data['product_label'];
        }
        if (! empty($data['rental_package_label'])) {
            $lines[] = 'Package: ' . $data['rental_package_label'];
        }
        if (! empty($data['rental_duration'])) {
            $lines[] = 'Rental duration: ' . $data['rental_duration'];
        }
        if (array_key_exists('with_driver', $data) && $data['with_driver'] !== null && $data['with_driver'] !== '') {
            $withDriver = filter_var($data['with_driver'], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
            if ($withDriver !== null) {
                $lines[] = 'Driver: With driver';
            }
        }
        if (! empty($data['pickup_date'])) {
            $pickup = $data['pickup_date'];
            if (! empty($data['pickup_time'])) {
                $pickup .= ' at ' . $data['pickup_time'];
            }
            $lines[] = 'Pickup: ' . $pickup;
        }
        if (! empty($data['dropoff_date'])) {
            $dropoff = $data['dropoff_date'];
            if (! empty($data['dropoff_time'])) {
                $dropoff .= ' at ' . $data['dropoff_time'];
            }
            $lines[] = 'Return: ' . $dropoff;
        }
        if (! empty($data['pickup_location'])) {
            $lines[] = 'Pickup location: ' . $data['pickup_location'];
        }
        if (! empty($data['dropoff_location'])) {
            $lines[] = 'Return location: ' . $data['dropoff_location'];
        }
        if (! empty($data['preferred_date'])) {
            $lines[] = 'Preferred viewing date: ' . $data['preferred_date'];
        }
        if (! empty($data['preferred_time'])) {
            $lines[] = 'Preferred viewing time: ' . $data['preferred_time'];
        }
        if (! empty($data['additional_request'])) {
            $lines[] = 'Additional request: ' . $data['additional_request'];
        }
        if (! empty($data['admin_message'])) {
            $lines[] = '';
            $lines[] = 'Update from Kigali Drive Rentals:';
            $lines[] = $data['admin_message'];
        }

        return implode("\n", $lines);
    }

    /**
     * Build a notification payload from a saved car rental.
     *
     * @return array<string, mixed>
     */
    public function payloadFromCarRental(CarRental $rental): array
    {
        $rental->loadMissing('car');
        $packageLabel = null;
        if ($rental->booking_type === 'rent' && $rental->rental_package && $rental->car) {
            $packageLabel = app(CarRentalPackageService::class)->labelFor($rental->car, $rental->rental_package);
        }

        return [
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
            'preferred_date' => $rental->preferred_date ? Carbon::parse($rental->preferred_date)->format('Y-m-d') : null,
            'preferred_time' => $rental->preferred_time,
            'additional_request' => $rental->additional_request ?? $rental->message,
            'product_label' => 'Car: ' . ($rental->car->name ?? 'Vehicle'),
            'rental_status' => $rental->rental_status,
        ];
    }

    public function whatsappUrl(string $phone, string $message): ?string
    {
        $digits = preg_replace('/\D+/', '', $phone);
        if ($digits === '') {
            return null;
        }

        return 'https://wa.me/' . $digits . '?text=' . rawurlencode($message);
    }

    public function notifyAdmin(array $data, Setting $setting): void
    {
        $channels = app(SubmissionChannelService::class);
        $email = $channels->adminEmail($setting, 'booking');
        if (! $email) {
            return;
        }

        $body = $this->buildMessage($data);

        Mail::to($email)->send(new AdminNotification([
            'subject' => 'Reservation #' . $data['booking_number'] . ' — Kigali Drive Rentals',
            'greeting' => 'Hello,',
            'body' => $body,
            'lastline' => 'Manage this reservation in your admin dashboard.',
        ]));
    }

    /**
     * Send the client a booking received / confirmation email.
     */
    public function notifyClientReceived(array $data): void
    {
        $email = $data['email'] ?? null;
        if (! $email) {
            return;
        }

        $body = $this->buildMessage($data);

        Mail::to($email)->send(new CarBookingClientMail([
            'subject' => 'We received your booking #' . $data['booking_number'] . ' — Kigali Drive Rentals',
            'greeting' => 'Hello ' . ($data['name'] ?? '') . ',',
            'intro' => 'Thank you for your reservation request. We have received it and our team will follow up shortly. Please keep your booking number for reference.',
            'body' => $body,
            'status_label' => 'Received — ' . ucfirst((string) ($data['rental_status'] ?? 'pending')),
            'lastline' => 'Other details will be discussed based on your needs. Reply to this email or contact us on WhatsApp if you need anything.',
        ]));
    }

    /**
     * Notify the client of a status change or custom admin update.
     */
    public function notifyClientUpdate(CarRental $rental, ?string $adminMessage = null, ?string $subjectOverride = null): void
    {
        if (! $rental->email) {
            return;
        }

        $data = $this->payloadFromCarRental($rental);
        if ($adminMessage) {
            $data['admin_message'] = $adminMessage;
        }

        $status = strtolower((string) $rental->rental_status);
        $statusLabel = match ($status) {
            'confirmed' => 'Confirmed',
            'cancelled' => 'Cancelled',
            default => 'Update — ' . ucfirst($status ?: 'pending'),
        };

        $intro = match ($status) {
            'confirmed' => 'Good news — your car booking has been confirmed. Here are the current details:',
            'cancelled' => 'Your car booking has been cancelled. If this was unexpected, please contact us right away.',
            default => 'Here is an update on your car booking:',
        };

        if ($adminMessage) {
            $intro = 'Our team sent you an update on your car booking:';
        }

        $subject = $subjectOverride ?: ('Booking #' . $rental->booking_number . ' — ' . $statusLabel . ' — Kigali Drive Rentals');

        Mail::to($rental->email)->send(new CarBookingClientMail([
            'subject' => $subject,
            'greeting' => 'Hello ' . ($rental->name ?? '') . ',',
            'intro' => $intro,
            'body' => $this->buildMessage($data),
            'status_label' => $statusLabel,
            'lastline' => 'Other details will be discussed based on your needs. Reply to this email if you have questions.',
        ]));
    }

    /**
     * Resend the original “we received your booking” email to the client.
     */
    public function resendClientReceived(CarRental $rental): void
    {
        $data = $this->payloadFromCarRental($rental);
        $this->notifyClientReceived($data);
    }
}
