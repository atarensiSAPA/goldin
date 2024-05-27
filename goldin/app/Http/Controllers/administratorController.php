<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Carbon\Carbon;

class administratorController extends Controller
{
    public function show(){
        $user = Auth::user();

        // Obtener los usuarios que estan activos
        $connectedUsers = User::where('connected', 1)->where('role', '!=', 2)->count();

        return view('admin.admin-dashboard', ['user' => $user, 'connectedUsers' => $connectedUsers]);
    }

    public function showUsers(){
        $users = User::all();

        $nonAdminUsers = User::where('role', '!=', 2)->get();

        return view('admin.partials.admin-users', ['users' => $users, 'nonAdminUsers' => $nonAdminUsers]);
    }
}