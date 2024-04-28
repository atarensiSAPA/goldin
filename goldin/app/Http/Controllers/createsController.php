<?php

namespace App\Http\Controllers;
use App\Models\creates;
use Illuminate\Http\Request;

class createsController extends Controller
{
    public function index(){
        //return all the content of the table creates to dashboard
        return view('dashboard', ['creates' => creates::createsDB()]);
    }
}
