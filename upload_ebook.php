<?php
session_start();
include 'db_connect.php';

// Hanya admin dan karyawan yang bisa mengakses
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['admin', 'employee'])) {
    header('Location: login.php');
    exit();
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];

    if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
        $filename = basename($_FILES['file']['name']);
        $target_dir = "ebooks/";
        $target_file = $target_dir . $filename;

        // Upload file
        if (move_uploaded_file($_FILES['file']['tmp_name'], $target_file)) {
            $query = "INSERT INTO ebooks (title, description, file) VALUES ('$title', '$description', '$filename')";
            if ($conn->query($query) === TRUE) {
                $message = "eBook berhasil diunggah!";
            } else {
                $message = "Gagal menyimpan ke database: " . $conn->error;
            }
        } else {
            $message = "Gagal mengunggah file.";
        }
    } else {
        $message = "File tidak valid atau terjadi kesalahan saat upload.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Upload eBook</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            background-color: #f5f5f5;
        }

        .header {
            background-color: #007bff;
            color: white;
            text-align: center;
            padding: 20px;
            position: relative;
        }

        .header h1 {
            margin: 0;
        }

        .logout-btn {
            position: absolute;
            top: 20px;
            right: 20px;
            background-color: #dc3545;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
        }

        .logout-btn:hover {
            background-color: #c82333;
        }

        .navbar {
            background-color: #0658b1;
            display: flex;
            justify-content: center;
            gap: 30px;
            padding: 12px 0;
        }

        .navbar a {
            color: white;
            font-weight: bold;
            text-decoration: none;
        }

        .navbar a:hover {
            text-decoration: underline;
        }

        .container {
            max-width: 600px;
            margin: 40px auto;
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        h2 {
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        .form-group input[type="file"] {
            padding: 5px;
        }

        button[type="submit"] {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }

        button[type="submit"]:hover {
            background-color: #0056b3;
        }

        .message {
            margin-top: 20px;
            font-weight: bold;
        }

        .back-btn {
            display: inline-block;
            margin-bottom: 20px;
            background-color: #6c757d;
            color: white;
            padding: 8px 16px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
        }

        .back-btn:hover {
            background-color: #5a6268;
        }
    </style>
</head>
<body>

    <!-- HEADER DAN NAVBAR -->
    <div class="header">
        <h1>Dashboard Karyawan</h1>
        <button class="logout-btn" onclick="location.href='logout.php'">Logout</button>
    </div>

    <div class="navbar">
        <a href="dashboard_karyawan.php">Dashboard karyawan</a>
        <a href="verify_orders.php">Verifikasi Pesanan</a>
        <a href="manage_subscriptions.php">Kelola Langganan</a>
        <a href="manage_ebooks.php">Kelola eBook</a>
        <a href="verify_subscriptions.php">Verifikasi Langganan</a>
        <a href="manage_orders.php"> Kelola Pesanan </a>
    </div>

    <!-- FORM UPLOAD -->
    <div class="container">
        <h2>Upload eBook Baru</h2>
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label>Judul eBook</label>
                <input type="text" name="title" required>
            </div>
            <div class="form-group">
                <label>Deskripsi</label>
                <textarea name="description" rows="4" required></textarea>
            </div>
            <div class="form-group">
                <label>File PDF</label>
                <input type="file" name="file" accept="application/pdf" required>
            </div>
            <button type="submit">Upload eBook</button>
        </form>
        <?php if (!empty($message)): ?>
            <p class="message"><?php echo $message; ?></p>
        <?php endif; ?>
    </div>

</body>
</html>
