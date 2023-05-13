<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model{
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
        'sizes'

    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function colors()
    {
        return $this->belongsToMany(Color::class, 'product_color', 'product_id', 'color_id');
    }
/*
    public function sizes()
    {
        return $this->hasMany(Size::class);
    }
    public function colors()
    {
        return $this->hasMany(Color::class);
    }
 */


}
