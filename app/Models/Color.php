<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
    use HasFactory;

    protected $table = 'colors';
    protected $fillable = [
        'color',
    ];
    public $timestamps = false;


   /* public function products()
    {
        return $this->belongsToMany(Product::class, 'product__colors');
    }*/

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

}
