<?php
session_start();
include 'db_connect.php';

// Cek apakah user adalah admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

// Cek apakah ada user_id yang dikirim
if (isset($_GET['user_id'])) {
    $user_id = intval($_GET['user_id']);

    // Update status user menjadi nonaktif
    $query = "UPDATE users SET status = 'inactive' WHERE user_id = $user_id";

    if ($conn->query($query)) {
        $_SESSION['message'] = "Akun berhasil dinonaktifkan.";
    } else {
        $_SESSION['message'] = "Gagal menonaktifkan akun.";
    }
}

$conn->close();
header('Location: manage_users.php');
exit();