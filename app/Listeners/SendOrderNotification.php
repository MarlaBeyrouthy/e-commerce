<?php

namespace App\Listeners;

use App\Events\NewOrder;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;
use App\Notifications\OrderPlacedNotification;


class SendOrderNotification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */



    public function handle(NewOrder $event): void
    {
        $order = $event->order;

        // Retrieve the associated cart items with the order
        $cartItems = $order->cartItems;

        // Loop through each cart item and send notifications
        foreach ($cartItems as $cartItem) {
            // Retrieve the associated product with the cart item
            $product = $cartItem->product;

            // Retrieve the product owner
            $productOwner = $product->user;

            // Send notification to the product owner
            Notification::send($productOwner, new OrderPlacedNotification($order));
        }
    }

}
