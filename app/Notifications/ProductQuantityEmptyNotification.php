<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;


class ProductQuantityEmptyNotification extends Notification implements ShouldBroadcast
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public $message;


    public function __construct($message )
    {
        $this->message = $message;


    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['broadcast','database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Product Quantity Alert')
            ->line('Product quantity has reached zero:')
            ->line('Product: ' . $this->product->name)
            ->line('Product ID: ' . $this->product->id);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */



    public function toArray(object $notifiable): array
    {
        return [
            'message' => $this->message,

        ];
    }
    public function toDatabase($notifiable)
    {
        return [
            'message' => $this->message,
        ];

    }

    /**
     * Get the array representation of the notification.
     *
     * @param $notifiable
     *
     * @return BroadcastMessage
     */


    public function toBroadcast($notifiable): BroadcastMessage {
        return new BroadcastMessage([

            'message'=>$this->message
        ]);
    }


}
