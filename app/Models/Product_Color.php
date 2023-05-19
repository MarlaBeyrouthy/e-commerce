<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product_Color extends Model
{
    use HasFactory;
    protected $table = 'product_color';
    protected $fillable = [
        'product_Id',
        'color_Id',

    ];
    public $timestamps = false;


   /* public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function color()
    {
        return $this->belongsTo(Color::class);
    }*/
}
