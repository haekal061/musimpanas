<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['admin', 'employee'])) {
    header('Location: login.php');
    exit();
}

$ebooks = $conn->query("SELECT * FROM ebooks ORDER BY ebook_id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manajemen eBook</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; background: #f5f5f5; }
        .container { max-width: 900px; margin: 20px auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        h2 { margin-bottom: 20px; }

        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; border-bottom: 1px solid #ccc; text-align: left; vertical-align: middle; }
        th { background: #007bff; color: white; }

        .action-buttons {
            display: flex;
            gap: 10px;
        }

        .action-buttons button {
            padding: 6px 12px;
            border: none;
            border-radius: 6px;
            color: white;
            cursor: pointer;
            font-weight: bold;
        }
        .edit {
            background-color: #28a745;
        }
        .edit:hover {
            background-color: #218838;
        }
        .delete {
            background-color: #dc3545;
        }
        .delete:hover {
            background-color: #c82333;
        }

        .add-btn {
            display: inline-block;
            margin-bottom: 20px;
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 6px;
        }
        .add-btn:hover {
            background-color: #0056b3;
        }

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
        .navbar-links a:hover {
            text-decoration: underline;
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
        <h2>Manajemen eBook</h2>
        <a class="add-btn" href="upload_ebook.php">+ Tambah eBook</a>
        <table>
            <thead>
                <tr>
                    <th>Judul</th>
                    <th>Deskripsi</th>
                    <th>File</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($ebook = $ebooks->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($ebook['title']); ?></td>
                        <td><?= htmlspecialchars($ebook['description']); ?></td>
                        <td><a href="ebooks/<?= $ebook['file']; ?>" target="_blank"><?= htmlspecialchars($ebook['file']); ?></a></td>
                        <td>
                            <div class="action-buttons">
                                <button class="edit" onclick="location.href='edit_ebook.php?ebook_id=<?= $ebook['ebook_id']; ?>'">Edit</button>
                                <button class="delete" onclick="if(confirm('Yakin ingin menghapus eBook ini?')) location.href='delete_ebook.php?ebook_id=<?= $ebook['ebook_id']; ?>'">Hapus</button>
                            </div>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
