<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\boxes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class dailyBoxesController extends Controller
{
    // Display the daily boxes available to the user
    public function show(){
        $boxes = boxes::get()->groupBy('box_name')->map->first();
    
        // Filter out non-daily boxes
        $boxes = $boxes->filter(function ($box) {
            return $box->daily == true;
        });
        foreach ($boxes as $box) {
            $box->box_name = str_replace('_', ' ', $box->box_name);
        }
    
        return view('boxes.dailyBox', ['boxes' => $boxes]);
    }

    // Open a daily box
    public function openBox(Request $request){
        $user = Auth::user();
        $user->addExperience(0);
        $box_name = $request->route('box_name');
        $boxes = boxes::where('box_name', $box_name)->get();
    
        foreach ($boxes as $box) {
            $box->coins = rand(2, 10) * $box->level;
        }
    
        $firstBox = $boxes->first();
        if ($firstBox) {
            $boxTitle = str_replace('_', ' ', $firstBox->box_name);
        } else {
            $boxTitle = 'No boxes found';
        }
    
        $canOpenBox = false;
        $timer = null;
        if ($user->level >= $boxes->first()->level && $user->role == 1) {
            // Get the box id based on its name
            $box_id = DB::table('boxes')->where('box_name', $box_name)->value('id');
        
            // Get the last time the user opened the specific box from the user_boxes table
            $lastOpened = DB::table('user_boxes')
                ->where('user_id', $user->id)
                ->where('box_id', $box_id)
                ->orderBy('last_opened_at', 'desc')
                ->first();
        
            // Check if 24 hours have passed since the user last opened the box
            if (!$lastOpened || $lastOpened->last_opened_at <= Carbon::now()->subDay()) {
                $canOpenBox = true;
                // Update the last opened time in the user_boxes table
                DB::table('user_boxes')
                    ->where('user_id', $user->id)
                    ->where('box_id', $box_id)
                    ->update(['last_opened_at' => Carbon::now()]);
            } else {
                // Calculate the time remaining until the user can open the box again
                $nextAvailableTime = Carbon::parse($lastOpened->last_opened_at)->addDay();
                $diff = Carbon::now()->diff($nextAvailableTime);
                $timer = $diff->h . 'h ' . $diff->i . 'm ' . $diff->s . 's';
            }
        }
        
        return view('boxes.openBox', ['boxes' => $boxes, 'boxTitle' => $boxTitle, 'canOpenBox' => $canOpenBox, 'timer' => $timer]);
    }

    // AJAX request to open a daily box and receive coins
    public function ajaxDailyOpenBox(Request $request)
    {
        // Find the box
        $box = $request->input('box_name');
    
        $box = boxes::where('box_name', $box)->first();
    
        // Calculate the number of coins
        $coinsWon = rand(2, 10) * $box->level;
    
        // Get the current user
        $user = $request->user();
    
        // Add the coins to the user's total
        $user->coins += $coinsWon;
    
        // Save the user's new coin total
        $user->save();
    
        // Store the current date and time in the user_boxes table
        DB::table('user_boxes')->insert([
            'user_id' => $user->id,
            'box_id' => $box->id,
            'last_opened_at' => Carbon::now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    
        // Return the number of coins won and the user's total coins as a JSON response
        return response()->json(['coinsWon' => $coinsWon, 'totalCoins' => $user->coins]);
    }

}
