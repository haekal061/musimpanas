<?php
session_start();
$logged_in = isset($_SESSION['user_id']);
$username = $logged_in ? $_SESSION['username'] : '';
$role = $logged_in ? $_SESSION['role'] : '';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Online Bookstore - Beranda</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Roboto', sans-serif;
            background: url('https://png.pngtree.com/background/20230527/original/pngtree-an-old-bookcase-in-a-library-picture-image_2760144.jpg') no-repeat center center fixed;
            background-size: cover;
        }

        .header {
            background-color: #1e2a38;
            color: #fff;
            padding: 20px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header h1 {
            font-size: 24px;
        }

        .navbar {
            background-color: #007bff;
            padding: 10px 40px;
            display: flex;
            justify-content: flex-start;
            gap: 15px;
        }

        .navbar a {
            color: #fff;
            text-decoration: none;
            padding: 8px 14px;
            font-weight: bold;
            border-radius: 4px;
        }

        .navbar a:hover {
            background-color: #0056b3;
        }

        .main {
            max-width: 1000px;
            margin: 40px auto;
            padding: 30px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            text-align: center;
        }

        h2 {
            font-size: 28px;
            margin-bottom: 20px;
            color: #333;
        }

        p {
            font-size: 16px;
            color: #555;
            margin-bottom: 20px;
        }

        ul.features {
            list-style: none;
            margin: 20px 0;
            padding: 0;
            text-align: left;
        }

        ul.features li {
            margin: 10px 0;
            font-size: 16px;
            padding-left: 25px;
            position: relative;
        }

        ul.features li::before {
            content: "✔";
            position: absolute;
            left: 0;
            color: green;
        }

        .buttons {
            margin-top: 30px;
            padding: 60px 20px;
            background: linear-gradient(rgba(0, 0, 0, 0.4), rgba(0,0,0,0.4)), url('https://png.pngtree.com/background/20230403/original/pngtree-pile-of-hard-covered-old-books-borders-on-blue-background-with-picture-image_2288051.jpg') no-repeat center center;
            background-size: cover;
            border-radius: 10px;
        }

        .buttons a {
            display: inline-block;
            background-color:rgb(246, 245, 250);
            color: #000;
            padding: 12px 25px;
            text-decoration: none;
            border-radius: 6px;
            margin: 10px;
            font-weight: bold;
        }

        .buttons a.logout {
            background-color: #dc3545;
            color: white;
        }

        .buttons a.logout:hover {
            background-color: #c82333;
        }

    </style>
</head>
<body>

<div class="header">
    <h1>Online Bookstore</h1>
    <?php if ($logged_in): ?>
        <div>Halo, <strong><?php echo htmlspecialchars($username); ?></strong></div>
    <?php endif; ?>
</div>

<div class="navbar">
    <a href="index.php">Beranda</a>
    <a href="book_catalog.php">Katalog</a>
    <?php if (!$logged_in): ?>
        <a href="register.php">Daftar</a>
        <a href="login.php">Masuk</a>
    <?php else: ?>
        <?php if ($role === 'user'): ?>
            <a href="subscription_status.php">Status Langganan</a>
            <a href="request_subscription.php">Ajukan Langganan</a>
            <a href="order_history.php">Riwayat Pesanan</a>
        <?php endif; ?>
        <a href="logout.php" class="logout">Logout</a>
    <?php endif; ?>
</div>

<div class="main">
    <h2>Selamat Datang di Online Bookstore</h2>
    <p>Temukan buku favoritmu kapan saja dan di mana saja. Nikmati fitur berlangganan untuk mendapatkan eBook gratis dan kemudahan pembelian buku langsung dari rumah.</p>

    <ul class="features">
        <li>Lihat dan beli buku langsung dari katalog</li>
        <li>Konfirmasi pembayaran mudah dan praktis</li>
        <li>Berlangganan eBook gratis selama 30 hari</li>
        <li>Baca eBook langsung tanpa harus mengunduh</li>
        <li>Riwayat pesanan yang mudah dipantau</li>
    </ul>

    <div class="buttons">
        <a href="book_catalog.php">Jelajahi Buku</a>
        <?php if (!$logged_in): ?>
            <a href="register.php">Gabung Sekarang</a>
        <?php endif; ?>
    </div>
</div>

</body>
</html>