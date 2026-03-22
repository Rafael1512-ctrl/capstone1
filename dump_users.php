<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
use App\Models\User;
foreach(User::all() as $user) {
    echo "ID: {$user->user_id}, Name: {$user->name}, Email: {$user->email}, Pass: {$user->pass}, Role: {$user->role_id}\n";
}
