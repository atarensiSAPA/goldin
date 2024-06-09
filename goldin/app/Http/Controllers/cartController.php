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
        // Obtén los items del carrito desde la petición
        $cartItems = $request->input('cartItems');
    
        // Verificar si hay suficientes unidades de cada artículo
        foreach ($cartItems as $item) {
            $clothes = clothes::find($item['id']);
            if ($clothes->units < $item['quantity']) {
                return response()->json([
                    'success' => false,
                    'message' => 'No hay suficientes unidades del objeto ' . $clothes->name
                ], 400);
            }
        }
    
        // Procesar la compra: restar la cantidad comprada de las unidades disponibles de cada artículo
        foreach ($cartItems as $item) {
            $clothes = clothes::find($item['id']);
            $clothes->units -= $item['quantity'];
            $clothes->save();
        }
    
        // Aquí puedes continuar con el procesamiento de la compra (crear la orden, mover los items del carrito a la orden, vaciar el carrito, etc.)
        Log::info('Purchase processed successfully');
        return response()->json([
            'success' => true,
            'message' => 'Compra procesada con éxito'
        ], 200);
    }
}
