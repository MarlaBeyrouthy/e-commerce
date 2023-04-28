<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Session;


class AccountConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;




    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
    {



    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
      /* return $this->view('verify',[
           'code'=>$this->code,
           'user'=>$this->user,
       ]);*/

        $code = session()->get('verification_code');
        return $this->subject('Verification Code')->view('verify', compact('code'));

    }
}




