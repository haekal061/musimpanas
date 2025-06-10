<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

if (isset($_GET['user_id'])) {
    $user_id = intval($_GET['user_id']);

    // Hapus user (langganan ikut terhapus otomatis karena ON DELETE CASCADE)
    $query = "DELETE FROM users WHERE user_id = $user_id";

    if ($conn->query($query)) {
        $_SESSION['message'] = "Akun pengguna berhasil dihapus.";
    } else {
        $_SESSION['message'] = "Gagal menghapus akun pengguna.";
    }
}

$conn->close();
header('Location: manage_users.php');
exit();