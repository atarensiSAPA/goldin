<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\oauthController;
use App\Http\Controllers\createsController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('/auth/login');
});

//Rutas de la tabla creates
Route::get('/dashboard', [createsController::class, 'index'], function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

//Agafa el id de la ruta i el passa al controlador per saber quin caixa ha de mostrar
Route::get('/creates/{box_name}', [createsController::class, 'openCreate'], function () {
    return view('creates.openCreate');
})->middleware(['auth', 'verified'])->name('creates.show');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


//Oauth Google
Route::get('/login-google', [oauthController::class, 'loginWithGoogle']);
//callback de Google
Route::get('/google-callback', [oauthController::class, 'cbGoogle']);

//Oauth Twitter
Route::get('/login-twitter', [oauthController::class, 'loginWithTwitter']);
//callback de Twitter
Route::get('/twitter-callback', [oauthController::class, 'cbTwitter']);

require __DIR__.'/auth.php';
