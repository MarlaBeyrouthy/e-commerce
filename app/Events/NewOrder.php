<?php

namespace App\Events;
use Illuminate\Support\Collection;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
//use Couchbase\Collection;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class NewOrder implements ShouldBroadcastNow
{
    public $order;
    public $productOwner;
    public $quantity;
    public $productName;
    public $orderDate;




    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */


    public function __construct(Order $order, User $productOwner, $quantity, $productName)
    {
        $this->order = $order;
        $this->productOwner = $productOwner;
        $this->quantity = $quantity;
        $this->productName = $productName;
        $this->orderDate = $order->order_date;

    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel
     */
   /* public function broadcastOn(): array
    {
        return [
            new PrivateChannel('new-order'),
        ];
    }*/
    public function broadcastOn()
    {
        return new PrivateChannel('new-order.' . $this->productOwner->id);
    }

    public function broadcastWith()
    {
        return [
            'order' => [
                'product_name' => $this->productName,
                'quantity' => $this->quantity,
                'order_date' => $this->order->order_date,
            ],
        ];
    }





}




