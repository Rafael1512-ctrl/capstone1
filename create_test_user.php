<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$db   = 'db_tixly';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) die("Conn fail: " . $conn->connect_error);

$u_id = 'U-00001';
$name = 'Budi Santoso';
$email = 'user@example.com';
$hashed = password_hash('user123', PASSWORD_BCRYPT);
$role_id = 3;

$stmt = $conn->prepare("INSERT INTO users (user_id, name, email, pass, role_id) VALUES (?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE name=VALUES(name), pass=VALUES(pass), role_id=VALUES(role_id)");
$stmt->bind_param("ssssi", $u_id, $name, $email, $hashed, $role_id);

if ($stmt->execute()) {
    echo "User Budi Santoso (user@example.com) created/updated successfully. Password: user123";
} else {
    echo "Error: " . $stmt->error;
}
$conn->close();
