<?php
session_start();
include 'db_connect.php';

// Periksa apakah pengguna sudah login sebagai admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit();
}

// Hapus karyawan berdasarkan user_id
$staff_id = $_GET['user_id'];
$query = "DELETE FROM users WHERE user_id = $staff_id AND role = 'employee'";

if ($conn->query($query) === TRUE) {
    echo '<script>alert("Karyawan berhasil dihapus!"); window.location.href="manage_staff.php";</script>';
} else {
    echo 'Error: ' . $conn->error;
}

$conn->close();
?>
