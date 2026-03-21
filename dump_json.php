<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
use App\Models\User;
$data = User::all()->toArray();
file_put_contents('users_data.json', json_encode($data, JSON_PRETTY_PRINT));
echo "Dumped " . count($data) . " users to users_data.json\n";
