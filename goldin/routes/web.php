<?php

use App\Http\Controllers\blackJackController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\oauthController;
use App\Http\Controllers\boxesController;
use App\Http\Controllers\dailyBoxesController;
use App\Http\Controllers\minigamesController;
use App\Http\Controllers\administratorController;

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

//Rutas de la tabla boxes
Route::get('/dashboard', [boxesController::class, 'index'], function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::post('/ajaxOpenBox', [boxesController::class, 'ajaxOpenBox'])->name('ajaxOpenBox');

Route::post('/ajaxDailyOpenBox', [dailyboxesController::class, 'ajaxDailyOpenBox'])->name('ajaxDailyOpenBox');

//Agafa el id de la ruta i el passa al controlador per saber quin caixa ha de mostrar
Route::get('/boxes/{box_name}', [boxesController::class, 'openBox'])->middleware(['auth', 'verified'])->name('boxes.show');

Route::get('/dailyboxes/{box_name}', [dailyboxesController::class, 'openBox'])->middleware(['auth', 'verified'])->name('dailyboxes.show');

Route::get('/dailyboxes', [dailyboxesController::class, 'show'])->middleware(['auth', 'verified'])->name('daily-boxes');

Route::post('/user-information', [dailyboxesController::class, 'userInfo']);

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

// Route::get('/minigames/plinko', function () {
//     return view('minigames.plinko');
// })->middleware(['auth', 'verified'])->name('plinko');

// Rutas del perfil
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/profile/{id}', [ProfileController::class, 'show'])->name('profile');
    Route::get('/edit-profile', [ProfileController::class, 'editProfile'])->name('edit-profile');
    Route::post('/sell-weapon', [ProfileController::class, 'sell']);
    Route::post('/filter-weapons', [ProfileController::class, 'filterWeapons']);
    Route::post('/cancel-vip', [ProfileController::class, 'cancelVip'])->name('cancel-vip');
    Route::post('/update-vip', [ProfileController::class, 'updateVip'])->name('update-vip');
});


Route::get('/administrator', [administratorController::class, 'show'])->name('administrator');

Route::get('/administrator', [administratorController::class, 'show'])->name('administrator');

//Oauth Google
Route::get('/login-google', [oauthController::class, 'loginWithGoogle']);
//callback de Google
Route::get('/google-callback', [oauthController::class, 'cbGoogle']);

//Oauth Twitter
Route::get('/login-twitter', [oauthController::class, 'loginWithTwitter']);
//callback de Twitter
Route::get('/twitter-callback', [oauthController::class, 'cbTwitter']);



require __DIR__.'/auth.php';
