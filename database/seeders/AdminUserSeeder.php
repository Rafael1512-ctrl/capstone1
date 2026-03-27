<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        // Admin Accounts
        $admins = [
            '2472024@maranatha.ac.id',
            '2472025@maranatha.ac.id',
        ];

        foreach ($admins as $email) {
            $user = User::where('email', $email)->first();
            if ($user) {
                $user->update([
                    'role_id' => 1, // Admin
                    'email_verified_at' => now(),
                    'pass' => Hash::make('admin123'),
                ]);
            } else {
                User::create([
                    'user_id' => 'ADM-' . Str::random(5),
                    'name' => 'Admin ' . explode('@', $email)[0],
                    'email' => $email,
                    'pass' => Hash::make('admin123'),
                    'role_id' => 1,
                    'email_verified_at' => now(),
                ]);
            }
        }

        // Organizer Account
        $organizerEmail = 'organizer@maranatha.ac.id';
        $organizer = User::where('email', $organizerEmail)->first();
        if ($organizer) {
            $organizer->update([
                'role_id' => 2, // Organizer
                'email_verified_at' => now(),
                'pass' => Hash::make('organizer123'),
            ]);
        } else {
            User::create([
                'user_id' => 'ORG-' . Str::random(5),
                'name' => 'Organizer LuxTix',
                'email' => $organizerEmail,
                'pass' => Hash::make('organizer123'),
                'role_id' => 2,
                'email_verified_at' => now(),
            ]);
        }

        $this->command->info('Admin and Organizer accounts have been created/updated and verified!');
    }
}
