<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;

try {
    $user = User::where('role', 'user')->first();
    if ($user) {
        $id = $user->id;
        echo "Attempting to delete user ID: $id ($user->name)" . PHP_EOL;
        $user->delete();
        echo "Delete successful!" . PHP_EOL;
    } else {
        echo "No user found to delete." . PHP_EOL;
    }
} catch (Exception $e) {
    echo "Delete failed: " . $e->getMessage() . PHP_EOL;
}
