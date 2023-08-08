<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'description',
        'price',
        'category',
        'gender',
        'brand_name',
        'user_id',
        'material',
        'photo',
        'quantity',
        'in_stock',
        'sale',
        'sizes'

    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function colors()
    {
        return $this->belongsToMany(Color::class, 'product_color');
    }


    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function wishlistedBy()
    {
        return $this->belongsToMany(User::class, 'wishlists');
    }

    public function calculateAverageRating()
    {
        $averageRating = $this->reviews()->avg('rating');
        $this->update(['average_rating' => $averageRating]);
        return $averageRating;
    }


}
