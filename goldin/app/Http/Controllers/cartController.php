<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\clothes;
use Illuminate\Support\Facades\Log;

class cartController extends Controller
{
    public function show()
    {
        $clothes = clothes::get()->groupBy('name')->map->first();

        foreach ($clothes as $c) {
            $c->name = str_replace('_', ' ', $c->name);
        }

        return view('cart.showDivsCart', ['clothes' => $clothes]);
    }

    public function buyCart(Request $request)
    {
        // Obtén el usuario actual
        $user = $request->user();
    
        // Obtén los items del carrito desde la petición
        $cartItems = $request->input('cartItems');
    
        // Calcular el total del carrito
        $total = 0;
        foreach ($cartItems as $item) {
            $clothes = Clothes::find($item['id']);
            $total += $clothes->price * $item['quantity'];
        }
    
        // Verificar si el usuario tiene suficientes monedas
        if ($user->coins < $total) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes suficientes monedas para realizar esta compra'
            ], 400);
        }
    
        // Verificar si hay suficientes unidades de cada artículo
        foreach ($cartItems as $item) {
            $clothes = Clothes::find($item['id']);
            if ($clothes->units < $item['quantity']) {
                return response()->json([
                    'success' => false,
                    'message' => 'No hay suficientes unidades del objeto ' . $clothes->name
                ], 400);
            }
        }
    
        // Procesar la compra: restar la cantidad comprada de las unidades disponibles de cada artículo
        foreach ($cartItems as $item) {
            $clothes = Clothes::find($item['id']);
            $clothes->units -= $item['quantity'];
            $clothes->save();
        }
    
        // Restar las monedas del usuario
        $user->coins -= $total;
        $user->save();
    
        // Aquí puedes continuar con el procesamiento de la compra (crear la orden, mover los items del carrito a la orden, vaciar el carrito, etc.)
        Log::info('Purchase processed successfully');
        return response()->json([
            'success' => true,
            'message' => 'Compra procesada con éxito'
        ], 200);
    }
}
