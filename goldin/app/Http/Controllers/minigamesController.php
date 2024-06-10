<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class MinigamesController extends Controller
{
    // Display the Blackjack mini-game
    public function showBlackJack()
    {
        return view('minigames.black-jack');
    }

    // Display the 3 Cups 1 Ball mini-game
    public function show3cups1ball()
    {
        return view('minigames.3cups-1ball');
    }

    // Place a bet in the mini-game
    public function placeBet(Request $request)
    {
        try {
            $user = Auth::user();
            $betAmount = $request->input('bet');
            $userType = $request->input('userType'); // Add this

            // Check if the user has enough coins
            if ($user->coins < $betAmount) {
                return response()->json(['message' => 'Insufficient coins', 'coins' => $user->coins], 400);
            }

            // Deduct the bet amount from the user's coins
            $user->coins -= $betAmount;
            $user->save();

            // Return a response to the client
            return response()->json(['message' => 'Bet placed successfully', 'coins' => $user->coins, 'userType' => $userType]);
        } catch (\Exception $e) {
            Log::error('Error placing bet: ' . $e->getMessage());
            return response()->json(['message' => 'Failed to place bet, please try again.'], 500);
        }
    }

    // Update user's coins based on the mini-game result
    public function updateCoins(Request $request)
    {
        try {
            $user = Auth::user();
            $betAmount = $request->input('bet');
            $userWon = filter_var($request->input('won'), FILTER_VALIDATE_BOOLEAN); // Convert to boolean
            $blackJack = filter_var($request->input('blackJack'), FILTER_VALIDATE_BOOLEAN); // Convert to boolean
            $winnings = 0; // Initialize winnings to 0

            if ($userWon) {
                // Calculate winnings based on user's role
                if ($user->role == 1) { // If user is VIP
                    $winnings = $betAmount * 1.5;
                    $user->addExperience(100);
                } else { // If user is normal or admin
                    $winnings = $betAmount * 1.25;
                    $user->addExperience(50);
                }

                // If user has a Black Jack, multiply winnings by 1.2
                if ($blackJack) {
                    $winnings *= 1.2;
                }

                // Add the won coins to the user's total coins
                $user->coins += $winnings;
            } else {
                // If user loses, winnings are 0 and no coins are added
                $winnings = -$betAmount; // This reflects the loss in the frontend
            }

            // Save the new total coins to the database
            $user->save();

            // Return a response to the client
            return response()->json(['message' => 'Coins updated successfully', 'coins' => $user->coins, 'winnings' => $winnings]);
        } catch (\Exception $e) {
            Log::error('Error updating coins: ' . $e->getMessage());
            return response()->json(['message' => 'Failed to update coins, please try again.'], 500);
        }
    }
}
