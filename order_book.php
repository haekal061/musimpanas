<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['book_id'])) {
    echo "ID buku tidak ditemukan.";
    exit();
}

$book_id = $_GET['book_id'];
$user_id = $_SESSION['user_id'];
$message = "";

// Ambil detail buku
$book_result = $conn->query("SELECT * FROM books WHERE book_id = $book_id");
if ($book_result->num_rows === 0) {
    echo "Buku tidak ditemukan.";
    exit();
}
$book = $book_result->fetch_assoc();

// Proses pemesanan
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $description = $_POST['description'];
    $order_date = date('Y-m-d');
    $proof_file = '';

    if (isset($_FILES['proof']) && $_FILES['proof']['error'] === 0) {
        $upload_dir = "payment_proofs/";
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir);
        }

        $filename = time() . "_" . basename($_FILES['proof']['name']);
        $target_path = $upload_dir . $filename;

        if (move_uploaded_file($_FILES['proof']['tmp_name'], $target_path)) {
            $proof_file = $target_path;
        } else {
            $message = "Upload bukti pembayaran gagal.";
        }
    }

    if ($proof_file) {
        $query = "INSERT INTO orders (user_id, book_id, order_date, payment_proof, status) 
                  VALUES ($user_id, $book_id, '$order_date', '$proof_file', 'pending')";

        if ($conn->query($query) === TRUE) {
            $message = "Pesanan berhasil dikirim. Silakan tunggu verifikasi.";
        } else {
            $message = "Gagal menyimpan pesanan: " . $conn->error;
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Form Pemesanan Buku</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f5f5f5; padding: 40px; }
        .container { max-width: 600px; margin: auto; background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        h2 { margin-bottom: 20px; }
        label { display: block; margin-top: 10px; font-weight: bold; }
        input, textarea { width: 100%; padding: 10px; margin-top: 5px; border-radius: 5px; border: 1px solid #ccc; }
        textarea { resize: vertical; }
        button { margin-top: 20px; background: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 6px; cursor: pointer; }
        button:hover { background: #0056b3; }
        .info-box { background: #e9ecef; padding: 15px; border-radius: 6px; margin-bottom: 20px; }
        .message { margin-top: 20px; color: green; }
        a.back-button {
            display: inline-block;
            margin-bottom: 15px;
            padding: 8px 16px;
            background-color: #6c757d;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
        }
        a.back-button:hover {
            background-color: #5a6268;
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="book_catalog.php" class="back-button">← Keluar ke katalog buku</a>

        <h2>Pemesanan Buku: <?= htmlspecialchars($book['title']) ?></h2>

        <div class="info-box">
            <p><strong>Harga Buku:</strong> Rp<?= number_format($book['price']) ?></p>
            <p><strong>No. Rekening Tujuan:</strong> 1234567890 (BANK DEMO)</p>
            <p><strong>Biaya yang harus dibayar:</strong> sesuai harga buku di atas</p>
        </div>

        <form method="POST" enctype="multipart/form-data">
            <label>Deskripsi Pembayaran</label>
            <textarea name="description" required placeholder="Contoh: Sudah transfer Rp<?= number_format($book['price']) ?> ke rekening bank DEMO."></textarea>

            <label>Upload Bukti Transfer</label>
            <input type="file" name="proof" accept="image/*" required>

            <button type="submit">Kirim Pesanan</button>
        </form>

        <?php if (!empty($message)): ?>
            <p class="message"><?= $message ?></p>
        <?php endif; ?>
    </div>
</body>
</html>
