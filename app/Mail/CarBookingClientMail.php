<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CarBookingClientMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @param  array{subject: string, greeting: string, intro: string, body: string, lastline?: string, status_label?: string}  $details
     */
    public function __construct(public array $details)
    {
    }

    public function build()
    {
        return $this
            ->from(config('mail.from.address'), config('mail.from.name', 'Kigali Drive Rentals'))
            ->subject($this->details['subject'] ?? 'Your car booking — Kigali Drive Rentals')
            ->view('emails.car-booking-client')
            ->with(['details' => $this->details]);
    }
}
