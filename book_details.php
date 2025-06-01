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
        body { font-family: Arial, sans-serif; background: #f4f4f4; padding: 40px; }
        .container { max-width: 800px; margin: auto; background: #fff; padding: 30px; border-radius: 10px; display: flex; gap: 30px; }
        .book-image img { max-width: 250px; border-radius: 6px; box-shadow: 0 0 5px rgba(0,0,0,0.1); }
        .book-info h2 { margin-bottom: 10px; }
        .book-info p { margin: 5px 0; }
        .book-info .price { color: #007bff; font-size: 18px; font-weight: bold; }
        .buy-btn {
            display: inline-block; margin-top: 20px; padding: 10px 20px;
            background: #28a745; color: white; text-decoration: none; border-radius: 5px;
        }
        .buy-btn:hover { background: #218838; }
    </style>
</head>
<div style="text-align: right; margin-bottom: 20px;">
    <a href="book_catalog.php" style="
        display: inline-block;
        padding: 10px 20px;
        background-color: #007bff;
        color: white;
        text-decoration: none;
        border-radius: 5px;
        font-weight: bold;
    ">← Kembali ke Dashboard</a>
</div>
<body>
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