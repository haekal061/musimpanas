<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id'])) {
    echo "ID buku tidak ditemukan.";
    exit();
}

$book_id = (int)$_GET['id'];
$result = $conn->query("SELECT * FROM books WHERE book_id = $book_id");
if ($result->num_rows === 0) {
    echo "Buku tidak ditemukan.";
    exit();
}
$book = $result->fetch_assoc();
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $conn->real_escape_string($_POST['title']);
    $author = $conn->real_escape_string($_POST['author']);
    $publisher = $conn->real_escape_string($_POST['publisher']);
    $price = (int)$_POST['price'];
    $description = $conn->real_escape_string($_POST['description']);
    $cover_image = $book['cover_image']; // default: tetap pakai gambar lama

    // Cek apakah ada gambar baru diunggah
    if (isset($_FILES['cover']) && $_FILES['cover']['error'] === 0) {
        $folder = "book_images/";
        if (!file_exists($folder)) {
            mkdir($folder);
        }

        $filename = time() . "_" . basename($_FILES['cover']['name']);
        $target = $folder . $filename;

        if (move_uploaded_file($_FILES['cover']['tmp_name'], $target)) {
            $cover_image = $filename;
        }
    }

    // Update database
    $update = "UPDATE books SET 
                title='$title',
                author='$author',
                publisher='$publisher',
                price=$price,
                description='$description',
                cover_image='$cover_image'
               WHERE book_id = $book_id";

    if ($conn->query($update) === TRUE) {
        $message = "Data buku berhasil diperbarui.";
        // Refresh data
        $book = $conn->query("SELECT * FROM books WHERE book_id = $book_id")->fetch_assoc();
    } else {
        $message = "Gagal memperbarui data: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Edit Buku</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; padding: 40px; }
        .container { max-width: 700px; margin: auto; background: #fff; padding: 30px; border-radius: 10px; }
        h2 { margin-bottom: 20px; }
        label { display: block; margin-top: 10px; font-weight: bold; }
        input, textarea { width: 100%; padding: 10px; border-radius: 5px; border: 1px solid #ccc; margin-top: 5px; }
        img.preview { max-width: 200px; margin-top: 10px; border-radius: 5px; box-shadow: 0 0 4px rgba(0,0,0,0.1); }
        button { margin-top: 20px; background: #28a745; color: white; padding: 10px 20px; border: none; border-radius: 5px; }
        button:hover { background: #218838; }
        .message { margin-top: 15px; color: green; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Edit Buku</h2>
        <form method="POST" enctype="multipart/form-data">
            <label>Judul</label>
            <input type="text" name="title" value="<?= htmlspecialchars($book['title']) ?>" required>

            <label>Penulis</label>
            <input type="text" name="author" value="<?= htmlspecialchars($book['author']) ?>" required>

            <label>Penerbit</label>
            <input type="text" name="publisher" value="<?= htmlspecialchars($book['publisher']) ?>" required>

            <label>Harga</label>
            <input type="number" name="price" value="<?= $book['price'] ?>" required>

            <label>Deskripsi</label>
            <textarea name="description" rows="4" required><?= htmlspecialchars($book['description']) ?></textarea>

            <label>Gambar Saat Ini</label><br>
            <img class="preview" src="book_images/<?= htmlspecialchars($book['cover_image']) ?>" alt="Cover Buku"><br>

            <label>Ganti Gambar (opsional)</label>
            <input type="file" name="cover" accept="image/*">

            <button type="submit">Perbarui Buku</button>
        </form>

        <?php if (!empty($message)): ?>
            <p class="message"><?= $message ?></p>
        <?php endif; ?>
    </div>
</body>
</html>