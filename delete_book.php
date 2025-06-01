<?php
session_start();
include 'db_connect.php';

// Periksa apakah pengguna sudah login sebagai admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit();
}

// Hapus buku berdasarkan book_id
$book_id = $_GET['book_id'];
$query = "DELETE FROM books WHERE book_id = $book_id";
if ($conn->query($query) === TRUE) {
    echo '<script>alert("Buku berhasil dihapus!"); window.location.href="manage_books.php";</script>';
} else {
    echo 'Error: ' . $conn->error;
}

$conn->close();
?>
