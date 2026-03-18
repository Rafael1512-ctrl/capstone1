<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create a dummy test account for quick login
        User::factory()->create([
            'name' => 'Dummy User',
            'email' => 'dummy@example.com',
            'password' => bcrypt('password123'),
            'role' => 'user',
            'phone' => '081234567890',
        ]);

        // Optional organizer account
        User::factory()->create([
            'name' => 'Organizer User',
            'email' => 'organizer@example.com',
            'password' => bcrypt('organizer123'),
            'role' => 'organizer',
        ]);
    }
}
