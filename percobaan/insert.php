<?php
$conn = mysqli_connect("localhost", "root", "", "perpustakaan");
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

$judul = $_POST['judul'];

$sql = "INSERT INTO buku (judul) VALUES ('$judul')";
if (mysqli_query($conn, $sql)) {
    echo "Berhasil menambahkan buku.";
} else {
    echo "Gagal: " . mysqli_error($conn);
}

mysqli_close($conn);
