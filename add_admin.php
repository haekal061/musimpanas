<?php
include 'db_connect.php';

$username = 'admin1';
$email = 'admin1@example.com';
$password = password_hash('password123', PASSWORD_DEFAULT);
$role = 'admin';
$status = 'active';

$query = "INSERT INTO users (username, email, password, role, status) 
          VALUES ('$username', '$email', '$password', '$role', '$status')";

if ($conn->query($query) === TRUE) {
    echo 'Admin berhasil ditambahkan!';
} else {
    echo 'Error: ' . $conn->error;
}

$conn->close();
?>
