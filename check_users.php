<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;

$users = User::all();
echo "Total Users: " . $users->count() . PHP_EOL;
foreach ($users as $user) {
    echo "{$user->id}: {$user->name} ({$user->email}) - Role: {$user->role}" . PHP_EOL;
}
