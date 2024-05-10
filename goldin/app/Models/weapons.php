<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class weapons extends Model
{
    use HasFactory;
    //get the content of the table creates
    public static function weaponsBD(){
        return weapons::all();
    }
}
