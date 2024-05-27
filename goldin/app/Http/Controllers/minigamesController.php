<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class minigamesController extends Controller
{

    public function showBlackJack(){
        return view('minigames.black-jack');
    }
    public function show3cups1ball(){
        return view('minigames.3cups-1ball');
    }
    public function placeBet(Request $request)
    {
        $user = Auth::user();
        $betAmount = $request->input('bet');
        $userType = $request->input('userType'); // Añadir esto
    
        // Comprobar si el usuario tiene suficientes monedas
        if ($user->coins < $betAmount) {
            return response()->json(['message' => 'Insufficient coins', 'coins' => $user->coins], 400);
        }
    
        // Restar la cantidad de la apuesta de las monedas del usuario
        $user->coins -= $betAmount;
    
        // Guardar el nuevo total de monedas en la base de datos
        $user->save();
    
        // Devolver una respuesta al cliente
        return response()->json(['message' => 'Bet placed successfully', 'coins' => $user->coins, 'userType' => $userType]); // Añadir userType a la respuesta
    }
    
    public function updateCoins(Request $request)
    {
        $user = Auth::user();
        $betAmount = $request->input('bet');
        $userWon = filter_var($request->input('won'), FILTER_VALIDATE_BOOLEAN); // Convertir a booleano
        $blackJack = filter_var($request->input('blackJack'), FILTER_VALIDATE_BOOLEAN); // Convertir a booleano
        $winnings = 0; // Inicializar las ganancias a 0
    
        if ($userWon) {
            // Calcular las ganancias dependiendo del rol del usuario
            if ($user->role == 1) { // Si el usuario es VIP
                $winnings = $betAmount * 1.5;
                $user->addExperience(100);
            } else { // Si el usuario es normal o admin
                $winnings = $betAmount * 1.25;
                $user->addExperience(50);
            }
    
            // Si el usuario tiene Black Jack, multiplicar las ganancias por 1.2
            if ($blackJack) {
                $winnings *= 1.2;
            }
    
            // Sumar las monedas ganadas al total de monedas del usuario
            $user->coins += $winnings;
        } else {
            // Si el usuario pierde, las ganancias son 0 y no se suman monedas
            $winnings = -$betAmount; // Esto refleja la pérdida en el frontend
        }
    
        // Guardar el nuevo total de monedas en la base de datos
        $user->save();
    
        // Devolver una respuesta al cliente
        return response()->json(['message' => 'Coins updated successfully', 'coins' => $user->coins, 'winnings' => $winnings]);
    }
}
