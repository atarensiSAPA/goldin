<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Exception;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class oauthController extends Controller
{

    public function loginWithGoogle(){
        return Socialite::driver('google')->redirect();
    }

    public function loginWithTwitter(){
        return Socialite::driver('twitter')->redirect();
    }

    public function cbGoogle(){
        try{
    
            //Obtenemos el usuario
            $user = Socialite::driver('google')->user();
    
            //Obtenemos la parte del email antes del @
            $emailParts = explode('@', $user->email);
            $emailPrefix = $emailParts[0];
    
            //Comprobamos si el usuario ya existe por external_id o email
            $userExists = User::where('external_id', $user->id)
                              ->orWhere('email', 'like', $emailPrefix . '%')
                              ->first();
    
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
    
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }

    public function cbTwitter(){
        try{
    
            //Obtenemos el usuario
            $user = Socialite::driver('twitter')->user();
    
            //Obtenemos la parte del email antes del @
            $emailParts = explode('@', $user->nickname);
            $emailPrefix = $emailParts[0];
    
            //Comprobamos si el usuario ya existe por external_id o email
            $userExists = User::where('external_id', $user->id)
                              ->orWhere('email', 'like', $emailPrefix . '%')
                              ->first();
    
            //Si existe, lo logueamos
            if($userExists){
                Auth::login($userExists);
            }else{
                //Si no existe, lo creamos y logueamos
                $newUser = User::create([
                    'name' => $user->name,
                    'email' => $user->nickname,
                    'avatar' => $user->avatar,
                    'external_id' => $user->id,
                    'external_auth' => 'twitter',
                ]);
                Auth::login($newUser);
            }
    
            //Redirigimos a la home
            return redirect('/dashboard');
    
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }
}
