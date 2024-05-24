<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class dailyCreatesController extends Controller
{
    public function show(){
        return view('creates.dailyCreate');
    }

}
