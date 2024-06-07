<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\daily_boxes;
use App\Models\weapons;

class AdministratorController extends Controller
{
    // Show the administrator dashboard
    public function show()
    {
        $user = Auth::user();
    
        // Get the number of connected users
        $connectedUsers = User::where('connected', 1)->where('role', '!=', 2)->count();

        // Count all available daily boxes
        $availableDailyBoxes = daily_boxes::where('available', true)->count();
    
        return view('admin.admin-dashboard', ['user' => $user, 'connectedUsers' => $connectedUsers, 'availableDailyBoxes' => $availableDailyBoxes]);
    }

    // Show non-administrator users with search functionality
    public function showUsers(Request $request)
    {
        $search = $request->input('search');

        if ($search) {
            $nonAdminUsers = User::where('role', '!=', 2)
                                ->where(function($query) use ($search) {
                                    $query->where('name', 'LIKE', "%$search%")
                                          ->orWhere('email', 'LIKE', "%$search%");
                                })
                                ->get();
        } else {
            $nonAdminUsers = User::where('role', '!=', 2)->get();
        }

        return view('admin.partials.admin-users', ['nonAdminUsers' => $nonAdminUsers]);
    }

    // Store a new user
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:8',
        ]);

        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->email_verified_at = now();
        $user->save();

        return redirect()->route('admin-users')->with('success', 'User added successfully');
    }

    // Show user edit form
    public function edit($id)
    {
        $user = User::find($id);

        if (!$user) {
            return redirect()->back()->with('error', 'User not found');
        }

        return view('admin.partials.edit-user', ['user' => $user]);
    }

    // Update user details
    public function update(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return redirect()->back()->with('error', 'User not found');
        }

        $user->update($request->only(['name', 'email', 'role', 'level', 'coins']));

        return redirect()->route('admin-users')->with('success', 'User updated successfully');
    }

    // Delete a user
    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            return redirect()->back()->with('error', 'User not found');
        }

        $user->delete();

        return redirect()->route('admin-users')->with('success', 'User deleted successfully');
    }

    // Kick a user from the system
    public function kick(User $user)
    {
        // Mark the user as "kicked"
        $user->is_kicked = true;
        $user->connected = 0;
        $user->save();
    
        // Redirect back to the user administration page with a success message
        return redirect()->route('admin-users')->with('success', 'User has been kicked.');
    }

    // Show all boxes and available weapons
    public function showBoxes()
    {
        $boxes = boxes::all();
        $weapons = weapons::all();

        return view('admin.partials.admin-boxes', ['boxes' => $boxes, 'weapons' => $weapons]);
    }

    // Store a new box
    public function storeBox(Request $request)
    {
        $request->validate([
            'box_name' => 'required|max:255',
            'box_img' => 'required|image',
            'cost' => 'required|integer',
            'daily' => 'boolean',
            'weapons' => 'array',
        ]);

        $box = new boxes;
        $box->box_name = $request->box_name;

        if ($request->hasFile('box_img')) {
            $imageName = time().'.'.$request->box_img->extension();
            $request->box_img->move(public_path('images/boxes'), $imageName);
            $box->box_img = $imageName;
        }

        $box->cost = $request->cost;
        $box->daily = $request->daily ? true : false;
        $box->save();

        if (!$box->daily && $request->has('weapons')) {
            $box->weapons()->sync($request->weapons);
        }

        return redirect()->route('admin-boxes')->with('success', 'Box added successfully');
    }
}
