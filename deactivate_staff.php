<?php
session_start();
include 'db_connect.php';

// Periksa apakah pengguna sudah login sebagai admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit();
}

// Nonaktifkan karyawan berdasarkan user_id
$staff_id = $_GET['user_id'];
$query = "UPDATE users SET status = 'inactive' WHERE user_id = $staff_id AND role = 'employee'";

if ($conn->query($query) === TRUE) {
    echo '<script>alert("Karyawan berhasil dinonaktifkan!"); window.location.href="manage_staff.php";</script>';
} else {
    echo 'Error: ' . $conn->error;
}

$conn->close();
?>
