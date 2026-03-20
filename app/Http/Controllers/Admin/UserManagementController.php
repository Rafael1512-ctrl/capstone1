<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserManagementController extends Controller
{
    // Tampilkan semua Admin
    public function admins()
    {
        $users = User::where('role', 'admin')->get();
        return view('admin.users.admins', compact('users'));
    }

    // Tampilkan semua Organizer
    public function organizers()
    {
        $users = User::where('role', 'organizer')->get();
        return view('admin.users.organizers', compact('users'));
    }

    // Tampilkan semua User (regular users)
    public function users()
    {
        $users = User::where('role', 'user')->get();
        return view('admin.users.users', compact('users'));
    }

    // Form Create User
    public function create($role = 'user')
    {
        return view('admin.users.create', compact('role'));
    }

    // Simpan User Baru
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,organizer,user',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => \Hash::make($request->password),
            'role' => $request->role,
        ]);

        $redirectRoute = 'admin.users.' . ($request->role == 'admin' ? 'admins' : ($request->role == 'organizer' ? 'organizers' : 'users'));
        
        return redirect()->route($redirectRoute)->with('success', 'User created successfully.');
    }

    // Form Edit User
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    // Update User
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:admin,organizer,user',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;
        
        if ($request->filled('password')) {
            $user->password = \Hash::make($request->password);
        }
        
        $user->save();

        $redirectRoute = 'admin.users.' . ($user->role == 'admin' ? 'admins' : ($user->role == 'organizer' ? 'organizers' : 'users'));

        return redirect()->route($redirectRoute)->with('success', 'User updated successfully.');
    }

    // Hapus User
    public function destroy(User $user)
    {
        $role = $user->role;
        $user->delete();

        $redirectRoute = 'admin.users.' . ($role == 'admin' ? 'admins' : ($role == 'organizer' ? 'organizers' : 'users'));

        return redirect()->route($redirectRoute)->with('success', 'User deleted successfully.');
    }
}