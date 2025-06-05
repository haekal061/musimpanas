<?php
session_start();
include 'db_connect.php';

// Hanya untuk admin atau karyawan
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['admin', 'employee'])) {
    header("Location: login.php");
    exit();
}

// Jika tombol verifikasi diklik
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $request_id = $_POST['request_id'];
    $user_id = $_POST['user_id'];
    $action = $_POST['action'];

    if ($action === 'approve') {
        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'];

        // Tambahkan ke tabel subscriptions
        $insert = $conn->query("INSERT INTO subscriptions (user_id, start_date, end_date) VALUES ($user_id, '$start_date', '$end_date')");

        if ($insert) {
            $conn->query("UPDATE subscription_requests SET status='approved' WHERE id = $request_id");
        }
    } elseif ($action === 'reject') {
        $conn->query("UPDATE subscription_requests SET status='rejected' WHERE id = $request_id");
    }
}

// Ambil daftar permintaan langganan yang masih pending
$requests = $conn->query("
    SELECT r.*, u.username, u.email 
    FROM subscription_requests r 
    JOIN users u ON r.user_id = u.user_id 
    WHERE r.status = 'pending'
    ORDER BY r.request_date DESC
");

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Verifikasi Langganan</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; margin: 0; }
        .container { max-width: 1000px; margin: 20px auto; background: white; padding: 30px; border-radius: 10px; }
        h2 { margin-bottom: 20px; }
        .request-box { border: 1px solid #ccc; padding: 20px; margin-bottom: 20px; border-radius: 6px; background: #f9f9f9; }
        label { display: block; font-weight: bold; margin-top: 10px; }
        input[type="date"], textarea { width: 100%; padding: 8px; margin-top: 5px; border-radius: 5px; border: 1px solid #ccc; }
        .btn-group { margin-top: 15px; }
        .btn-group button {
            padding: 8px 16px; margin-right: 10px;
            border: none; border-radius: 5px; cursor: pointer; font-weight: bold;
        }
        .approve { background: #28a745; color: white; }
        .reject { background: #dc3545; color: white; }
        .approve:hover { background: #218838; }
        .reject:hover { background: #c82333; }
        img.proof { max-width: 250px; margin-top: 10px; border-radius: 5px; }

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
        <h2>Verifikasi Permintaan Langganan</h2>

        <?php if ($requests->num_rows > 0): ?>
            <?php while ($r = $requests->fetch_assoc()): ?>
                <div class="request-box">
                    <p><strong>Pengguna:</strong> <?= htmlspecialchars($r['username']) ?> (<?= $r['email'] ?>)</p>
                    <p><strong>Tanggal Permintaan:</strong> <?= $r['request_date'] ?></p>
                    <p><strong>Deskripsi:</strong><br><?= nl2br(htmlspecialchars($r['description'])) ?></p>
                    <p><strong>Bukti Transfer:</strong><br>
                        <img class="proof" src="<?= $r['proof_file'] ?>" alt="Bukti Transfer">
                    </p>

                    <form method="POST">
                        <input type="hidden" name="request_id" value="<?= $r['id'] ?>">
                        <input type="hidden" name="user_id" value="<?= $r['user_id'] ?>">

                        <label>Tanggal Mulai Langganan</label>
                        <input type="date" name="start_date" required>

                        <label>Tanggal Berakhir Langganan</label>
                        <input type="date" name="end_date" required>

                        <div class="btn-group">
                            <button type="submit" name="action" value="approve" class="approve">Setujui</button>
                            <button type="submit" name="action" value="reject" class="reject" onclick="return confirm('Tolak permintaan ini?')">Tolak</button>
                        </div>
                    </form>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>Tidak ada permintaan langganan yang perlu diverifikasi.</p>
        <?php endif; ?>
    </div>
</body>
</html>
