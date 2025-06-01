<?php
session_start();
include 'db_connect.php';

// Pastikan hanya admin yang bisa akses
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

// Periksa apakah ada user_id yang dikirim
if (isset($_GET['user_id'])) {
    $user_id = intval($_GET['user_id']);

    // Ubah status user menjadi aktif
    $query = "UPDATE users SET status = 'active' WHERE user_id = $user_id AND role = 'employee'";

    if ($conn->query($query)) {
        $_SESSION['message'] = "Akun karyawan berhasil diaktifkan.";
    } else {
        $_SESSION['message'] = "Gagal mengaktifkan akun karyawan.";
    }
}

$conn->close();
header('Location: manage_staff.php');
exit();