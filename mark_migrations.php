<?php
$mysqli = new mysqli('127.0.0.1', 'root', '', 'dbcoba');

// Mark all existing migrations as batch 1
$migrations = [
    '2014_10_12_000000_create_users_table',
    '2026_03_19_013055_create_events_table',
    '2026_03_19_013104_create_ticket_types_table',
    '2026_03_19_013111_create_orders_table',
    '2026_03_19_013117_create_order_items_table',
    '2026_03_19_013123_create_tickets_table',
    '2026_03_19_013128_create_payments_table',
    '2026_03_19_013134_create_waiting_list_table',
    '2026_03_19_013141_create_ticket_reservations_table',
    '2026_03_19_013148_create_notifications_table',
    '2026_03_19_013412_create_triggers_and_procedures',
    '2026_03_19_042547_create_personal_access_tokens_table',
    '2026_03_19_045121_create_sessions_table',
    '2026_03_19_045245_create_cache_table',
    '2026_03_19_045245_create_jobs_table',
    '2026_03_19_134832_fix_triggers',
    '2026_03_20_000001_create_event_categories_table',
];

foreach ($migrations as $migration) {
    $insert = "INSERT IGNORE INTO migrations (migration, batch) VALUES ('$migration', 1)";
    if ($mysqli->query($insert)) {
        echo "✓ Marked: $migration\n";
    } else {
        echo "✗ Error for $migration: " . $mysqli->error . "\n";
    }
}

$mysqli->close();
