<?php
// db_connect.php
// Koneksi ke database
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'online_bookstore';

$conn = new mysqli($host, $user, $password, $database);

// Periksa koneksi
if ($conn->connect_error) {
    die("Koneksi database gagal: " . $conn->connect_error);
}
?>