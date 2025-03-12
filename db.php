<?php
$host = 'localhost';
$user = 'root'; // Default XAMPP MySQL user
$pass = ''; // Default XAMPP MySQL password
$db_name = 'focus_db2';
$conn = new mysqli($host, $user, $pass, $db_name);
if ($conn->connect_error) {
    die("Connection failed: " . htmlspecialchars($conn->connect_error));
}
$conn->set_charset('utf8mb4'); // Prevent encoding attacks
?>