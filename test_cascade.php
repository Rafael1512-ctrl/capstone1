<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\Event;
use App\Models\Order;

try {
    $user = User::create([
        'name' => 'Test User',
        'email' => 'test_order@example.com',
        'password' => Hash::make('password'),
        'role' => 'user'
    ]);
    
    $event = Event::first(); // Asumsikan ada event minimal satu dari dump
    if (!$event) {
        $event = Event::create(['title' => 'Test Event', 'date' => now()->addDays(1), 'location' => 'Jakarta', 'organizer_id' => $user->id]);
    }
    
    $order = Order::create([
        'user_id' => $user->id,
        'event_id' => $event->id,
        'total_amount' => 1000,
        'status' => 'pending'
    ]);
    
    echo "Created user ID: $user->id and order ID: $order->id" . PHP_EOL;
    $user->delete();
    echo "Delete user with order successful! Cascade working." . PHP_EOL;
} catch (Exception $e) {
    echo "Failed: " . $e->getMessage() . PHP_EOL;
    // Bersihkan jika perlu
    if (isset($user)) $user->delete();
}
