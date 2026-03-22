<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;

try {
    $user = User::create([
        'name' => 'Test User',
        'email' => 'test_delete@example.com',
        'password' => Hash::make('password'),
        'role' => 'user'
    ]);
    $id = $user->id;
    echo "Created user ID: $id" . PHP_EOL;
    $user->delete();
    echo "Delete successful!" . PHP_EOL;
} catch (Exception $e) {
    echo "Failed: " . $e->getMessage() . PHP_EOL;
}
