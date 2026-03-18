<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

try {
    $users = User::all();
    echo "Hashing " . $users->count() . " users..." . PHP_EOL;
    foreach ($users as $user) {
        // Only hash if not already hashed (Bcrypt hashes start with $2y$)
        if (!str_starts_with($user->pass, '$2y$')) {
            $user->pass = Hash::make($user->pass);
            $user->save();
            echo "Updated user: {$user->name}" . PHP_EOL;
        } else {
            echo "User already hashed: {$user->name}" . PHP_EOL;
        }
    }
    echo "Done!" . PHP_EOL;
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . PHP_EOL;
}
