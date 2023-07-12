<?php

namespace App\Broadcasting;

use App\Models\Order;
use App\Models\User;

class NewOrderChannel
{
    /**
     * Create a new channel instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Authenticate the user's access to the channel.
     */
   /* public function join(User $user): array|bool
    {
        //
    }*/
/*    public function join($user, $orderId)
    {
        // Implement your authorization logic here
        $order = Order::find($orderId);

        if ($order && $order->user_id === $user->id) {
            return true; // User is the owner of the order, allow joining the channel
        }

        return false; // User is not authorized to join the channel
        //return true; // or false if the user should not be able to join the channel
    }*/

}
// http://127.0.0.1:8000/laravel-websockets



