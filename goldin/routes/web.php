<?php

use App\Http\Controllers\blackJackController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\oauthController;
use App\Http\Controllers\createsController;
use App\Http\Controllers\minigamesController;
use App\Http\Controllers\shopController;

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

Route::post('/ajaxOpenBox', [createsController::class, 'ajaxOpenBox'])->middleware(['auth', 'verified']);

//Rutas de minigames
Route::get('/minigames', function () {
    return view('minigames.minigames-selector');
})->middleware(['auth', 'verified'])->name('minigames-selector');

//Black jack
Route::get('/minigames/black-jack', [minigamesController::class, 'showBlackJack'])->middleware(['auth', 'verified'])->name('black-jack');

//3 cups 1 ball
Route::get('/minigames/3cups-1ball', [minigamesController::class, 'show3cups1ball'])->middleware(['auth', 'verified'])->name('3cups-1ball');

//Bets and update coins
Route::post('/bet', [minigamesController::class, 'placeBet']);
Route::post('/update-coins', [minigamesController::class, 'updateCoins']);

Route::get('/minigames/plinko', function () {
    return view('minigames.plinko');
})->middleware(['auth', 'verified'])->name('plinko');

//Agafa el id de la ruta i el passa al controlador per saber quin caixa ha de mostrar
Route::get('/creates/{box_name}', [createsController::class, 'openCreate'], function () {
    return view('creates.openCreate');
})->middleware(['auth', 'verified'])->name('creates.show');

// Rutas del perfil
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/profile/{id}', [ProfileController::class, 'show'])->middleware(['auth', 'verified'])->name('profile');


Route::get('/edit-profile', [ProfileController::class, 'editProfile'])->middleware(['auth', 'verified'])->name('edit-profile');

Route::post('/sell-weapon', [ProfileController::class, 'sell'])->middleware(['auth', 'verified'])->name('edit-profile');

Route::post('/filter-weapons', [ProfileController::class, 'filterWeapons'])->middleware(['auth', 'verified'])->name('edit-profile');

//Oauth Google
Route::get('/login-google', [oauthController::class, 'loginWithGoogle']);
//callback de Google
Route::get('/google-callback', [oauthController::class, 'cbGoogle']);

//Oauth Twitter
Route::get('/login-twitter', [oauthController::class, 'loginWithTwitter']);
//callback de Twitter
Route::get('/twitter-callback', [oauthController::class, 'cbTwitter']);



require __DIR__.'/auth.php';
