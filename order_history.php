<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$orders = $conn->query("
    SELECT o.*, b.title, b.price 
    FROM orders o 
    JOIN books b ON o.book_id = b.book_id 
    WHERE o.user_id = $user_id 
    ORDER BY o.order_date DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Riwayat Pesanan</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet" />
    <style>
        html, body {
  height: 100%;
  margin: 0;
  padding: 0;
}

body {
  font-family: 'Roboto', sans-serif;
  background: url('https://png.pngtree.com/background/20230527/original/pngtree-an-old-bookcase-in-a-library-picture-image_2760144.jpg') no-repeat center center/cover;
  background-attachment: fixed;
}

       
        .container { max-width: 1000px; margin: 20px auto; background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        h2 { color: #333; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 12px 15px; border-bottom: 1px solid #ddd; text-align: left; }
        th { background-color: #007bff; color: white; }
        .status { font-weight: bold; }
        .status.pending { color: orange; }
        .status.confirmed { color: green; }
        .status.shipped { color: blue; }
        .status.completed { color: gray; }
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
        .user-info {
            position: absolute;
            top: 20px;
            right: 20px;
            color: #ffffff;
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
        .logout {
            background-color: #dc3545;
            color: #ffffff;
            padding: 8px 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            text-decoration: none;
        }
        .logout:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Riwayat Pemesanan Buku</h1>
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
        <h2>Riwayat Pemesanan Buku</h2>

        <?php if ($orders->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Judul Buku</th>
                        <th>Harga</th>
                        <th>Tanggal Pesan</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($o = $orders->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($o['title']) ?></td>
                            <td>Rp<?= number_format($o['price']) ?></td>
                            <td><?= $o['order_date'] ?></td>
                            <td class="status <?= $o['status'] ?>"><?= ucfirst($o['status']) ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Anda belum memiliki riwayat pemesanan.</p>
        <?php endif; ?>
    </div>
</body>
</html>
