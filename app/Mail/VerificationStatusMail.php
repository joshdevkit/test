<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VerificationStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $status_flag;

    /**
     * Create a new message instance.
     */
    public function __construct($user, $status_flag)
    {
        $this->user = $user;
        $this->status_flag = $status_flag;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Account Verification Status')
            ->view('emails.verification-status');
    }
}
