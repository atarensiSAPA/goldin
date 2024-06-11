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
    
        // Check if the VIP subscription has expired
        if ($user->vip_expires_at && Carbon::parse($user->vip_expires_at)->isPast()) {
            $user->vip_expires_at = Carbon::now()->addMonth();
            $user->save();
        }
    
        $user->addExperience(0);
        $maxExperience = $user->max_experience;
        $roleName = $this->getRoleName($user->role);
    
        // Obtener el historial de compras con la información de la ropa
        $purchaseHistory = purchase_history::where('user_id', $user->id)
            ->with('clothes')
            ->get()
            ->map(function ($purchase) {
                $purchase->clothes->name = str_replace('_', ' ', $purchase->clothes->name);
                $purchase->clothes->type = str_replace('_', ' ', $purchase->clothes->type);
                $purchase->formatted_created_at = $purchase->created_at->toIso8601String();
                return $purchase;
            });
    
        return view('profile.user-information', [
            'user' => $user,
            'maxExperience' => $maxExperience,
            'roleName' => $roleName,
            'purchaseHistory' => $purchaseHistory,
        ]);
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
