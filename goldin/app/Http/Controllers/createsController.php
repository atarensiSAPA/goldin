<?php

namespace App\Http\Controllers;
use App\Models\creates;
use Illuminate\Http\Request;

class createsController extends Controller
{
    public function index()
    {
        //mostrar totes les caixes agrupades per nom de caixa i agafar la primera
        $creates = creates::all()->groupBy('box_name')->map->first();

        return view('dashboard', ['creates' => $creates]);
    }

    public function openCreate(Request $request)
    {
        // Get the box_name from the URL
        $box_name = $request->route('box_name');
    
        // Get all creates with the same box_name
        $creates = creates::where('box_name', $box_name)->with('weapon')->get();
    
        return view('creates.openCreate', ['creates' => $creates]);
    }
}
    