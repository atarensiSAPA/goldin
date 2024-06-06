<?php

namespace App\Http\Controllers;
use App\Models\boxes;
use Illuminate\Http\Request;
use App\Models\clothes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Exception;

class clothesController extends Controller
{
    // Display all available boxes
    public function index()
    {
        $clothes = clothes::get()->groupBy('name')->map->first();

        // Filter out non-daily boxes
        $clothes = $clothes->filter(function ($c) {
            return $c->daily == false;
        });
        
        foreach ($clothes as $c) {
            $c->box_name = str_replace('_', ' ', $c->name);
        }
        
        return view('dashboard', ['clothes' => $clothes]);
    }
}
