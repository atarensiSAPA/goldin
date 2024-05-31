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
use Illuminate\Support\Facades\Mail;
use App\Mail\WeaponWithdrawn;

class ProfileController extends Controller
{

    // Function to edit the profile
    public function editProfile()
    {
        $user = Auth::user();
        return view('profile.partials.edit-profile-form', ['user' => $user]);
    }

    // Function to get the role name based on role ID
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

    // Function to show the user profile
    public function show()
    {
        $user = Auth::user();
        
        // Get the user's weapons ordered by the last update time
        $weapons = $user->weapons()->orderBy('updated_at', 'desc')->get();

        // Get the selected filter from localStorage or request, default to 'obtention'
        $selectedFilter = request()->input('filter', session('selectedFilter', 'obtention'));
        session(['selectedFilter' => $selectedFilter]);
    
        // Apply the selected filter
        $weapons = $this->applyFilter($weapons, $selectedFilter);

        // Check if the VIP subscription has expired
        if ($user->vip_expires_at && Carbon::parse($user->vip_expires_at)->isPast()) {
            // If expired, reset it to 1 month from now
            $user->vip_expires_at = Carbon::now()->addMonth();
            $user->save();
        }

        $user->vip_expires_at ? Carbon::parse($user->vip_expires_at)->diffForHumans() : null;
    
        // Calculate the user's level and experience
        $user->addExperience(0);
    
        $maxExperience = $user->max_experience; // Use the accessor to get the max_experience

        $roleName = $this->getRoleName($user->role); // Get the role name
    
        // Add color and appearance percentage to each weapon
        foreach ($weapons as $weapon) {
            $weapon->color = $this->getColorForRarity($weapon->rarity);
        }
    
        return view('profile.user-information', ['user' => $user, 'maxExperience' => $maxExperience, 'weapons' => $weapons, 'roleName' => $roleName]);
    }

    // Function to apply filter to weapons
    public function applyFilter($weapons, $filter)
    {
        switch ($filter) {
            case 'price':
                return $weapons->sortByDesc('price')->values();
            case 'rarity':
                // Define the rarity order
                $rarityOrder = ['commun', 'rare', 'epic', 'legendary', 'mitic'];
                // Sort weapons by rarity
                return $weapons->sortBy(function ($weapon) use ($rarityOrder) {
                    return array_search($weapon->rarity, $rarityOrder);
                })->values();
            case 'obtention':
            default:
                return $weapons->sortByDesc('updated_at')->values();
        }
    }
    
    // Function to filter weapons based on the selected filter
    public function filterWeapons(Request $request)
    {
        $filter = $request->input('filter');
        $user = Auth::user();
    
        // Store the selected filter in the session
        session(['selectedFilter' => $filter]);
    
        // Apply the filter
        $weapons = $this->applyFilter($user->weapons()->get(), $filter);
    
        // Add color and appearance percentage to each weapon
        foreach ($weapons as $weapon) {
            $weapon->color = $this->getColorForRarity($weapon->rarity);
        }
    
        return response()->json(['weapons' => $weapons]);
    }
    
    // Function to get color for weapon rarity
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

    // Function to sell a weapon
    public function sell(Request $request)
    {
        $weaponId = $request->input('weapon_id');
    
        // Find the weapon in the database
        $weapon = weapons::find($weaponId);
    
        if (!$weapon) {
            // If the weapon doesn't exist, return an error
            return response()->json(['error' => 'Weapon not found'], 404);
        }
    
        // Calculate the selling price of the weapon
        $sellPrice = $weapon->price;
    
        // Add the selling price to the user's account
        $user = Auth::user();
        $user->coins += $sellPrice;
        $user->save();
    
        // Find and delete a single entry from the pivot table
        $userWeapon = DB::table('user_weapons')
            ->where('user_id', $user->id)
            ->where('weapon_id', $weaponId)
            ->first();
    
        Log::info('User weapon to be deleted:', ['user_weapons' => $userWeapon]);
    
        if ($userWeapon) {
            DB::table('user_weapons')->where('id', $userWeapon->id)->delete();
            Log::info('User weapon deleted successfully');
        } else {
            Log::info('No user weapon found to delete');
        }
    
        // Return a success response with the new coin balance
        return response()->json(['success' => true, 'newCoinBalance' => $user->coins]);
    }

    // Function to withdraw a weapon
    public function withdrawWeapon(Request $request)
    {
        $weaponId = $request->input('weapon_id');
        $user = Auth::user();
        $weapon = weapons::find($weaponId);
    
        if (!$weapon) {
            return response()->json(['message' => 'Weapon not found'], 404);
        }
    
        // Find the user's weapon in the user_weapons table
        $userWeapon = DB::table('user_weapons')
                        ->where('user_id', $user->id)
                        ->where('weapon_id', $weaponId)
                        ->first();
    
        if (!$userWeapon) {
            return response()->json(['message' => 'User does not have this weapon'], 400);
        }

        if ($weapon->units <= 0) {
            return response()->json(['message' => 'No units available, please try again later'], 400);
        }
    
        // Delete the first instance of the user's weapon
        DB::table('user_weapons')->where('id', $userWeapon->id)->delete();
    
        // Decrease the weapon's units by 1 and save
        $weapon->units -= 1;
        $weapon->save();
    
        // Send an email to the user about the weapon withdrawal
        Mail::to($user->email)->send(new WeaponWithdrawn($user, $weapon));
    
        return response()->json(['success' => true]);
    }

    /**
     * Display the user's profile form.
     */
    public function edit(Request $request)
    {
        return redirect()->route('user-profile');
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
        $user = $request->user();

        // Si el usuario ha iniciado sesión a través de OAuth, no es necesario verificar la contraseña
        if (!$user->external_auth) {
            $request->validateWithBag('userDeletion', [
                'password' => ['required', 'current_password'],
            ]);
        }

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
    
    // Function to cancel the VIP subscription
    public function cancelVip(Request $request)
    {
        $request->validate([
            'password' => 'required',
        ], [
            'password.required' => 'Please fill out the password field.',
        ]);
    
        $user = Auth::user();
    
        // Check if the provided password matches the user's password
        if (Hash::check($request->password, $user->password)) {
            $user->role = 0;
            $user->save();
    
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false, 'error' => 'The provided password is incorrect.']);
        }
    }

    // Function to update the VIP subscription
    public function updateVip(Request $request)
    {
        $user = Auth::user();
        $user->role = 1;
        $user->vip_expires_at = Carbon::now()->addMonth();
        $user->save();
    
        return response()->json(['success' => true]);
    }
}
