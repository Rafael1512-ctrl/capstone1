<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Event;
use App\Models\TicketType;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Buat user admin
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Buat organizer
        $organizer = User::create([
            'name' => 'Organizer',
            'email' => 'organizer@example.com',
            'password' => Hash::make('password'),
            'role' => 'organizer',
        ]);

        // Buat user biasa
        $user = User::create([
            'name' => 'User',
            'email' => 'user@example.com',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);

        // Buat event
        $event = Event::create([
            'organizer_id' => $organizer->id,
            'title' => 'Music Festival 2025',
            'description' => 'Festival musik terbesar tahun ini dengan lineup internasional',
            'date' => now()->addMonths(2),
            'location' => 'Jakarta International Expo',
            'banner_url' => null,
            'status' => 'published',
        ]);

        // Buat ticket types
        TicketType::create([
            'event_id' => $event->id,
            'name' => 'VIP',
            'description' => 'Akses VIP area + meet & greet',
            'price' => 500000,
            'quantity_total' => 100,
            'quantity_sold' => 0,
        ]);

        TicketType::create([
            'event_id' => $event->id,
            'name' => 'Regular',
            'description' => 'Tiket masuk reguler',
            'price' => 200000,
            'quantity_total' => 500,
            'quantity_sold' => 0,
        ]);

        TicketType::create([
            'event_id' => $event->id,
            'name' => 'Early Bird',
            'description' => 'Tiket promo terbatas',
            'price' => 150000,
            'quantity_total' => 200,
            'quantity_sold' => 0,
        ]);
    }
}