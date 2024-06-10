<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\DailyBoxes;
use App\Models\Clothes;

class AdministratorController extends Controller
{
    // Mostrar el panel de administración
    public function show()
    {
        try {
            $user = Auth::user();
        
            // Obtener el número de usuarios conectados
            $connectedUsers = User::where('connected', 1)->where('role', '!=', 2)->count();
        
            return view('admin.admin-dashboard', ['user' => $user, 'connectedUsers' => $connectedUsers]);
        } catch (\Exception $e) {
            Log::error('Error displaying admin dashboard: ' . $e->getMessage());
            return response()->view('errors.general', ['message' => 'Failed to load admin dashboard, please try again.'], 500);
        }
    }

    // Mostrar usuarios no administradores con funcionalidad de búsqueda
    public function showUsers(Request $request)
    {
        try {
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
        } catch (\Exception $e) {
            Log::error('Error displaying users: ' . $e->getMessage());
            return response()->view('errors.general', ['message' => 'Failed to load users, please try again.'], 500);
        }
    }

    // Almacenar un nuevo usuario
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:8',
        ]);

        try {
            $user = new User;
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->email_verified_at = now();
            $user->save();

            return redirect()->route('admin-users')->with('success', 'User added successfully');
        } catch (\Exception $e) {
            Log::error('Error storing new user: ' . $e->getMessage());
            return redirect()->route('admin-users')->with('error', 'Failed to add user, please try again.');
        }
    }

    // Mostrar formulario de edición de usuario
    public function edit($id)
    {
        try {
            $user = User::findOrFail($id);

            return view('admin.partials.edit-user', ['user' => $user]);
        } catch (\Exception $e) {
            Log::error('Error displaying user edit form: ' . $e->getMessage());
            return redirect()->back()->with('error', 'User not found');
        }
    }

    // Actualizar detalles del usuario
    public function update(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);

            $user->update($request->only(['name', 'email', 'role', 'level', 'coins']));

            return redirect()->route('admin-users')->with('success', 'User updated successfully');
        } catch (\Exception $e) {
            Log::error('Error updating user: ' . $e->getMessage());
            return redirect()->route('admin-users')->with('error', 'Failed to update user, please try again.');
        }
    }

    // Eliminar un usuario
    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();

            return redirect()->route('admin-users')->with('success', 'User deleted successfully');
        } catch (\Exception $e) {
            Log::error('Error deleting user: ' . $e->getMessage());
            return redirect()->route('admin-users')->with('error', 'Failed to delete user, please try again.');
        }
    }

    // Expulsar a un usuario del sistema
    public function kick(User $user)
    {
        try {
            // Marcar al usuario como "expulsado"
            $user->is_kicked = true;
            $user->connected = 0;
            $user->save();
        
            return redirect()->route('admin-users')->with('success', 'User has been kicked.');
        } catch (\Exception $e) {
            Log::error('Error kicking user: ' . $e->getMessage());
            return redirect()->route('admin-users')->with('error', 'Failed to kick user, please try again.');
        }
    }

    // Mostrar todas las prendas
    public function showClothes()
    {
        try {
            $clothes = Clothes::all();
            return view('admin.partials.admin-clothes', ['clothes' => $clothes]);
        } catch (\Exception $e) {
            Log::error('Error displaying clothes: ' . $e->getMessage());
            return response()->view('errors.general', ['message' => 'Failed to load clothes, please try again.'], 500);
        }
    }
}
