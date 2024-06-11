<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\clothes;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\clothesBuy;

class CartController extends Controller
{
    // Mostrar los artículos del carrito
    public function show()
    {
        try {
            $clothes = clothes::get()->groupBy('name')->map->first();

            foreach ($clothes as $c) {
                $c->name = str_replace('_', ' ', $c->name);
            }

            return view('cart.showDivsCart', ['clothes' => $clothes]);
        } catch (\Exception $e) {
            Log::error('Error displaying cart items: ' . $e->getMessage());
            return response()->view('errors.general', ['message' => 'Failed to load cart items, please try again.'], 500);
        }
    }

    // Procesar la compra de los artículos del carrito
    public function buyCart(Request $request)
    {
        try {
            // Obtén el usuario actual
            $user = $request->user();

            // Obtén los items del carrito desde la petición
            $cartItems = $request->input('cartItems');

            // Calcular el total del carrito
            $total = 0;
            foreach ($cartItems as $item) {
                $clothes = clothes::findOrFail($item['id']);
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
                $clothes = clothes::findOrFail($item['id']);
                if ($clothes->units < $item['quantity']) {
                    return response()->json([
                        'success' => false,
                        'message' => 'No hay suficientes unidades del objeto ' . $clothes->name
                    ], 400);
                }
            }

            // Procesar la compra: restar la cantidad comprada de las unidades disponibles de cada artículo
            foreach ($cartItems as $item) {
                $clothes = clothes::findOrFail($item['id']);
                $clothes->units -= $item['quantity'];
                $clothes->save();

                // Guardar el historial de compra
                DB::table('purchase_history')->insert([
                    'user_id' => $user->id,
                    'clothes_id' => $clothes->id,
                    'quantity' => $item['quantity'],
                    'price' => $clothes->price,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }

            // Restar las monedas del usuario
            $user->coins -= $total;
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Compra procesada con éxito'
            ], 200);
        } catch (\Exception $e) {
            // Revertir la transacción en caso de error
            DB::rollBack();
            Log::error('Error processing purchase: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al procesar la compra, por favor intenta nuevamente.'
            ], 500);
        }
    }

    public function sendBuyMail(Request $request){
        // Obtén el usuario actual
        $user = $request->user();

        // Obtén los items del carrito desde la petición
        $cartItems = $request->input('cartItems');

        $items = [];
        $total = 0;
        foreach ($cartItems as $item) {
            $clothes = clothes::findOrFail($item['id']);
            // Calcular el total del artículo y sumarlo al total general
            $totalItem = $clothes->price * $item['quantity'];
            $total += $totalItem;

            // Agregar el artículo al array de artículos
            $items[] = [
                'clothes' => $clothes,
                'quantity' => $item['quantity'],
                'totalItem' => $totalItem,
            ];
        }
        // Envío de correo electrónico
        Mail::to($user->email)->send(new clothesBuy($user, $items, $total));
    }
}
