<?php
session_start();
include 'db_connect.php';

// Periksa apakah pengguna sudah login sebagai admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit();
}

// Proses penambahan karyawan
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $role = 'employee';
    $status = 'active';

    // Validasi email unik
    $check_query = "SELECT * FROM users WHERE email = '$email'";
    $check_result = $conn->query($check_query);

    if ($check_result->num_rows > 0) {
        echo '<script>alert("Email sudah terdaftar. Gunakan email lain."); window.location.href="add_staff.php";</script>';
    } elseif (strlen($password) < 8 || !preg_match('/[A-Z]/', $password) || !preg_match('/[0-9]/', $password)) {
        // Validasi password lebih aman
        echo '<script>alert("Password harus minimal 8 karakter, mengandung huruf besar dan angka."); window.location.href="add_staff.php";</script>';
    } else {
        // Hash password dan simpan ke database
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $query = "INSERT INTO users (username, email, password, role, status) VALUES ('$username', '$email', '$hashed_password', '$role', '$status')";
        if ($conn->query($query) === TRUE) {
            echo '<script>alert("Karyawan berhasil ditambahkan!"); window.location.href="manage_staff.php";</script>';
        } else {
            echo 'Error: ' . $conn->error;
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Staff</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Roboto', sans-serif; background-color: #f9f9f9; margin: 0; }
        .container { max-width: 600px; margin: 50px auto; padding: 20px; background-color: #ffffff; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.2); }
        .container h2 { color: #343a40; }
        .form-group { margin-bottom: 15px; }
        .form-group label { font-weight: bold; }
        .form-group input { width: 100%; padding: 10px; margin-top: 5px; border-radius: 5px; border: 1px solid #ddd; }
        .form-group button { background-color: #007bff; color: #ffffff; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; }
        .form-group button:hover { background-color: #0069d9; }
        .back-button { background-color: #6c757d; color: #ffffff; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; margin-bottom: 15px; }
        .back-button:hover { background-color: #5a6268; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Add New Staff</h2>
        <button class="back-button" onclick="location.href='manage_staff.php'">Back to Staff Management</button>
        <form method="post">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" required>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required>
                <small>Password harus minimal 8 karakter, mengandung huruf besar dan angka.</small>
            </div>
            <div class="form-group">
                <button type="submit">Add Staff</button>
            </div>
        </form>
    </div>
</body>
</html>
