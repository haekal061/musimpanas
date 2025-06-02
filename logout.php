<?php
session_start();

// Hapus semua sesi pengguna
session_unset();
session_destroy();

// Arahkan kembali ke halaman login
header('Location: login.php');
exit();
?>
