<?php

namespace App\Mail;

use App\Models\Articlecomment;
use App\Models\BlogComment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BlogCommentsNotofications extends Mailable
{
    use Queueable, SerializesModels;

    public $comment;
    public function __construct(BlogComment $comment)
    {
        $this->comment = $comment;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Comments Notofications',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.blogsCommentsNotifications',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
