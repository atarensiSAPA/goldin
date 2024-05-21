<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\weapons;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{

    public function editProfile()
    {
        $user = Auth::user();
        return view('profile.partials.edit-profile-form', ['user' => $user]);
    }

    public function show()
    {
        $user = Auth::user();
        $weapons = $user->weapons;
    
        // Calculate the user's level and experience
        $user->addExperience(0);
    
        $maxExperience = $user->max_experience; // Use the accessor to get the max_experience
    
        // Add color and appearance percentage to each weapon
        foreach ($weapons as $weapon) {
            $weapon->color = $this->getColorForRarity($weapon->rarity);
        }
    
        return view('profile.user-information', ['user' => $user, 'maxExperience' => $maxExperience, 'weapons' => $weapons]);
    }
    
    public function getColorForRarity($rarity)
    {
        switch ($rarity) {
            case 'mitic':
                return 'rgba(255,0,0,0.8)';
            case 'legendary':
                return 'rgba(255,165,0,0.8)';
            case 'epic':
                return 'rgba(128,0,128,0.8)';
            case 'rare':
                return 'rgba(0,0,255,0.8)';
            case 'commun':
                return 'rgba(0,128,0,0.8)';
        }
    }

    public function sell(Request $request)
    {
        $weaponId = $request->input('weapon_id');
    
        // Busca la arma en la base de datos
        $weapon = weapons::find($weaponId);
    
        if (!$weapon) {
            // Si la arma no existe, devuelve un error
            return response()->json(['error' => 'Weapon not found'], 404);
        }
    
        // Calcula el precio de venta de la arma
        $sellPrice = $weapon->price;
    
        // Añade el precio de venta a la cuenta del usuario
        $user = Auth::user();
        $user->coins += $sellPrice;
        $user->save();
    
        // Encuentra y elimina una sola entrada de la tabla intermedia
        $userWeapon = DB::table('user_weapon')
            ->where('user_id', $user->id)
            ->where('weapon_id', $weaponId)
            ->first();

        Log::info('User weapon to be deleted:', ['user_weapon' => $userWeapon]);

        if ($userWeapon) {
            DB::table('user_weapon')->where('id', $userWeapon->id)->delete();
            Log::info('User weapon deleted successfully');
        } else {
            Log::info('No user weapon found to delete');
        }
    
        // Devuelve una respuesta de éxito
        return response()->json(['success' => true]);
    }

    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.user-information', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
