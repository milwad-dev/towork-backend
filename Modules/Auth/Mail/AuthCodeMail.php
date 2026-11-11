<?php

namespace Modules\Auth\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Modules\Auth\Enums\AuthCodePurpose;
use Modules\Auth\Services\AuthCodeService;

class AuthCodeMail extends Mailable implements ShouldQueue
{
    use Queueable;
    use SerializesModels;

    public function __construct(public string $code, public AuthCodePurpose $purpose) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: $this->purpose->subject());
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'Auth::mail.auth-code',
            with: [
                'intro' => $this->purpose === AuthCodePurpose::VerifyEmail
                    ? 'Welcome to '.config('app.name').'! Use this code to verify your e-mail address.'
                    : 'We received a request to reset your '.config('app.name').' password. Use this code to continue.',
                'minutes' => AuthCodeService::TTL_MINUTES,
            ],
        );
    }
}
