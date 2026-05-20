<?php

namespace App\Mail;

use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReservationNotifications extends Mailable
{
    use Queueable, SerializesModels;
    public $details;
    public function __construct($details)
    {
        return $this->details= $details;
    }

    public function build(){
        return $this->from(config('mail.from.address'), config('mail.from.name', 'Kigali Drive Rentals'))
        ->subject('New Update')
        ->view('emails.registrationNotifications')
        ->with($this->details);

    }

    public function attachments(): array
    {
        return [];
    }
}
