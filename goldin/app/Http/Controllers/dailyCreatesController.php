<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\creates;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class dailyCreatesController extends Controller
{
    public function show(){
        $creates = creates::get()->groupBy('box_name')->map->first();
    
        //comprobar que la caja sea daily
        $creates = $creates->filter(function ($create) {
            return $create->daily == true;
        });
        foreach ($creates as $create) {
            $create->box_name = str_replace('_', ' ', $create->box_name);
        }
    
        return view('creates.dailyCreate', ['creates' => $creates]);
    }

    public function openCreate(Request $request){
        $user = Auth::user();
        $user->addExperience(0);
        $box_name = $request->route('box_name');
        $creates = creates::where('box_name', $box_name)->get();
    
        foreach ($creates as $create) {
            $create->coins = rand(2, 10) * $create->level;
        }
    
        $firstCreate = $creates->first();
        if ($firstCreate) {
            $createTitle = str_replace('_', ' ', $firstCreate->box_name);
        } else {
            $createTitle = 'No creates found';
        }
    
        $canOpenBox = false;
        $timer = null;
        if ($user->level >= $creates->first()->level && $user->role == 1) {
            // Get the box id based on its name
            $box_id = DB::table('creates')->where('box_name', $box_name)->value('id');
        
            // Get the last time the user opened the specific box from the user_creates table
            $lastOpened = DB::table('user_creates')
                ->where('user_id', $user->id)
                ->where('create_id', $box_id)
                ->orderBy('last_opened_at', 'desc')
                ->first();
        
            // Check if 24 hours have passed since the user last opened the box
            if (!$lastOpened || $lastOpened->last_opened_at <= Carbon::now()->subDay()) {
                $canOpenBox = true;
                // Update the last opened time in the user_creates table
                DB::table('user_creates')
                    ->where('user_id', $user->id)
                    ->where('create_id', $box_id)
                    ->update(['last_opened_at' => Carbon::now()]);
            } else {
                // Calculate the time remaining until the user can open the box again
                $nextAvailableTime = Carbon::parse($lastOpened->last_opened_at)->addDay();
                $diff = Carbon::now()->diff($nextAvailableTime);
                $timer = $diff->h . 'h ' . $diff->i . 'm ' . $diff->s . 's';
            }
        }
        
        return view('creates.openCreate', ['creates' => $creates, 'createTitle' => $createTitle, 'canOpenBox' => $canOpenBox, 'timer' => $timer]);
    }

    public function ajaxDailyOpenBox(Request $request)
    {
        // Find the box
        $box = $request->input('box_name');
    
        $create = Creates::where('box_name', $box)->first();
    
        // Calculate the number of coins
        $coinsWon = rand(2, 10) * $create->level;
    
        // Get the current user
        $user = $request->user();
    
        // Add the coins to the user's total
        $user->coins += $coinsWon;
    
        // Save the user's new coin total
        $user->save();
    
        // Store the current date and time in the user_creates table
        DB::table('user_creates')->insert([
            'user_id' => $user->id,
            'create_id' => $create->id,
            'last_opened_at' => Carbon::now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    
        // Return the number of coins won and the user's total coins as a JSON response
        return response()->json(['coinsWon' => $coinsWon, 'totalCoins' => $user->coins]);
    }

}
