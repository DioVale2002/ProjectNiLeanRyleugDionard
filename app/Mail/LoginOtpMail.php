<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LoginOtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly string $code,
        public readonly int $expiresMinutes,
    ) {
    }

    public function build(): self
    {
        return $this
            ->subject('Your NCB login code')
            ->view('emails.login-otp');
    }
}
