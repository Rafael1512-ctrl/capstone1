<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

try {
    $tables = Schema::getTableListing();
    echo "Tables: " . implode(', ', $tables) . PHP_EOL . PHP_EOL;

    foreach ($tables as $table) {
        echo "Table: $table" . PHP_EOL;
        $columns = DB::select("DESCRIBE $table");
        foreach ($columns as $column) {
            echo " - {$column->Field}: {$column->Type}" . PHP_EOL;
        }
        echo PHP_EOL;
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
