<?php
$conn = mysqli_connect('127.0.0.1', 'root', '', 'dbcoba');
$query = "INSERT INTO migrations (migration, batch) VALUES ('2026_03_21_143229_add_missing_columns_to_acara', 2)";
mysqli_query($conn, $query);
echo "Migration marked as complete\n";
mysqli_close($conn);
