<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $table = 'carts';
    protected $fillable = [
        'user_id',
        'order_id',
        'cart_item_id',
        'Total_price',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
/*
    public function cartItem()
    {
        return $this->belongsTo(Cart_Item::class);
    }*/

    public function cartItem()
    {
        return $this->belongsTo(Cart_Item::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }


}
