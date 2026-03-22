<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$app->boot();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

$users = User::all();
foreach ($users as $user) {
    // Check if password has a hash-like prefix
    if (!str_starts_with($user->pass, '$2y$') && !str_starts_with($user->pass, '$argon2')) {
        echo "Hashing password for: " . $user->email . " (current: " . $user->pass . ")\n";
        $user->pass = Hash::make($user->pass);
        $user->save();
    }
}
echo "All passwords are now hashed correctly.\n";
