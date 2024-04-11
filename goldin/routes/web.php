<?php

use App\Http\Controllers\ProfileController;
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

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

//Google OAUTH
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

//driver de Socialite
Route::get('/login-google', function () {
    return Socialite::driver('google')->redirect();
});

//callback de Google
Route::get('/google-callback', function () {
    //Obtenemos el usuario
    $user = Socialite::driver('google')->user();
 
    //Comprobamos si el usuario ya existe
    $userExists = User::where('external_id', $user->id)->first();

    //Si existe, lo logueamos
    if($userExists){
        Auth::login($userExists);
    }else{
        //Si no existe, lo creamos y logueamos
        $newUser = User::create([
            'name' => $user->name,
            'email' => $user->email,
            'avatar' => $user->avatar,
            'external_id' => $user->id,
            'external_auth' => 'google',
        ]);
        Auth::login($newUser);
    }

    //Redirigimos a la home
    return redirect('/dashboard');
});

require __DIR__.'/auth.php';
