<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\oauthController;
use App\Http\Controllers\clothesController;
use App\Http\Controllers\dailyBoxesController;
use App\Http\Controllers\minigamesController;
use App\Http\Controllers\AdministratorController;
use App\Http\Controllers\cartController;
use Illuminate\Support\Facades\Route;

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
    // Routes of the boxes table
    Route::get('/dashboard', [clothesController::class, 'index'])->name('dashboard');

    Route::post('/ajaxDailyOpenBox', [dailyBoxesController::class, 'ajaxDailyOpenBox'])->name('ajaxDailyOpenBox');

    Route::get('/dailyboxes/{box_name}', [dailyBoxesController::class, 'openBox'])->name('dailyboxes.show');

    Route::get('/dailyboxes', [dailyBoxesController::class, 'show'])->name('daily-boxes');

    Route::post('/user-information', [dailyBoxesController::class, 'userInfo']);

    // Minigames routes
    Route::get('/minigames', function () {
        return view('minigames.minigames-selector');
    })->name('minigames-selector');

    // Black jack
    Route::get('/minigames/black-jack', [minigamesController::class, 'showBlackJack'])->name('black-jack');

    // 3 cups 1 ball
    Route::get('/minigames/3cups-1ball', [minigamesController::class, 'show3cups1ball'])->name('3cups-1ball');

    // Bets and update coins
    Route::post('/bet', [minigamesController::class, 'placeBet']);
    Route::post('/update-coins', [minigamesController::class, 'updateCoins']);

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/user-profile', [ProfileController::class, 'show'])->name('user-profile');
    Route::get('/edit-profile', [ProfileController::class, 'editProfile'])->name('edit-profile');
    Route::post('/filter-weapons', [ProfileController::class, 'filterWeapons']);
    Route::post('/cancel-vip', [ProfileController::class, 'cancelVip'])->name('cancel-vip');
    Route::post('/update-vip', [ProfileController::class, 'updateVip'])->name('update-vip');

    //Cart
    Route::get('/showBuyCart', [cartController::class, 'show'])->name('showBuyCart');
    Route::post('/buyCart', [cartController::class, 'buyCart'])->name('buyCart');
});

// Admin routes
Route::middleware(['auth', 'verified', 'admin', 'CheckIfKicked'])->group(function () {
    Route::get('/administrator', [AdministratorController::class, 'show'])->name('administrator');
    Route::get('/admin-users', [AdministratorController::class, 'showUsers'])->name('admin-users');
    Route::post('/add-user', [AdministratorController::class, 'store']);
    Route::get('/admin-users/{user}/edit', [AdministratorController::class, 'edit'])->name('users.edit');
    Route::put('/admin-users/{user}', [AdministratorController::class, 'update'])->name('users.update');
    Route::delete('/admin-users/{user}', [AdministratorController::class, 'destroy'])->name('admin-users.destroy');
    Route::post('/admin-users/kick/{user}', [AdministratorController::class, 'kick'])->name('users.kick');

    Route::get('/admin-boxes', [AdministratorController::class, 'showBoxes'])->name('admin-boxes');
    Route::post('/admin/boxes', [AdministratorController::class, 'storeBox'])->name('boxes.store');
});

// OAuth Google
Route::get('/login-google', [oauthController::class, 'loginWithGoogle'])->name('login-google');
// Google callback
Route::get('/google-callback', [oauthController::class, 'cbGoogle']);

// OAuth Twitter
Route::get('/login-twitter', [oauthController::class, 'loginWithTwitter'])->name('login-twitter');;
// Twitter callback
Route::get('/twitter-callback', [oauthController::class, 'cbTwitter']);

require __DIR__.'/auth.php';
