<?php
include 'db.php';

$username = 'admin';
$password = password_hash('water123', PASSWORD_BCRYPT);

$stmt = $conn->prepare("INSERT INTO admins (username, password) VALUES (?, ?)");
$stmt->bind_param("ss", $username, $password);
$stmt->execute();

echo "Admin user created securely!";
?>
