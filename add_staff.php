<?php
session_start();
include 'db_connect.php';

// Periksa apakah pengguna sudah login sebagai admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit();
}

$message = '';

// Proses penambahan karyawan
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $role = 'employee';
    $status = 'active';

    $check_query = "SELECT * FROM users WHERE email = '$email'";
    $check_result = $conn->query($check_query);

    if ($check_result->num_rows > 0) {
        $message = "Email sudah terdaftar. Gunakan email lain.";
    } elseif (strlen($password) < 8 || !preg_match('/[A-Z]/', $password) || !preg_match('/[0-9]/', $password)) {
        $message = "Password harus minimal 8 karakter, mengandung huruf besar dan angka.";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $query = "INSERT INTO users (username, email, password, role, status) VALUES ('$username', '$email', '$hashed_password', '$role', '$status')";
        if ($conn->query($query) === TRUE) {
            $message = "Karyawan berhasil ditambahkan!";
        } else {
            $message = "Gagal menambahkan: " . $conn->error;
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add New Staff</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            margin: 0;
        }
        .topbar {
            background-color: #2f353b;
            padding: 20px 40px;
            color: white;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .topbar .left,
        .topbar .right {
            width: 200px;
        }
        .topbar .center {
            flex-grow: 1;
            text-align: center;
        }
        .topbar .center h1 {
            margin: 0;
            font-size: 30px;
        }
        .topbar .right {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            gap: 10px;
            font-weight: bold;
        }
        .topbar .right a {
            background-color: #dc3545;
            color: white;
            padding: 8px 16px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
        }

        .navmenu {
            background-color: #1d1f21;
            padding: 10px 40px;
        }
        .nav-center {
            display: flex;
            justify-content: center;
            gap: 30px;
        }
        .nav-center a {
            color: white;
            text-decoration: none;
            font-weight: bold;
        }
        .nav-center a:hover {
            text-decoration: underline;
        }

        .container {
            max-width: 600px;
            margin: 40px auto 0 auto;
            background: #fff;
            padding: 30px;
            border-radius: 10px;
        }

        .form-group {
            margin-bottom: 15px;
        }
        label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }
        input {
            width: 100%;
            padding: 10px;
            margin-top: 3px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        small {
            display: block;
            margin-top: 5px;
            color: #777;
        }
        button {
            margin-top: 20px;
            padding: 10px 20px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
        }
        button:hover {
            background: #0056b3;
        }
        .message {
            margin-top: 15px;
            color: green;
            font-weight: bold;
        }
    </style>
</head>
<body>

<!-- Top Header -->
<div class="topbar">
    <div class="left"></div>
    <div class="center">
        <h1>Manage Staff</h1>
    </div>
    <div class="right">
        <span>Welcome,<?= htmlspecialchars($_SESSION['username'] ?? 'admin1') ?>!</span>
        <a href="logout.php">Logout</a>
    </div>
</div>

<!-- Navigation Menu -->
<div class="navmenu">
    <nav class="nav-center">
        <a href="admin_dashboard.php">Dashboard</a>
        <a href="manage_books.php">Kelola Buku</a>
        <a href="manage_users.php">Kelola Pelanggan</a>
        <a href="manage_staff.php">Kelola Karyawan</a>
    </div>

<!-- Form Container -->
<div class="container">
    <h2>Tambah Karyawan Baru</h2>
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
        <button type="submit">Tambah Staff</button>
    </form>
    <?php if (!empty($message)): ?>
        <p class="message"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>
</div>

</body>
</html>
