<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Exception;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class OauthController extends Controller
{
    // Redirect to Google login page
    public function loginWithGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    // Redirect to Twitter login page
    public function loginWithTwitter()
    {
        return Socialite::driver('twitter')->redirect();
    }

    // Callback function after Google login
    public function cbGoogle()
    {
        try {
            // Get the user details from Google
            $googleUser = Socialite::driver('google')->user();

            // Extract the email prefix before '@'
            $emailPrefix = strstr($googleUser->email, '@', true);

            // Check if the user already exists by external_id or email
            $user = User::where('external_id', $googleUser->id)
                        ->orWhere('email', 'like', $emailPrefix . '%')
                        ->first();

            if ($user) {
                Auth::login($user);
            } else {
                // If the user doesn't exist, create and log them in
                $user = User::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'avatar' => $googleUser->avatar,
                    'external_id' => $googleUser->id,
                    'external_auth' => 'google',
                ]);
                Auth::login($user);
            }

            // Update connected field with current time
            $user->update([
                'connected' => 1,
                'is_kicked' => 0,
            ]);

            // Redirect to the dashboard
            return redirect('/dashboard');
        } catch (Exception $e) {
            Log::error('Error during Google login callback: ' . $e->getMessage());
            return redirect('/login')->with('error', 'Failed to login with Google, please try again.');
        }
    }

    // Callback function after Twitter login
    public function cbTwitter()
    {
        try {
            // Get the user details from Twitter
            $twitterUser = Socialite::driver('twitter')->user();

            // Extract the email prefix before '@'
            $emailPrefix = strstr($twitterUser->nickname, '@', true);

            // Check if the user already exists by external_id or email
            $user = User::where('external_id', $twitterUser->id)
                        ->orWhere('email', 'like', $emailPrefix . '%')
                        ->first();

            if ($user) {
                Auth::login($user);
            } else {
                // If the user doesn't exist, create and log them in
                $user = User::create([
                    'name' => $twitterUser->name,
                    'email' => $twitterUser->nickname,
                    'avatar' => $twitterUser->avatar,
                    'external_id' => $twitterUser->id,
                    'external_auth' => 'twitter',
                ]);
                Auth::login($user);
            }

            // Update connected field with current time
            $user->update([
                'connected' => 1,
                'is_kicked' => 0,
            ]);

            // Redirect to the dashboard
            return redirect('/dashboard');
        } catch (Exception $e) {
            Log::error('Error during Twitter login callback: ' . $e->getMessage());
            return redirect('/login')->with('error', 'Failed to login with Twitter, please try again.');
        }
    }
}
