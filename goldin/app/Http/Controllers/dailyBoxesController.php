<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\daily_boxes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DailyBoxesController extends Controller
{
    // Display the daily boxes available to the user
    public function show()
    {
        try {
            $boxes = daily_boxes::get()->groupBy('box_name')->map->first();
        
            foreach ($boxes as $box) {
                $box->box_name = str_replace('_', ' ', $box->box_name);
            }
        
            return view('boxes.dailyBox', ['boxes' => $boxes]);
        } catch (\Exception $e) {
            Log::error('Error displaying daily boxes: ' . $e->getMessage());
            return response()->view('errors.general', ['message' => 'Failed to load daily boxes, please try again.'], 500);
        }
    }

    // Open a daily box
    public function openBox(Request $request)
    {
        try {
            $user = Auth::user();
            $user->addExperience(0);
            $box_name = $request->route('box_name');
            $boxes = daily_boxes::where('box_name', $box_name)->get();
        
            foreach ($boxes as $box) {
                $box->coins = rand(2, 10) * $box->level;
            }
        
            $firstBox = $boxes->first();
            $boxTitle = $firstBox ? str_replace('_', ' ', $firstBox->box_name) : 'No boxes found';
        
            $canOpenBox = false;
            $timer = null;
            if ($user->level >= $boxes->first()->level && $user->role == 1) {
                // Get the box id based on its name
                $box_id = DB::table('daily_boxes')->where('box_name', $box_name)->value('id');
            
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
                        ->updateOrInsert(
                            ['user_id' => $user->id, 'box_id' => $box_id],
                            ['last_opened_at' => Carbon::now(), 'updated_at' => now()]
                        );
                } else {
                    // Calculate the time remaining until the user can open the box again
                    $nextAvailableTime = Carbon::parse($lastOpened->last_opened_at)->addDay();
                    $diff = Carbon::now()->diff($nextAvailableTime);
                    $timer = $diff->h . 'h ' . $diff->i . 'm ' . $diff->s . 's';
                }
            }
            
            return view('boxes.openBox', ['boxes' => $boxes, 'boxTitle' => $boxTitle, 'canOpenBox' => $canOpenBox, 'timer' => $timer]);
        } catch (\Exception $e) {
            Log::error('Error opening box: ' . $e->getMessage());
            return response()->view('errors.general', ['message' => 'Failed to open the box, please try again.'], 500);
        }
    }

    // AJAX request to open a daily box and receive coins
    public function ajaxDailyOpenBox(Request $request)
    {
        try {
            $boxName = $request->input('box_name');
        
            $box = daily_boxes::where('box_name', $boxName)->firstOrFail();
        
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
        } catch (\Exception $e) {
            Log::error('Error in AJAX daily box opening: ' . $e->getMessage());
            return response()->json(['message' => 'Failed to open the daily box, please try again.'], 500);
        }
    }
}
