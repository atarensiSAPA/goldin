<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class weapons extends Model
{
    use HasFactory;

    protected $fillable = ['units'];

    //get the content of the table boxes
    public static function weaponsBD(){
        return weapons::all();
    }
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_weapon');
    }

    public function boxes()
    {
        return $this->belongsToMany(boxes::class, 'box_weapons');
    }
}
