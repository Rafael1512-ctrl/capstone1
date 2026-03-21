<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
use App\Models\User;
$user = User::where('email', '2472024@maranatha.ac.id')->first();
echo "ID: {$user->user_id}\n";
echo "Role ID: {$user->role_id} (" . gettype($user->role_id) . ")\n";
echo "Role attribute: |" . $user->role . "|\n";
echo "isAdmin: " . ($user->isAdmin() ? 'true' : 'false') . "\n";
echo "Role check test: " . (in_array($user->role, ['admin']) ? 'PASS' : 'FAIL') . "\n";
