<?php
session_start();
include 'db_connect.php';

// Proses registrasi
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validasi email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo '<script>alert("Format email tidak valid!"); window.location.href="register.php";</script>';
        exit();
    }

    // Periksa apakah email sudah terdaftar
    $check_email = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($check_email);
    if ($result->num_rows > 0) {
        echo '<script>alert("Email sudah terdaftar. Silakan gunakan email lain."); window.location.href="register.php";</script>';
        exit();
    }

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Simpan pengguna ke database
    $query = "INSERT INTO users (username, email, password, role, status) VALUES ('$username', '$email', '$hashed_password', 'user', 'active')";
    if ($conn->query($query) === TRUE) {
        // Simpan user_id ke sesi
        $_SESSION['user_id'] = $conn->insert_id;
        echo '<script>alert("Registrasi berhasil! Anda akan diarahkan ke halaman katalog."); window.location.href="login.php";</script>';
    } else {
        echo 'Error: ' . $conn->error;
    }
}

$conn->close();
?>
