<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['admin', 'employee'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = $_POST['order_id'];
    $new_status = $_POST['status'];
    $conn->query("UPDATE orders SET status = '$new_status' WHERE order_id = $order_id");
}

$orders = $conn->query("SELECT o.*, u.username, b.title, b.price FROM orders o JOIN users u ON o.user_id = u.user_id JOIN books b ON o.book_id = b.book_id ORDER BY o.order_date DESC");
$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Pesanan</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f9f9f9;
            margin: 0;
        }
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
        .logout {
            background-color: #dc3545;
            color: #ffffff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            text-decoration: none;
        }
        .logout:hover {
            background-color: #c82333;
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
        .container {
            max-width: 1000px;
            margin: 40px auto;
            background: #fff;
            padding: 30px;
            border-radius: 10px;
        }
        .order-box {
            border: 1px solid #ccc;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
            background: #fefefe;
        }
        label {
            display: block;
            margin-top: 10px;
            font-weight: bold;
        }
        select, button {
            padding: 8px;
            margin-top: 5px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        button {
            background: #007bff;
            color: white;
            font-weight: bold;
            cursor: pointer;
        }
        button:hover {
            background: #0056b3;
        }
        img.proof {
            margin-top: 10px;
            max-width: 300px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }
        .filter-buttons {
            text-align: center;
            margin: 20px 0;
        }
        .filter-buttons button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            margin: 0 5px;
            font-weight: bold;
            border-radius: 5px;
            cursor: pointer;
        }
        .filter-buttons button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<div class="header">
    <h1>Kelola Pesanan</h1>
    <div class="user-info">
        <span>Welcome, <?= htmlspecialchars($username) ?>!</span>
        <a class="logout" href="logout.php">Logout</a>
    </div>
</div>

<div class="navbar-links">
    <a href="dashboard_karyawan.php">Dashboard karyawan</a>
    <a href="verify_orders.php">Verifikasi Pesanan</a>
    <a href="manage_subscriptions.php">Kelola Langganan</a>
    <a href="manage_ebooks.php">Kelola eBook</a>
    <a href="verify_subscriptions.php">Verifikasi Langganan</a>
    <a href="manage_orders.php">Kelola Pesanan</a>
</div>

<div class="filter-buttons">
    <button onclick="filterOrders('all')">Semua</button>
    <button onclick="filterOrders('pending')">Pending</button>
    <button onclick="filterOrders('confirmed')">Terkonfirmasi</button>
    <button onclick="filterOrders('shipped')">Dikirim</button>
    <button onclick="filterOrders('completed')">Selesai</button>
</div>

<div class="container">
    <h2>Kelola Pesanan Buku</h2>
    <?php if ($orders->num_rows > 0): ?>
        <?php while ($o = $orders->fetch_assoc()): $status = $o['status']; ?>
            <div class="order-box order-group" data-status="<?= htmlspecialchars($status) ?>">
                <p><strong>Pengguna:</strong> <?= htmlspecialchars($o['username']) ?></p>
                <p><strong>Judul Buku:</strong> <?= htmlspecialchars($o['title']) ?></p>
                <p><strong>Harga:</strong> Rp<?= number_format($o['price']) ?></p>
                <p><strong>Tanggal Pesanan:</strong> <?= htmlspecialchars($o['order_date']) ?></p>
                <p><strong>Status:</strong> <?= ucfirst(htmlspecialchars($status)) ?></p>
                <p><strong>Bukti Pembayaran:</strong><br>
                    <?php if (!empty($o['payment_proof'])): ?>
                        <img class="proof" src="<?= htmlspecialchars($o['payment_proof']) ?>" alt="Bukti Pembayaran">
                    <?php else: ?>
                        <em>Tidak ada bukti pembayaran</em>
                    <?php endif; ?>
                </p>
                <form method="POST">
                    <input type="hidden" name="order_id" value="<?= htmlspecialchars($o['order_id']) ?>">
                    <label>Ubah Status Pesanan:</label>
                    <select name="status" required>
                        <option value="pending" <?= $status === 'pending' ? 'selected' : '' ?>>Pending</option>
                        <option value="confirmed" <?= $status === 'confirmed' ? 'selected' : '' ?>>Terkonfirmasi</option>
                        <option value="shipped" <?= $status === 'shipped' ? 'selected' : '' ?>>Dikirim</option>
                        <option value="completed" <?= $status === 'completed' ? 'selected' : '' ?>>Selesai</option>
                    </select>
                    <button type="submit">Simpan</button>
                </form>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>Tidak ada pesanan.</p>
    <?php endif; ?>
</div>

<script>
function filterOrders(status) {
    const groups = document.querySelectorAll('.order-group');
    groups.forEach(group => {
        if (status === 'all' || group.dataset.status === status) {
            group.style.display = 'block';
        } else {
            group.style.display = 'none';
        }
    });
}
</script>

</body>
</html>

<?php $conn->close(); ?>