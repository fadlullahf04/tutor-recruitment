<?php

namespace App\Mail;

use App\Models\TutorProfile;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TutorRejectedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $tutor;

    /**
     * Create a new message instance.
     */
    public function __construct(TutorProfile $tutor)
    {
        $this->tutor = $tutor;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Informasi Hasil Seleksi Tutor UPT PJJ UIN Siber Cirebon',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.tutor-rejected',
        );
    }
}
