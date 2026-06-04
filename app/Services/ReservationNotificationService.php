<?php

namespace App\Services;

use App\Mail\AdminNotification;
use App\Models\Setting;
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
                $lines[] = 'Driver: ' . ($withDriver ? 'With driver' : 'Self-drive');
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

        return implode("\n", $lines);
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
        $email = $setting->booking_email_enabled ? ($setting->booking_email ?: $setting->email) : null;
        if (!$email) {
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
}
