<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TutorRegisteredMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $rawPassword;
    public $nik;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, string $rawPassword, string $nik)
    {
        $this->user = $user;
        $this->rawPassword = $rawPassword;
        $this->nik = $nik;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Pendaftaran Akun Tutor UPT PJJ UIN Siber Cirebon - Berhasil',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.tutor-registered',
        );
    }
}
