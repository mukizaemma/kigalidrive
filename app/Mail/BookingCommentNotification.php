<?php

namespace App\Mail;

use App\Models\HotelBooking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Queue\SerializesModels;

class BookingCommentNotification extends Mailable
{
    use Queueable, SerializesModels;

    /** @var HotelBooking */
    public $booking;

    /** @var string */
    public $authorLabel;

    /** @var string */
    public $authorName;

    /** @var string */
    public $commentText;

    /** @var string */
    public $createdAtFormatted;

    public function __construct(HotelBooking $booking, string $authorLabel, string $authorName, string $commentText, string $createdAtFormatted)
    {
        $this->booking = $booking;
        $this->authorLabel = $authorLabel;
        $this->authorName = $authorName;
        $this->commentText = $commentText;
        $this->createdAtFormatted = $createdAtFormatted;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New message on your booking - ' . $this->booking->reference_number,
            from: new Address(config('mail.from.address'), config('mail.from.name', 'Kigali Drive Rentals')),
        );
    }

    public function content(): Content
    {
        return new Content(view: 'emails.booking-comment-notification');
    }
}
