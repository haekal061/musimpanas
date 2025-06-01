<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Escape input
    $title = $conn->real_escape_string($_POST['title']);
    $author = $conn->real_escape_string($_POST['author']);
    $publisher = $conn->real_escape_string($_POST['publisher']);
    $description = $conn->real_escape_string($_POST['description']);
    $price = (int)$_POST['price'];
    $cover_image = '';

    // Upload gambar
    if (isset($_FILES['cover']) && $_FILES['cover']['error'] === 0) {
        $upload_dir = 'book_images/';
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir);
        }

        $filename = time() . "_" . basename($_FILES['cover']['name']);
        $target_path = $upload_dir . $filename;

        if (move_uploaded_file($_FILES['cover']['tmp_name'], $target_path)) {
            $cover_image = $filename;

            // Simpan ke database
            $query = "INSERT INTO books (title, author, publisher, price, description, cover_image)
                      VALUES ('$title', '$author', '$publisher', $price, '$description', '$cover_image')";

            if ($conn->query($query) === TRUE) {
                $message = "Buku berhasil ditambahkan!";
            } else {
                $message = "Gagal menyimpan data: " . $conn->error;
            }
        } else {
            $message = "Upload gambar gagal.";
        }
    } else {
        $message = "Gambar buku wajib diunggah.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Tambah Buku</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; padding: 40px; }
        .container { max-width: 600px; margin: auto; background: #fff; padding: 30px; border-radius: 10px; }
        h2 { margin-bottom: 20px; }
        label { display: block; font-weight: bold; margin-top: 10px; }
        input, textarea { width: 100%; padding: 10px; border-radius: 5px; border: 1px solid #ccc; margin-top: 5px; }
        button { margin-top: 20px; padding: 10px 20px; background: #007bff; color: white; border: none; border-radius: 5px; }
        button:hover { background: #0056b3; }
        .message { margin-top: 15px; color: green; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Tambah Buku Baru</h2>
        <form method="POST" enctype="multipart/form-data">
            <label>Judul</label>
            <input type="text" name="title" required>

            <label>Penulis</label>
            <input type="text" name="author" required>

            <label>Penerbit</label>
            <input type="text" name="publisher" required>

            <label>Harga</label>
            <input type="number" name="price" required>

            <label>Deskripsi</label>
            <textarea name="description" rows="4" required></textarea>

            <label>Upload Gambar Buku</label>
            <input type="file" name="cover" accept="image/*" required>

            <button type="submit">Simpan Buku</button>
        </form>

        <?php if (!empty($message)): ?>
            <p class="message"><?= $message ?></p>
        <?php endif; ?>
    </div>
</body>
</html>