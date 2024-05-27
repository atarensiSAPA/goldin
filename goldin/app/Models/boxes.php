<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class boxes extends Model
{
    use HasFactory;
    //get the content of the table boxes
    public static function boxesDB(){
        return boxes::all();
    }

    public function weapons()
    {
        return $this->belongsToMany(weapons::class, 'box_weapons', 'box_id', 'weapon_id');
    }
}
