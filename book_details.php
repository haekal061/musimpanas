<?php
session_start();
include 'db_connect.php';

if (!isset($_GET['book_id'])) {
    echo "ID buku tidak ditemukan.";
    exit();
}

$book_id = (int)$_GET['book_id'];
$result = $conn->query("SELECT * FROM books WHERE book_id = $book_id");

if ($result->num_rows === 0) {
    echo "Buku tidak ditemukan.";
    exit();
}

$book = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Detail Buku</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
        }

        .header {
            background-color: #7a5230;
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
            font-size: 16px;
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

        .navbar {
            display: flex;
            justify-content: center;
            background-color: #5c3d25;
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

        .container {
            max-width: 800px;
            margin: 20px auto;
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            display: flex;
            gap: 30px;
        }

        .book-image img {
            max-width: 250px;
            border-radius: 6px;
            box-shadow: 0 0 5px rgba(0,0,0,0.1);
        }

        .book-info h2 {
            margin-bottom: 10px;
        }

        .book-info p {
            margin: 5px 0;
        }

        .book-info .price {
            color:rgb(107, 136, 168);
            font-size: 18px;
            font-weight: bold;
        }

        .buy-btn {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background: #28a745;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        .buy-btn:hover {
            background: #218838;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Katalog Buku</h1>
        <?php if (isset($_SESSION['username'])): ?>
            <div class="user-info">
                Welcome, <?= htmlspecialchars($_SESSION['username']) ?>!
                <a href="logout.php" class="logout">Logout</a>
            </div>
        <?php endif; ?>
    </div>

    <div class="navbar">
        <a href="index.php">Home</a>
        <a href="book_catalog.php">Katalog</a>
        <a href="subscription_status.php">Status Langganan</a>
        <a href="order_history.php">Riwayat Pesanan</a>
    </div>

    <div class="container">
        <div class="book-image">
            <img src="book_images/<?= htmlspecialchars($book['cover_image']) ?>" alt="<?= htmlspecialchars($book['title']) ?>">
        </div>
        <div class="book-info">
            <h2><?= htmlspecialchars($book['title']) ?></h2>
            <p><strong>Penulis:</strong> <?= htmlspecialchars($book['author']) ?></p>
            <p><strong>Penerbit:</strong> <?= htmlspecialchars($book['publisher']) ?></p>
            <p class="price">Rp<?= number_format($book['price']) ?></p>
            <p><strong>Deskripsi:</strong><br><?= nl2br(htmlspecialchars($book['description'])) ?></p>

            <a class="buy-btn" href="order_book.php?book_id=<?= $book['book_id'] ?>">Beli Sekarang</a>
        </div>
    </div>
</body>
</html>
