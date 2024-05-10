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
    public function weapon()
    {
        return $this->belongsTo('App\Models\weapons', 'weapon_id');
    }
}
