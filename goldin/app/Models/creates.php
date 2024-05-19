<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class creates extends Model
{
    use HasFactory;
    //get the content of the table creates
    public static function createsDB(){
        return creates::all();
    }

    public function weapons()
    {
        return $this->belongsToMany(weapons::class, 'create_weapon', 'create_id', 'weapon_id');
    }
}
