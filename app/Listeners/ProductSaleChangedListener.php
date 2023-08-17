<?php

namespace App\Listeners;

use App\Events\ProductSaleChanged;
use App\Models\User;
use App\Notifications\ProductSaleChangedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Queue\InteractsWithQueue;

class ProductSaleChangedListener
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
    public function handle(ProductSaleChanged $event)
    {
        // Access the broadcasted data
        $message = $event->message;
        $userId = $event->userId;

        // Take necessary actions here, such as sending emails, notifications, etc.
        // For example, you can create a new notification for the user
        $user = User::find($userId);
        if ($user) {
            $notification = new ProductSaleChangedNotification($message);
            $user->notify($notification);

        }
    }
}
