<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$app->boot();

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

// Create sample User
$userData = [
    'user_id' => 'U-00001',
    'name'    => 'Budi Santoso',
    'email'   => 'user@example.com',
    'pass'    => Hash::make('user123'),
    'role_id'  => 3,
];

try {
    // Check if exists
    if (User::where('email', $userData['email'])->exists()) {
        $user = User::where('email', $userData['email'])->first();
        $user->update($userData);
        echo "User updated.\n";
    } else {
        User::create($userData);
        echo "User created.\n";
    }
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage();
}
