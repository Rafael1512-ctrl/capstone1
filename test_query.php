<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    $events = \App\Models\Event::where('status', 'published')->orderBy('date', 'asc')->get();
    echo "Query successful! Found " . $events->count() . " events." . PHP_EOL;
} catch (Exception $e) {
    echo "Query failed: " . $e->getMessage() . PHP_EOL;
}
