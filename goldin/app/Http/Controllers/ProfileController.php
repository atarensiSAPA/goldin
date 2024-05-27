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
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class ProfileController extends Controller
{

    public function editProfile()
    {
        $user = Auth::user();
        return view('profile.partials.edit-profile-form', ['user' => $user]);
    }

    public function getRoleName($role) {
        switch ($role) {
            case 0:
                return 'Normal';
            case 1:
                return 'VIP';
            case 2:
                return 'Admin';
            default:
                return 'Unknown';
        }
    }

    public function show()
    {
        $user = Auth::user();
        $weapons = $user->weapons()->orderBy('updated_at', 'desc')->get();

        // Obtener el filtro seleccionado del localStorage a través del request si existe
        $selectedFilter = request()->input('filter', session('selectedFilter', 'obtention'));
        session(['selectedFilter' => $selectedFilter]);
    
        // Aplicar el filtro
        $weapons = $this->applyFilter($weapons, $selectedFilter);

        // Check if the VIP subscription has expired
        if ($user->vip_expires_at && Carbon::parse($user->vip_expires_at)->isPast()) {
            // If the VIP subscription has expired, reset it to 1 month from now
            $user->vip_expires_at = Carbon::now()->addMonth();
            $user->save();
        }

        $user->vip_expires_at ? Carbon::parse($user->vip_expires_at)->diffForHumans() : null;
    
        // Calculate the user's level and experience
        $user->addExperience(0);
    
        $maxExperience = $user->max_experience; // Use the accessor to get the max_experience

        $user->role = $this->getRoleName($user->role); // What rol is the user
    
        // Add color and appearance percentage to each weapon
        foreach ($weapons as $weapon) {
            $weapon->color = $this->getColorForRarity($weapon->rarity);
        }
    
        return view('profile.user-information', ['user' => $user, 'maxExperience' => $maxExperience, 'weapons' => $weapons]);
    }

    public function applyFilter($weapons, $filter)
    {
        switch ($filter) {
            case 'price':
                return $weapons->sortByDesc('price')->values();
            case 'rarity':
                // Define el orden de las rarezas
                $rarityOrder = ['commun', 'rare', 'epic', 'legendary', 'mitic'];
                // Ordena las armas por rareza
                return $weapons->sortBy(function ($weapon) use ($rarityOrder) {
                    return array_search($weapon->rarity, $rarityOrder);
                })->values();
            case 'obtention':
            default:
                return $weapons->sortByDesc('updated_at')->values();
        }
    }
    
    public function filterWeapons(Request $request)
    {
        $filter = $request->input('filter');
        $user = Auth::user();
    
        // Almacenar el filtro seleccionado en la sesión
        session(['selectedFilter' => $filter]);
    
        // Aplicar el filtro
        $weapons = $this->applyFilter($user->weapons()->get(), $filter);
    
        // Añade el color y el porcentaje de apariencia a cada arma
        foreach ($weapons as $weapon) {
            $weapon->color = $this->getColorForRarity($weapon->rarity);
        }
    
        return response()->json(['weapons' => $weapons]);
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
    
        // Busca el arma en la base de datos
        $weapon = weapons::find($weaponId);
    
        if (!$weapon) {
            // Si el arma no existe, devuelve un error
            return response()->json(['error' => 'Weapon not found'], 404);
        }
    
        // Calcula el precio de venta del arma
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
    
        // Devuelve una respuesta de éxito con el nuevo balance de monedas
        return response()->json(['success' => true, 'newCoinBalance' => $user->coins]);
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
    
    public function cancelVip(Request $request)
    {
        $request->validate([
            'password' => 'required',
        ], [
            'password.required' => 'Please fill out the password field.',
        ]);
    
        $user = Auth::user();
    
        if (Hash::check($request->password, $user->password)) {
            $user->role = 0;
            $user->save();
    
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false, 'error' => 'The provided password is incorrect.']);
        }
    }
}
