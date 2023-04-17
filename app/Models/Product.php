<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;


    protected $table = 'products';
    protected $fillable = [
        'product',
        'description',
        'brand_name',
        'price',
        'photo_product',
        'user_id',
        'seller_id',
        'category_id',
        'color_id',
        'size_id',
        'material',

    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subcategory()
    {
        return $this->belongsTo(Subcategory::class);
    }

    public function cartItems()
    {
        return $this->hasMany(Cart_Item::class);
    }

    public function colors()
    {
        return $this->belongsToMany(Color::class, 'product__colors');
    }

    public function sizes()
    {
        return $this->belongsToMany(Size::class, 'product_sizes');
    }
}
