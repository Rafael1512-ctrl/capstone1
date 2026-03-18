<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

try {
    $roles = DB::select("SELECT * FROM role");
    foreach ($roles as $role) {
        $attrs = (array)$role;
        echo implode(', ', array_map(fn($k, $v) => "$k: $v", array_keys($attrs), $attrs)) . PHP_EOL;
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
