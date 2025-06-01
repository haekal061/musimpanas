<?php
// Koneksi ke database menggunakan mysqli_connect (procedural)
$host = "localhost";
$user = "root";
$pass = "";
$db   = "perpustakaan";

$conn = mysqli_connect($host, $user, $pass, $db);

// Cek koneksi
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Query SELECT id dan judul dari tabel buku
$sql = "SELECT id, judul FROM buku";
$result = mysqli_query($conn, $sql);

$data = [];

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = [
            "id" => $row['id'],
            "judul" => $row['judul']
        ];
    }
}

// Tutup koneksi
mysqli_close($conn);

// Keluarkan data sebagai JSON
echo json_encode($data);