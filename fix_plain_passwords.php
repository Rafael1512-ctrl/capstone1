<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$db   = 'db_tixly';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) die("Conn fail: " . $conn->connect_error);

$hashed = password_hash('user123', PASSWORD_BCRYPT);
$sql = "UPDATE users SET pass = ? WHERE pass = 'user123'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $hashed);

if ($stmt->execute()) {
    echo "Plaintext passwords updated to hashed versions successfully.";
} else {
    echo "Error: " . $stmt->error;
}
$conn->close();
