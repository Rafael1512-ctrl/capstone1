<?php
$log = file_get_contents('storage/logs/laravel.log');
$lines = explode("\n", $log);
$last_50 = array_slice($lines, -50);
echo implode("\n", $last_50);
