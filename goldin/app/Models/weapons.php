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
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_weapon');
    }

    public function creates()
    {
        return $this->belongsToMany(creates::class, 'create_weapon');
    }
}
