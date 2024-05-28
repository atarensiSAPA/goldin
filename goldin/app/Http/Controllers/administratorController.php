<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdministratorController extends Controller
{
    public function show()
    {
        $user = Auth::user();

        // Obtener los usuarios que estan activos
        $connectedUsers = User::where('connected', 1)->where('role', '!=', 2)->count();

        return view('admin.admin-dashboard', ['user' => $user, 'connectedUsers' => $connectedUsers]);
    }

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

    public function edit($id)
    {
        $user = User::find($id);

        if (!$user) {
            return redirect()->back()->with('error', 'User not found');
        }

        return view('admin.partials.edit-user', ['user' => $user]);
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return redirect()->back()->with('error', 'User not found');
        }

        $user->update($request->only(['name', 'email', 'role', 'level', 'coins']));

        return redirect()->route('admin-users')->with('success', 'User updated successfully');
    }

    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            return redirect()->back()->with('error', 'User not found');
        }

        $user->delete();

        return redirect()->route('admin-users')->with('success', 'User deleted successfully');
    }

    public function kick(User $user)
    {
        // Marcar al usuario como "expulsado"
        $user->is_kicked = true;
        $user->connected = 0;
        $user->save();
    
        // Redirigir de vuelta a la página de administración de usuarios con un mensaje de éxito
        return redirect()->route('admin-users')->with('status', 'User has been kicked.');
    }
}
