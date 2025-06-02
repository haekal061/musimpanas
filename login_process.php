<?php
session_start();
include 'db_connect.php';

// Proses login
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Periksa apakah email terdaftar
    $query = "SELECT * FROM users WHERE email = '$email' AND status = 'active'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verifikasi password
        if (password_verify($password, $user['password'])) {
            // Simpan data pengguna ke sesi
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            // Redirect sesuai peran
            if ($user['role'] == 'admin') {
                header('Location: admin_dashboard.php');
            } elseif ($user['role'] == 'employee') {
                header('Location: dashboard_karyawan.php');
            } else {
                header('Location: index.php');
            }
            exit();
        } else {
            echo '<script>alert("Password salah!"); window.location.href="login.php";</script>';
        }
    } else {
        echo '<script>alert("Email tidak terdaftar atau akun tidak aktif!"); window.location.href="login.php";</script>';
    }
}

$conn->close();
?>
