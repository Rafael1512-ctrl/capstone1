<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\User::create([
            'name' => 'Admin Tixly',
            'email' => 'admin@tixly.com',
            'password' => \Illuminate\Support\Facades\Hash::make('admin123'),
            'role' => 'Admin',
        ]);
    }
}
