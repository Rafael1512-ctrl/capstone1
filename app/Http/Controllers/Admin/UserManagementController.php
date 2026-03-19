<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

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
}