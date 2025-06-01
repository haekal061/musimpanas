<?php
session_start();
include 'db_connect.php';

// Periksa apakah pengguna sudah login sebagai karyawan
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'employee') {
    header('Location: login.php');
    exit();
}

$username = $_SESSION['username'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Karyawan</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Roboto', sans-serif; background-color: #f9f9f9; margin: 0; }
        .header { background-color: #007bff; color: #ffffff; padding: 20px; text-align: center; position: relative; }
        .header h1 { margin: 0; font-size: 36px; }
        .container { max-width: 1200px; margin: 20px auto; padding: 20px; background-color: #ffffff; border-radius: 10px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
        .button { padding: 15px 30px; background-color: #0056b3; color: #ffffff; border: none; border-radius: 5px; cursor: pointer; font-weight: bold; text-align: center; margin-bottom: 20px; display: inline-block; }
        .button:hover { background-color:rgb(0, 9, 94); }
        .user-info { position: absolute; top: 20px; right: 20px; color: #ffffff; }
        .logout { background-color: #dc3545; color: #ffffff; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-weight: bold; }
        .logout:hover { background-color: #c82333; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Dashboard Karyawan</h1>
        <div class="user-info">
            <span>Welcome, <?php echo $username; ?> </span>
            <a href="logout.php" class="logout">Logout</a>
        </div>
    </div>

    <div class="container">
        <h2>Panel Karyawan</h2>
        <a href="verify_orders.php" class="button">Verifikasi Pesanan</a>
        <a href="manage_subscriptions.php" class="button">Kelola Langganan</a>
        <a href="manage_ebooks.php" class="button">Kelola eBook</a>
        <a href="verify_subscriptions.php" class="button">Verifikasi Langganan</a>
        <a href="manage_orders.php" class="button">Kelola Pesanan</a>

    </div>
</body>
</html>
