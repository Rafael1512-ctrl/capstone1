<?php
$mysqli = new mysqli('127.0.0.1', 'root', '', 'dbcoba');

$columns_needed = ['organizer_id', 'category_id', 'banner_url', 'status'];

foreach ($columns_needed as $col) {
    $query = "SHOW COLUMNS FROM acara LIKE '$col'";
    $result = $mysqli->query($query);
    if ($result->num_rows == 0) {
        echo "❌ Missing: $col\n";
    } else {
        echo "✓ Exists: $col\n";
    }
}

// Now add the missing columns
$alter_queries = [
    "ALTER TABLE acara ADD COLUMN organizer_id VARCHAR(7) NULL COMMENT 'FK to users table'",
    "ALTER TABLE acara ADD COLUMN category_id BIGINT NULL COMMENT 'FK to kategori_acara table'",
    "ALTER TABLE acara ADD COLUMN banner_url VARCHAR(255) NULL COMMENT 'URL path to banner image'",
    "ALTER TABLE acara ADD COLUMN status ENUM('draft', 'published', 'cancelled') DEFAULT 'draft' COMMENT 'Event status'"
];

echo "\n\nAdding missing columns...\n";
foreach ($alter_queries as $query) {
    if ($mysqli->query($query)) {
        echo "✓ " . explode(" ADD COLUMN ", $query)[1] . " added\n";
    } else {
        echo "✗ Error: " . $mysqli->error . "\n";
    }
}

$mysqli->close();
