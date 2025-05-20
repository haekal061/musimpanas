<?php
$conn = mysqli_connect("localhost", "root", "", "perpustakaan");
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

$id = $_POST['id'];

$sql = "DELETE FROM buku WHERE id = $id";
if (mysqli_query($conn, $sql)) {
    echo "Buku berhasil dihapus.";
} else {
    echo "Gagal: " . mysqli_error($conn);
}

mysqli_close($conn);
