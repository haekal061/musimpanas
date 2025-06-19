<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    http_response_code(403);
    exit('Akses ditolak.');
}

if (!isset($_GET['file'])) {
    http_response_code(400);
    exit('File tidak ditemukan.');
}

$file = basename($_GET['file']);
$path = __DIR__ . '/ebooks/' . $file;

if (!file_exists($path)) {
    http_response_code(404);
    exit('File tidak tersedia.');
}

header('Content-Type: application/pdf');
header('Content-Disposition: inline; filename="' . $file . '"');
header('X-Content-Type-Options: nosniff');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Pragma: no-cache');

readfile($path);
exit();
?>
