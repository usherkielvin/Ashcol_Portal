<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VerificationCode extends Mailable
{
    use Queueable, SerializesModels;

    public $code;
    public $name;

    /**
     * Create a new message instance.
     */
    public function __construct($code, $name = null)
    {
        $this->code = $code;
        $this->name = $name;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        $fromAddress = config('mail.from.address');
        $fromNameBase = config('mail.from.name');
        $displayName = $fromNameBase ?: 'Ashcol Service Desk';

        return $this->from($fromAddress, $displayName)
                    ->subject('Email Verification Code - Ashcol Service Desk')
                    ->view('emails.verification-code')
                    ->with([
                        'code' => $this->code,
                        'name' => $this->name,
                    ]);
    }
}

