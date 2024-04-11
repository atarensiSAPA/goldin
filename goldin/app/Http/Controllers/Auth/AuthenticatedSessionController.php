<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        //Mostrar errors i comprovar si s'ha fet login 3 cops malament per mostrar el captcha
        if ($request->session()->get('login_attempts', 0) >= 3) {
            $request->validate([
                'g-recaptcha-response' => 'required|captcha',
            ], [
                'g-recaptcha-response.required' => 'Please verify that you are not a robot.',
                'g-recaptcha-response.captcha' => 'Failed to verify that you are not a robot. Please try again.',
            ]);
        }
        
        if (Auth::attempt($request->only('email', 'password'))) {
            $request->session()->forget('login_attempts');
    
            $request->session()->regenerate();
    
            return redirect()->intended(RouteServiceProvider::HOME);
        } else {
            $request->session()->put('login_attempts', $request->session()->get('login_attempts', 0) + 1);
    
            return back()->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ]);
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
