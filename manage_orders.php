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

// Ambil semua pesanan
$orders = $conn->query("
    SELECT o.*, u.username, b.title, b.price 
    FROM orders o 
    JOIN users u ON o.user_id = u.user_id 
    JOIN books b ON o.book_id = b.book_id 
    ORDER BY o.order_date DESC
");
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
            background-color: #343a40;
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

        .nav-bar {
            background-color: #212529;
            display: flex;
            justify-content: center;
            padding: 10px 0;
        }

        .nav-bar a {
            color: white;
            margin: 0 15px;
            font-weight: bold;
            text-decoration: none;
        }

        .nav-bar a:hover {
            text-decoration: underline;
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
    </style>
</head>
<body>

    <!-- Header -->
    <div class="header">
        <h1>Kelola Pesanan</h1>
        <div class="user-info">
            <span>Welcome, <?= htmlspecialchars($username) ?>!</span>
            <a class="logout" href="logout.php">Logout</a>
        </div>
    </div>

    <!-- Navigation Bar -->
    <div class="nav-bar">
        <a href="admin_dashboard.php">Dashboard</a>
        <a href="manage_books.php">Manage Books</a>
        <a href="manage_users.php">Manage Users</a>
        <a href="manage_orders.php">Manage Orders</a>
        <a href="manage_staff.php">Manage Employees</a>
    </div>

    <!-- Main Content -->
    <div class="container">
        <h2>Kelola Pesanan Buku</h2>

        <?php if ($orders->num_rows > 0): ?>
            <?php while ($o = $orders->fetch_assoc()): ?>
                <div class="order-box">
                    <p><strong>Pengguna:</strong> <?= htmlspecialchars($o['username']) ?></p>
                    <p><strong>Judul Buku:</strong> <?= htmlspecialchars($o['title']) ?></p>
                    <p><strong>Harga:</strong> Rp<?= number_format($o['price']) ?></p>
                    <p><strong>Tanggal Pesanan:</strong> <?= htmlspecialchars($o['order_date']) ?></p>
                    <p><strong>Status:</strong> <?= ucfirst(htmlspecialchars($o['status'])) ?></p>
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
                            <option value="pending" <?= $o['status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                            <option value="confirmed" <?= $o['status'] === 'confirmed' ? 'selected' : '' ?>>Terkonfirmasi</option>
                            <option value="shipped" <?= $o['status'] === 'shipped' ? 'selected' : '' ?>>Dikirim</option>
                            <option value="completed" <?= $o['status'] === 'completed' ? 'selected' : '' ?>>Selesai</option>
                        </select>
                        <button type="submit">Simpan</button>
                    </form>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>Tidak ada pesanan.</p>
        <?php endif; ?>
    </div>

</body>
</html>

<?php $conn->close(); ?>
