<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserManagementController extends Controller
{
    // Tampilkan semua Admin
    public function admins()
    {
        $users = User::where('role_id', 1)->get();
        return view('admin.users.admins', compact('users'));
    }

    // Tampilkan semua Organizer
    public function organizers()
    {
        $users = User::where('role_id', 2)->get();
        return view('admin.users.organizers', compact('users'));
    }

    // Tampilkan semua User (regular users)
    public function users()
    {
        $users = User::where('role_id', 3)->get();
        return view('admin.users.users', compact('users'));
    }

    // Form Create User
    public function create($role = 'user')
    {
        $role_id = ($role == 'admin' ? 1 : ($role == 'organizer' ? 2 : 3));
        return view('admin.users.create', compact('role', 'role_id'));
    }

    // Simpan User Baru
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role_id' => 'required|in:1,2,3',
        ]);

        $hashedPassword = Hash::make($request->password);

        DB::statement("CALL AddUser(?, ?, ?, ?)", [
            $request->name,
            $request->email,
            $hashedPassword,
            $request->role_id
        ]);

        $role = ($request->role_id == 1 ? 'admin' : ($request->role_id == 2 ? 'organizer' : 'user'));
        $redirectRoute = 'admin.users.' . ($role == 'admin' ? 'admins' : ($role == 'organizer' ? 'organizers' : 'users'));
        
        return redirect()->route($redirectRoute)->with('success', 'User created successfully.');
    }

    // Form Edit User
    public function edit($user_id)
    {
        $user = User::findOrFail($user_id);
        return view('admin.users.edit', compact('user'));
    }

    // Update User
    public function update(Request $request, $user_id)
    {
        $user = User::findOrFail($user_id);
        
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|string|email|max:100|unique:users,email,' . $user->user_id . ',user_id',
            'password' => 'nullable|string|min:8|confirmed',
            'role_id' => 'required|in:1,2,3',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->role_id = $request->role_id;
        
        if ($request->filled('password')) {
            $user->pass = Hash::make($request->password);
        }
        
        $user->save();

        $role = ($user->role_id == 1 ? 'admin' : ($user->role_id == 2 ? 'organizer' : 'user'));
        $redirectRoute = 'admin.users.' . ($role == 'admin' ? 'admins' : ($role == 'organizer' ? 'organizers' : 'users'));

        return redirect()->route($redirectRoute)->with('success', 'User updated successfully.');
    }

    // Hapus User
    public function destroy($user_id)
    {
        $user = User::findOrFail($user_id);
        $role_id = $user->role_id;
        $user->delete();

        $role = ($role_id == 1 ? 'admin' : ($role_id == 2 ? 'organizer' : 'user'));
        $redirectRoute = 'admin.users.' . ($role == 'admin' ? 'admins' : ($role == 'organizer' ? 'organizers' : 'users'));

        return redirect()->route($redirectRoute)->with('success', 'User deleted successfully.');
    }
}