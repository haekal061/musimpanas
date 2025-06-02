<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['admin', 'employee'])) {
    header('Location: login.php');
    exit();
}

$ebook_id = $_GET['ebook_id'];
$query = $conn->query("SELECT * FROM ebooks WHERE ebook_id = $ebook_id");
$ebook = $query->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];

    $update = "UPDATE ebooks SET title='$title', description='$description' WHERE ebook_id = $ebook_id";
    if ($conn->query($update)) {
        echo "<script>alert('eBook berhasil diperbarui!'); window.location.href='manage_ebooks.php';</script>";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit eBook</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 40px; background-color: #f2f2f2; }
        .container { max-width: 600px; margin: auto; background: white; padding: 20px; border-radius: 10px; }
        h2 { margin-bottom: 20px; }
        label { font-weight: bold; display: block; margin-top: 10px; }
        input[type="text"], textarea { width: 100%; padding: 10px; margin-top: 5px; border-radius: 5px; border: 1px solid #ccc; }
        button { margin-top: 20px; background: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; }
        button:hover { background: #0056b3; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Edit eBook</h2>
        <form method="POST">
            <label>Judul</label>
            <input type="text" name="title" value="<?php echo $ebook['title']; ?>" required>
            <label>Deskripsi</label>
            <textarea name="description" rows="4" required><?php echo $ebook['description']; ?></textarea>
            <button type="submit">Simpan Perubahan</button>
        </form>
    </div>
</body>
</html>
