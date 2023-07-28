<?php

namespace App\Notifications;

use App\Events\NewOrder;
use App\Models\Product;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class OrderPlacedNotification extends Notification implements ShouldBroadcast
{
    use Queueable;

    public $order;

    /**
     * Create a new notification instance.
     */
   /* public function __construct()
    {
        //
    }*/

    public function __construct($order)
    {
        $this->order = $order;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
   /* public function via(object $notifiable): array
    {
        return ['mail'];
    }*/

    public function via(object $notifiable): array
    {
        return ['database','broadcast'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }


    public function toDatabase($notifiable)
    {
        // Assuming the event sends the order data as follows
        $order = $this->order;

        $cartItem = $this->order->cartItems->first();
        $productName = $cartItem->product->name ?? null;
        $quantity = $cartItem->quantity ?? null;
        return [
            'product_name' => $productName,
            'quantity' => $quantity,
            'order_date' => $this->order->order_date,
        ];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return BroadcastMessage
     */

    public function toBroadcast($notifiable)
    {
        $cartItem = $this->order->cartItems->first();
        $productName = $cartItem->product->name ?? null;
        $quantity = $cartItem->quantity ?? null;

        return new BroadcastMessage([
            'order' => [
                'product_name' => $productName,
                'quantity' => $quantity,
                'order_date' => $this->order->order_date,
            ],
        ]);
    }





}
