<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class minigamesController extends Controller
{

    public function showBlackJack(){
        return view('minigames.black-jack');
    }
    public function placeBet(Request $request)
    {
        $user = Auth::user();
        $betAmount = $request->input('bet');
    
        // Comprobar si el usuario tiene suficientes monedas
        if ($user->coins < $betAmount) {
            return response()->json(['message' => 'Insufficient coins', 'coins' => $user->coins], 400);
        }
    
        // Restar la cantidad de la apuesta de las monedas del usuario
        $user->coins -= $betAmount;
    
        // Guardar el nuevo total de monedas en la base de datos
        $user->save();
    
        // Devolver una respuesta al cliente
        return response()->json(['message' => 'Bet placed successfully', 'coins' => $user->coins]);
    }

    public function updateCoins(Request $request)
    {
        $user = Auth::user();
        $winnings = $request->input('coins');
    
        // Sumar las monedas ganadas al total de monedas del usuario
        $user->coins += $winnings;
    
        // Guardar el nuevo total de monedas en la base de datos
        $user->save();

        //añadirle 50 exp si gana
        $user->addExperience(50);
    
        // Devolver una respuesta al cliente
        return response()->json(['message' => 'Coins updated successfully', 'coins' => $user->coins]);
    }
}
