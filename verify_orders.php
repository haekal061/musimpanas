<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['admin', 'employee'])) {
    header("Location: login.php");
    exit();
}

// Proses update status pesanan
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = $_POST['order_id'];
    $new_status = $_POST['status'];

    $conn->query("UPDATE orders SET status = '$new_status' WHERE order_id = $order_id");
}

// Ambil daftar pesanan yang belum selesai
$orders = $conn->query("
    SELECT o.*, u.username, b.title, b.price 
    FROM orders o 
    JOIN users u ON o.user_id = u.user_id 
    JOIN books b ON o.book_id = b.book_id 
    WHERE o.status != 'completed' 
    ORDER BY o.order_date DESC
");
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Verifikasi Pesanan Buku</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f9f9f9; margin: 0;}
        .container { max-width: 1000px; margin: 20px auto; background: #fff; padding: 30px; border-radius: 10px; }
        h2 { margin-bottom: 20px; }
        .order-box { border: 1px solid #ccc; padding: 20px; margin-bottom: 20px; border-radius: 8px; background: #fefefe; }
        label { display: block; margin-top: 10px; font-weight: bold; }
        select, button { padding: 8px; margin-top: 5px; border-radius: 5px; border: 1px solid #ccc; }
        button { background: #007bff; color: white; font-weight: bold; cursor: pointer; }
        button:hover { background: #0056b3; }
        img.proof { margin-top: 10px; max-width: 300px; border-radius: 5px; border: 1px solid #ddd; }

        .navbar-title {
            background-color: #007bff;
            padding: 20px 0;
            text-align: center;
            color: white;
            font-size: 24px;
            font-weight: bold;
            position: relative;
        }
        .navbar-links {
            background-color: #0056b3;
            padding: 10px 0;
            text-align: center;
        }
        .navbar-links a {
            color: white;
            margin: 0 30px;
            text-decoration: none;
            font-weight: bold;
        }
        .logout-btn {
            position: absolute;
            top: 50%;
            right: 20px;
            transform: translateY(-50%);
            background-color: #dc3545;
            color: white;
            padding: 6px 12px;
            border: none;
            border-radius: 4px;
            font-weight: bold;
            font-size: 12px;
            text-decoration: none;
            cursor: pointer;
        }
        .logout-btn:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>
    <div class="navbar-title">
        Dashboard Karyawan
        <a href="logout.php" class="logout-btn">Logout</a>
    </div>
    <div class="navbar-links">
        <a href="dashboard_karyawan.php">Dashboard karyawan</a>
        <a href="verify_orders.php">Verifikasi Pesanan</a>
        <a href="manage_subscriptions.php">Kelola Langganan</a>
        <a href="manage_ebooks.php">Kelola eBook</a>
        <a href="verify_subscriptions.php">Verifikasi Langganan</a>
        <a href="manage_orders.php"> Kelola Pesanan </a>
    </div>
    <div class="container">
        <h2>Verifikasi Pesanan Buku</h2>

        <?php if (isset($orders) && $orders && $orders->num_rows > 0): ?>
            <?php while ($o = $orders->fetch_assoc()): ?>
                <div class="order-box">
                    <p><strong>Nama Pengguna:</strong> <?= htmlspecialchars($o['username']) ?></p>
                    <p><strong>Judul Buku:</strong> <?= htmlspecialchars($o['title']) ?></p>
                    <p><strong>Harga:</strong> Rp<?= number_format($o['price']) ?></p>
                    <p><strong>Tanggal Pesanan:</strong> <?= $o['order_date'] ?></p>
                    <p><strong>Status Saat Ini:</strong> <?= ucfirst($o['status']) ?></p>
                    <p><strong>Bukti Pembayaran:</strong><br>
                        <img class="proof" src="<?= htmlspecialchars($o['payment_proof']) ?>" alt="Bukti Transfer">
                    </p>

                    <form method="POST">
                        <input type="hidden" name="order_id" value="<?= $o['order_id'] ?>">
                        <label>Ubah Status Pesanan:</label>
                        <select name="status" required>
                            <option value="confirmed" <?= $o['status'] === 'confirmed' ? 'selected' : '' ?>>Terkonfirmasi</option>
                            <option value="shipped" <?= $o['status'] === 'shipped' ? 'selected' : '' ?>>Dikirim</option>
                            <option value="completed" <?= $o['status'] === 'completed' ? 'selected' : '' ?>>Selesai</option>
                        </select>
                        <button type="submit">Perbarui Status</button>
                    </form>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>Tidak ada pesanan yang menunggu verifikasi.</p>
        <?php endif; ?>
    </div>
</body>
</html>
