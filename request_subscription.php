<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$request_made = false;
$message = '';

// Cek apakah sudah pernah request
$check = $conn->query("SELECT * FROM subscription_requests WHERE user_id = $user_id AND status = 'pending'");
if ($check->num_rows > 0) {
    $request_made = true;
}

// Proses pengajuan
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$request_made) {
    $description = $_POST['description'];
    $request_date = date('Y-m-d');

    if (isset($_FILES['proof']) && $_FILES['proof']['error'] === 0) {
        $filename = basename($_FILES['proof']['name']);
        $target_dir = "uploads/";
        if (!file_exists($target_dir)) {
            mkdir($target_dir);
        }
        $target_file = $target_dir . time() . "_" . $filename;

        if (move_uploaded_file($_FILES['proof']['tmp_name'], $target_file)) {
            $query = "INSERT INTO subscription_requests (user_id, request_date, description, proof_file) 
                      VALUES ($user_id, '$request_date', '$description', '$target_file')";

            if ($conn->query($query) === TRUE) {
                $message = "Permintaan langganan berhasil dikirim.";
                $request_made = true;
            } else {
                $message = "Gagal menyimpan data: " . $conn->error;
            }
        } else {
            $message = "Upload bukti transfer gagal.";
        }
    } else {
        $message = "Silakan unggah bukti transfer.";
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Ajukan Langganan</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet" />
    <style>
        
        body { font-family: 'Roboto', sans-serif; background-color: #f5f5f5; margin: 0; padding: 0; 
            background: url('https://png.pngtree.com/background/20230527/original/pngtree-an-old-bookcase-in-a-library-picture-image_2760144.jpg') no-repeat center center/cover;}
        .container { max-width: 600px; background: #fff; padding: 30px; border-radius: 8px; margin: 20px auto; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        h2 { margin-bottom: 20px; color: #333; }
        label { display: block; margin-top: 10px; font-weight: bold; }
        input, textarea { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 6px; }
        button { margin-top: 20px; padding: 10px 20px; background-color: #007bff; color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: bold; }
        button:hover { background-color: #0056b3; }
        .info { background: #e9ecef; padding: 15px; border-radius: 6px; margin-bottom: 20px; }
        .message { margin-top: 15px; color: green; font-weight: bold; }

        .header {
            background-color: #007bff;
            color: #ffffff;
            padding: 20px;
            text-align: center;
            position: relative;
        }
        .header h1 {
            margin: 0;
            font-size: 36px;
        }
        .navbar {
            display: flex;
            justify-content: center;
            background-color: #0056b3;
            padding: 10px;
        }
        .navbar a {
            color: #ffffff;
            margin: 0 15px;
            text-decoration: none;
            font-weight: bold;
        }
        .navbar a:hover {
            text-decoration: underline;
        }
        .user-info {
            position: absolute;
            top: 20px;
            right: 20px;
            color: #ffffff;
        }
        .logout {
            background-color: #dc3545;
            color: #ffffff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            text-decoration: none;
            margin-left: 10px;
        }
        .logout:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Ajukan Langganan eBook</h1>
        <?php if (isset($_SESSION['user_id'])): ?>
            <div class="user-info">
                <a href="logout.php" class="logout">Logout</a>
            </div>
        <?php endif; ?>
    </div>

    <div class="navbar">
        <a href="index.php">Home</a>
        <a href="book_catalog.php">Katalog</a>
        <a href="subscription_status.php">Status Langganan</a>
        <a href="request_subscription.php">Ajukan Langganan</a>
        <a href="order_history.php">Riwayat Pesanan</a>
    </div>


    <div class="container">
        <h2>Ajukan Langganan eBook</h2>

        <?php if ($request_made): ?>
            <p class="message">Permintaan Anda sudah dikirim dan sedang menunggu verifikasi.</p>
        <?php else: ?>
            <div class="info">
                <p><strong>Biaya Langganan:</strong> Rp50.000 / bulan</p>
                <p><strong>No. Rekening:</strong> 1234567890 (BANK DEMO)</p>
            </div>
            <form method="POST" enctype="multipart/form-data">
                <label>Deskripsi Pembayaran</label>
                <textarea name="description" required placeholder="Contoh: Sudah transfer Rp50.000 ke rekening bank DEMO."></textarea>

                <label>Upload Bukti Transfer</label>
                <input type="file" name="proof" accept="image/*" required>

                <button type="submit">Kirim Permintaan</button>
            </form>
        <?php endif; ?>

        <?php if (!empty($message)): ?>
            <p class="message"><?php echo $message; ?></p>
        <?php endif; ?>
    </div>
</body>
</html>
