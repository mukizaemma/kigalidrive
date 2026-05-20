<?php

namespace App\Mail;

use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReservationConfirmed extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Reservation $reservation
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Reservation Confirmed - ' . (config('mail.from.name') ?? 'Kigali Drive Rentals'),
            from: new Address(
                config('mail.from.address'),
                config('mail.from.name', 'Kigali Drive Rentals')
            ),
        );
    }

    public function content(): Content
    {
        return new Content(view: 'emails.reservation-confirmed');
    }
}


