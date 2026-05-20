<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PublicationNotification extends Mailable
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
        ->view('emails.updatesNotifications')
        ->with($this->details);

    }
    public function attachments(): array
    {
        return [];
    }
}
