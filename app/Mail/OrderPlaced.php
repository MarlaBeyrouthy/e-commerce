<?php

namespace App\Mail;

use App\Models\City;
use App\Models\Place;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderPlaced extends Mailable
{
    use Queueable, SerializesModels;

    public $data;


    /**
     * Create a new message instance.
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Order Placed',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.order_placed',
        );
    }

   /* public function build()
    {
        return $this->markdown('emails.order_placed')
                    ->subject('New Order Placed');
    }*/
    public function build()
    {
      //  $cityName = isset($this->data['city_id']) ? City::find($this->data['city_id'])->city : '';
       // $placeName = isset($this->data['place_id']) ? Place::find($this->data['place_id'])->place : '';

        return $this->markdown('emails.order_placed')
                    ->subject('New Order Placed')
                    ->with([
                        'data' => $this->data,
                       // 'city_name' => $cityName,
                        //'place_name' => $placeName,
                        'city_name' => $this->data['city_name'],
                        'place_name' => $this->data['place_name'],

                    ]);
    }






    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
