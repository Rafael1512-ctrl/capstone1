<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

try {
    // Cari admin yang sudah ada (berdasarkan role admin)
    $admin = User::where('role', 'admin')->first();
    
    if ($admin) {
        $admin->update([
            'email' => '2472020@maranatha.ac.id',
            'password' => Hash::make('admin123')
        ]);
        echo "Admin updated successfully!" . PHP_EOL;
    } else {
        // Jika tidak ada admin sama sekali, buat baru
        User::create([
            'name' => 'Admin',
            'email' => '2472020@maranatha.ac.id',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'email_verified_at' => now()
        ]);
        echo "Admin created successfully!" . PHP_EOL;
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . PHP_EOL;
}
