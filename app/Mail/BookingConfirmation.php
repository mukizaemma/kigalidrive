<?php

namespace App\Mail;

use App\Models\HotelBooking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Queue\SerializesModels;

class BookingConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public $booking;

    /**
     * Create a new message instance.
     */
    public function __construct(HotelBooking $booking)
    {
        $this->booking = $booking;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $recipient = $this->booking->guest_email ?? ($this->booking->user?->email ?? null);
        return new Envelope(
            subject: 'Booking Confirmation - Reference: ' . $this->booking->reference_number,
            from: new Address(config('mail.from.address'), config('mail.from.name', 'Kigali Drive Rentals')),
            to: $recipient ? [$recipient] : [],
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.bookingConfirmation',
            with: [
                'booking' => $this->booking,
                'user' => $this->booking->user,
                'guestName' => $this->booking->guest_name ?? ($this->booking->user?->name ?? 'Guest'),
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
