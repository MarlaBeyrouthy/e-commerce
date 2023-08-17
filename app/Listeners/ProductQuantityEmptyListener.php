<?php

namespace App\Listeners;

namespace App\Listeners;

use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Notifications\Channels\BroadcastChannel;
class ProductQuantityEmptyListener
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
    public function handle(\App\Events\ProductQuantityEmpty $event)
    {
        // Assuming you have the necessary logic to determine the user and send the notification
        $user = User::find($event->productOwnerId);

        if ($user) {
            // Create a new instance of the notification and send it to the user
            $notification = new \App\Notifications\ProductQuantityEmptyNotification($event->message);
            $user->notify($notification);
        }
    }

    public function broadcastOn(\App\Events\ProductQuantityEmpty $event): array
    {
        return [
            new PrivateChannel('empty-product.' . $event->productOwnerId),
        ];
    }
}
