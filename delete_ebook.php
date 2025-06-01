<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['admin', 'employee'])) {
    header('Location: login.php');
    exit();
}

$ebook_id = $_GET['ebook_id'];

// Dapatkan nama file untuk dihapus dari folder
$get_file = $conn->query("SELECT file FROM ebooks WHERE ebook_id = $ebook_id");
if ($get_file->num_rows > 0) {
    $file_data = $get_file->fetch_assoc();
    $file_path = 'ebooks/' . $file_data['file'];

    if (file_exists($file_path)) {
        unlink($file_path); // hapus file dari folder
    }
}

$delete = $conn->query("DELETE FROM ebooks WHERE ebook_id = $ebook_id");

if ($delete) {
    echo "<script>alert('eBook berhasil dihapus!'); window.location.href='manage_ebooks.php';</script>";
} else {
    echo "Gagal menghapus data: " . $conn->error;
}
?>
