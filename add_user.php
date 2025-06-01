<?php
include 'db_connect.php';

$username = 'karyawan2';
$email = 'karyawan2@example.com';
$password = password_hash('password123', PASSWORD_DEFAULT);
$role = 'employee';
$status = 'active';

// Cek apakah email sudah ada
$check_query = "SELECT * FROM users WHERE email = '$email'";
$check_result = $conn->query($check_query);

if ($check_result->num_rows > 0) {
    echo 'Email sudah terdaftar. Gunakan email lain.';
} else {
    $query = "INSERT INTO users (username, email, password, role, status) 
              VALUES ('$username', '$email', '$password', '$role', '$status')";
    if ($conn->query($query) === TRUE) {
        echo 'User berhasil ditambahkan!';
    } else {
        echo 'Error: ' . $conn->error;
    }
}

$conn->close();
?>
