<?php

namespace App\Events;

use App\Models\Product;
use App\Models\User;
use App\Notifications\ProductQuantityEmptyNotification;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;


class ProductQuantityEmpty implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public $message;
    public $productOwnerId;

    public function __construct($message,$productOwnerId)
    {
        $this->message = $message;
        $this->productOwnerId = $productOwnerId;

    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('empty-product.' . $this->productOwnerId),
        ];
    }

    public function broadcastWith()
    {
        return [
            'message' => $this->message,
        ];
    }


}
