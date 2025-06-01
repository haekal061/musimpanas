<?php
session_start();
include 'db_connect.php';

// Cek apakah user yang login adalah admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

// Cek apakah ada user_id yang dikirim melalui GET
if (isset($_GET['user_id'])) {
    $user_id = intval($_GET['user_id']);

    // Update status user menjadi aktif
    $query = "UPDATE users SET status = 'active' WHERE user_id = $user_id";

    if ($conn->query($query)) {
        $_SESSION['message'] = "Akun berhasil diaktifkan.";
    } else {
        $_SESSION['message'] = "Gagal mengaktifkan akun.";
    }
}

$conn->close();
header('Location: manage_users.php');
exit();