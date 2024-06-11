<?php

namespace App\Http\Controllers;

use App\Models\Boxes;
use App\Models\clothes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class clothesController extends Controller
{
    // Display all available clothes
    public function index()
    {
        try {
            $clothes = clothes::get()->groupBy('name')->map->first();
            
            foreach ($clothes as $c) {
                $c->name = str_replace('_', ' ', $c->name);
            }
            
            return view('dashboard', ['clothes' => $clothes]);
        } catch (\Exception $e) {
            Log::error('Error displaying clothes: ' . $e->getMessage());
            return response()->view('errors.general', ['message' => 'Failed to load clothes, please try again.'], 500);
        }
    }
}
