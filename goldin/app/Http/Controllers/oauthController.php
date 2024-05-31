<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Exception;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class oauthController extends Controller
{

    // Redirect to Google login page
    public function loginWithGoogle(){
        return Socialite::driver('google')->redirect();
    }

    // Redirect to Twitter login page
    public function loginWithTwitter(){
        return Socialite::driver('twitter')->redirect();
    }

    // Callback function after Google login
    public function cbGoogle(){
        try{
    
            // Get the user details from Google
            $user = Socialite::driver('google')->user();
    
            // Extract the email prefix before '@'
            $emailParts = explode('@', $user->email);
            $emailPrefix = $emailParts[0];
    
            // Check if the user already exists by external_id or email
            $userExists = User::where('external_id', $user->id)
                              ->orWhere('email', 'like', $emailPrefix . '%')
                              ->first();
    
            // If the user exists, log them in
            if($userExists){
                Auth::login($userExists);
            }else{
                // If the user doesn't exist, create and log them in
                $newUser = User::create([
                    'name' => $user->name,
                    'email' => $user->email,
                    'avatar' => $user->avatar,
                    'external_id' => $user->id,
                    'external_auth' => 'google',
                ]);
                Auth::login($newUser);
            }

            $user = Auth::user();    
            // Update connected field with current time
            $user->connected = 1;
            $user->is_kicked = 0;
            $user->save();
            // Redirect to the dashboard
            return redirect('/dashboard');
    
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }

    // Callback function after Twitter login
    public function cbTwitter(){
        try{
    
            // Get the user details from Twitter
            $user = Socialite::driver('twitter')->user();
    
            // Extract the email prefix before '@'
            $emailParts = explode('@', $user->nickname);
            $emailPrefix = $emailParts[0];
    
            // Check if the user already exists by external_id or email
            $userExists = User::where('external_id', $user->id)
                              ->orWhere('email', 'like', $emailPrefix . '%')
                              ->first();
    
            // If the user exists, log them in
            if($userExists){
                Auth::login($userExists);
            }else{
                // If the user doesn't exist, create and log them in
                $newUser = User::create([
                    'name' => $user->name,
                    'email' => $user->nickname,
                    'avatar' => $user->avatar,
                    'external_id' => $user->id,
                    'external_auth' => 'twitter',
                ]);
                Auth::login($newUser);
            }

            $user = Auth::user();    
            // Update connected field with current time
            $user->connected = 1;
            $user->is_kicked = 0;
            $user->save();
            // Redirect to the dashboard
            return redirect('/dashboard');
    
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }
}
