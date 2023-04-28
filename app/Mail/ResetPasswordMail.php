<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;




class ResetPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public $resetCode;
    public $user;


    /**
     * Create a new message instance.
     *
     * @param string $resetCode
     * @return void
     */
    public function __construct($resetCode,$user)
    {
        $this->resetCode = $resetCode;
        $this->user = $user;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // You can customize the email subject, view, and data here
        return $this->subject('Password Reset')->view('reset')->with([
            'resetCode' => $this->resetCode,
            'user'=>$this->user
        ]);
    }
}

