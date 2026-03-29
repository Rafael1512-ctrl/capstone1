<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * Display the user's profile.
     */
    public function show()
    {
        $user = auth()->user();
        return view('profile.show', compact('user'));
    }

    /**
     * Show the form for editing the user's profile.
     */
    public function edit()
    {
        $user = auth()->user();
        return view('profile.edit', compact('user'));
    }

    /**
     * Update the user's profile.
     */
    public function update(Request $request)
    {
        $user = auth()->user();

        // Validate input
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:100|unique:users,email,' . $user->user_id . ',user_id',
            'pass' => 'nullable|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'bio' => 'nullable|string|max:500',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Update name, email, phone, bio
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->phone = $validated['phone'];
        $user->bio = $validated['bio'];

        // Handle Profile Photo Upload
        if ($request->hasFile('profile_photo')) {
            $file = $request->file('profile_photo');
            $filename = $user->user_id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('profile-photos', $filename, 'public');
            $user->profile_photo = $path;
        }

        // Update password if provided
        if (!empty($validated['pass'])) {
            $user->pass = Hash::make($validated['pass']);
        }

        $user->save();

        return redirect()->route('profile.show')
            ->with('success', 'Profil Anda berhasil diperbarui!');
    }
}
