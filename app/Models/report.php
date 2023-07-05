<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class report extends Model
{
    use HasFactory;

    protected $table = 'reports';
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'checked',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
