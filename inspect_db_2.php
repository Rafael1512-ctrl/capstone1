<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

function check_table($table) {
    echo "TABLE: $table\n";
    $cols = DB::select("SHOW COLUMNS FROM $table");
    foreach($cols as $col) {
        echo " - " . $col->Field . "\n";
    }
}

check_table('transaksi');
check_table('acara');
check_table('ticket');
