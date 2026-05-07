<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CustomerOtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly string $subjectText,
        public readonly string $messageText,
        public readonly string $code,
        public readonly int $expiresMinutes,
    ) {
    }

    public function build(): self
    {
        return $this
            ->subject($this->subjectText)
            ->view('emails.customer-otp');
    }
}
