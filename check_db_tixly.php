<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

try {
    $database = DB::connection()->getDatabaseName();
    echo "Current Database: $database" . PHP_EOL . PHP_EOL;

    $columns = DB::select("DESCRIBE users");
    foreach ($columns as $column) {
        echo " - {$column->Field}: {$column->Type}" . PHP_EOL;
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
