<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class blackJackController extends Controller
{
    public function show(){
        return view('minigames.black-jack');
    }

    public function placeBet(Request $request)
    {
        $user = Auth::user();
        $betAmount = $request->input('bet');

        // Restar la cantidad de la apuesta de las monedas del usuario
        $user->coins -= $betAmount;

        // Guardar el nuevo total de monedas en la base de datos
        $user->save();

        // Devolver una respuesta al cliente
        return response()->json(['message' => 'Bet placed successfully', 'coins' => $user->coins]);
    }
}
