<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\Clothes;
use App\Models\purchase_history;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\ClothesBuy;

class ProfileController extends Controller
{
    // Function to edit the profile
    public function editProfile(): View
    {
        $user = Auth::user();
        return view('profile.partials.edit-profile-form', ['user' => $user]);
    }

    // Function to get the role name based on role ID
    public function getRoleName(int $role): string
    {
        return match ($role) {
            0 => 'Normal',
            1 => 'VIP',
            2 => 'Admin',
            default => 'Unknown',
        };
    }

    // Function to show the user profile
    public function show(): View
    {
        $user = Auth::user();

        // Get the selected filter from session or request, default to 'obtention'
        $selectedFilter = request()->input('filter', session('selectedFilter', 'obtention'));
        session(['selectedFilter' => $selectedFilter]);

        // Check if the VIP subscription has expired
        if ($user->vip_expires_at && Carbon::parse($user->vip_expires_at)->isPast()) {
            $user->vip_expires_at = Carbon::now()->addMonth();
            $user->save();
        }

        $user->addExperience(0);
        $maxExperience = $user->max_experience;
        $roleName = $this->getRoleName($user->role);

        $purchaseHistory = purchase_history::where('user_id', $user->id)->get();

        return view('profile.user-information', [
            'user' => $user,
            'maxExperience' => $maxExperience,
            'roleName' => $roleName,
            'purchaseHistory' => $purchaseHistory,
        ]);
    }

    // Function to apply filter to weapons
    public function applyFilter($weapons, string $filter)
    {
        return match ($filter) {
            'price' => $weapons->sortByDesc('price')->values(),
            default => $weapons->sortByDesc('updated_at')->values(),
        };
    }

    // Function to sell a weapon
    public function sell(Request $request)
    {
        $weaponId = $request->input('weapon_id');

        // Find the weapon in the database
        $weapon = Clothes::find($weaponId);

        if (!$weapon) {
            return response()->json(['error' => 'Weapon not found'], 404);
        }

        $user = Auth::user();
        $sellPrice = $weapon->price;
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

        return response()->json(['success' => true, 'newCoinBalance' => $user->coins]);
    }

    // Function to withdraw a weapon
    public function withdrawWeapon(Request $request)
    {
        $weaponId = $request->input('weapon_id');
        $user = Auth::user();
        $weapon = Clothes::find($weaponId);

        if (!$weapon) {
            return response()->json(['message' => 'Weapon not found'], 404);
        }

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

        DB::table('user_weapons')->where('id', $userWeapon->id)->delete();
        $weapon->units -= 1;
        $weapon->save();

        Mail::to($user->email)->send(new ClothesBuy($user, $weapon));

        return response()->json(['success' => true]);
    }

    // Display the user's profile form.
    public function edit(Request $request): RedirectResponse
    {
        return redirect()->route('user-profile');
    }

    // Update the user's profile information.
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $user->fill($request->validated());

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    // Delete the user's account.
    public function destroy(Request $request): RedirectResponse
    {
        $user = $request->user();

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
