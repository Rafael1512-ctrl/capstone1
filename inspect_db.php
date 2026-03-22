<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

function dump_table($table) {
    echo "TABLE: $table\n";
    $cols = DB::select("DESCRIBE $table");
    foreach($cols as $col) {
        echo $col->Field . ",";
    }
    echo "\n";
}

dump_table('transaksi');
dump_table('acara');
dump_table('ticket');
dump_table('ticket_type');
