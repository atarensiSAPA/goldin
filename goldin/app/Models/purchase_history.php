<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class purchase_history extends Model
{
    use HasFactory;

    protected $table = 'purchase_history';
    protected $fillable = [
        'user_id', 
        'clothes_id', 
        'quantity', 
        'price', 
        'created_at', 
        'updated_at'
    ];

    public function clothes()
    {
        return $this->belongsTo(Clothes::class);
    }
}
