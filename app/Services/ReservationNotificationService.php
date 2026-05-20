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
        if (!empty($data['additional_request'])) {
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
