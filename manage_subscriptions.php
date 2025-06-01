<?php
session_start();
include 'db_connect.php';

// Cek hak akses
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['admin', 'employee'])) {
    header("Location: login.php");
    exit();
}

// Ambil daftar user
$users = $conn->query("SELECT * FROM users WHERE role = 'user' ORDER BY username ASC");

// Tambah atau update langganan
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_POST['user_id'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    // Cek apakah sudah ada langganan sebelumnya
    $check = $conn->query("SELECT * FROM subscriptions WHERE user_id = $user_id");

    if ($check->num_rows > 0) {
        $conn->query("UPDATE subscriptions SET start_date='$start_date', end_date='$end_date' WHERE user_id = $user_id");
    } else {
        $conn->query("INSERT INTO subscriptions (user_id, start_date, end_date) VALUES ($user_id, '$start_date', '$end_date')");
    }

    echo "<script>alert('Langganan berhasil diperbarui!'); window.location.href='manage_subscriptions.php';</script>";
    exit();
}

// Ambil data langganan untuk ditampilkan
$subs = $conn->query("SELECT s.*, u.username, u.email FROM subscriptions s JOIN users u ON s.user_id = u.user_id ORDER BY end_date DESC");

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Manajemen Langganan</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; background: #f5f5f5; }
        .container { max-width: 900px; margin:20px auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        h2 { margin-bottom: 20px; }
        form { margin-bottom: 30px; }
        label { display: block; margin-top: 10px; font-weight: bold; }
        input, select { width: 100%; padding: 10px; margin-top: 5px; border-radius: 5px; border: 1px solid #ccc; }
        button { margin-top: 15px; padding: 10px 20px; background: #007bff; color: white; border: none; border-radius: 6px; cursor: pointer; }
        button:hover { background: #0056b3; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; border-bottom: 1px solid #ccc; text-align: left; }
        th { background: #007bff; color: white; }

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
    </div>
    <div class="container">
        <h2>Atur Langganan Pengguna</h2>
        <form method="POST">
            <label>Pilih Pengguna</label>
            <select name="user_id" required>
                <option value="">-- Pilih Pengguna --</option>
                <?php while($user = $users->fetch_assoc()): ?>
                    <option value="<?= $user['user_id'] ?>"><?= htmlspecialchars($user['username']) ?> (<?= $user['email'] ?>)</option>
                <?php endwhile; ?>
            </select>

            <label>Tanggal Mulai Langganan</label>
            <input type="date" name="start_date" required>

            <label>Tanggal Berakhir Langganan</label>
            <input type="date" name="end_date" required>

            <button type="submit">Simpan Langganan</button>
        </form>

        <h3>Daftar Langganan Aktif</h3>
        <table>
            <thead>
                <tr>
                    <th>Nama Pengguna</th>
                    <th>Email</th>
                    <th>Mulai</th>
                    <th>Berakhir</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $subs->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['username']) ?></td>
                        <td><?= htmlspecialchars($row['email']) ?></td>
                        <td><?= $row['start_date'] ?></td>
                        <td><?= $row['end_date'] ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
