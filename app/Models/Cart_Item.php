<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart_Item extends Model
{
    use HasFactory;

    protected $table = 'cart__items';
    protected $fillable = [
        'product_id',
        'quantity',
        'price',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

  /*  public function carts()
    {
        return $this->hasMany(Cart::class);
    }*/

    public function sizes()
    {
        return $this->belongsToMany(Size::class);
    }

    public function colors()
    {
        return $this->belongsToMany(Color::class);
    }

    public function carts()
    {
        return $this->hasMany(Cart::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
