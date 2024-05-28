<?php

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

Route::middleware(['auth', 'verified', 'CheckIfKicked'])->group(function () {
    //Rutas de la tabla boxes
    Route::get('/dashboard', [boxesController::class, 'index'], function () {
        return view('dashboard');
    })->name('dashboard');

    Route::post('/ajaxOpenBox', [boxesController::class, 'ajaxOpenBox'])->name('ajaxOpenBox');

    Route::post('/ajaxDailyOpenBox', [dailyBoxesController::class, 'ajaxDailyOpenBox'])->name('ajaxDailyOpenBox');

    //Agafa el id de la ruta i el passa al controlador per saber quin caixa ha de mostrar
    Route::get('/boxes/{box_name}', [boxesController::class, 'openBox'])->name('boxes.show');

    Route::get('/dailyboxes/{box_name}', [dailyBoxesController::class, 'openBox'])->name('dailyboxes.show');

    Route::get('/dailyboxes', [dailyBoxesController::class, 'show'])->name('daily-boxes');

    Route::post('/user-information', [dailyBoxesController::class, 'userInfo']);

    //Rutas de minigames
    Route::get('/minigames', function () {
        return view('minigames.minigames-selector');
    })->name('minigames-selector');

    //Black jack
    Route::get('/minigames/black-jack', [minigamesController::class, 'showBlackJack'])->name('black-jack');

    //3 cups 1 ball
    Route::get('/minigames/3cups-1ball', [minigamesController::class, 'show3cups1ball'])->name('3cups-1ball');

    //Bets and update coins
    Route::post('/bet', [minigamesController::class, 'placeBet']);
    Route::post('/update-coins', [minigamesController::class, 'updateCoins']);

    // Rutas del perfil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/user-profile', [ProfileController::class, 'show'])->name('user-profile');
    Route::get('/edit-profile', [ProfileController::class, 'editProfile'])->name('edit-profile');
    Route::post('/sell-weapon', [ProfileController::class, 'sell']);
    Route::post('/filter-weapons', [ProfileController::class, 'filterWeapons']);
    Route::post('/cancel-vip', [ProfileController::class, 'cancelVip'])->name('cancel-vip');
    Route::post('/update-vip', [ProfileController::class, 'updateVip'])->name('update-vip');
});

// Rutas de admin
Route::middleware(['auth', 'verified', 'admin', 'CheckIfKicked'])->group(function () {
    Route::get('/administrator', [administratorController::class, 'show'])->name('administrator');
    Route::get('/admin-users', [administratorController::class, 'showUsers'])->name('admin-users');
    Route::post('/add-user', [administratorController::class, 'store']);
    Route::get('/admin-users/{user}/edit', [administratorController::class, 'edit'])->name('users.edit');
    Route::put('/admin-users/{user}', [administratorController::class, 'update'])->name('users.update');
    Route::delete('/admin-users/{user}', [AdministratorController::class, 'destroy'])->name('admin-users.destroy');
    Route::post('/admin-users/kick/{user}', [AdministratorController::class, 'kick'])->name('users.kick');

    Route::get('/admin-boxes', [administratorController::class, 'showBoxes'])->name('admin-boxes');
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
