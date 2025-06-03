<?php
session_start();
include 'db_connect.php';

// Periksa apakah pengguna sudah login sebagai admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit();
}

$username = $_SESSION['username'];

// Cek apakah ada pencarian
$keyword = isset($_GET['keyword']) ? $conn->real_escape_string($_GET['keyword']) : '';
if (!empty($keyword)) {
    $query = "SELECT * FROM books WHERE 
              title LIKE '%$keyword%' OR 
              author LIKE '%$keyword%' OR 
              publisher LIKE '%$keyword%'";
} else {
    $query = "SELECT * FROM books";
}

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Buku</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f9f9f9;
            margin: 0;
        }

        .header {
            background-color: #343a40;
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
        }

        .logout:hover {
            background-color: #c82333;
        }

        .nav-bar {
            background-color: #212529;
            display: flex;
            justify-content: center;
            padding: 10px 0;
        }

        .nav-bar a {
            color: white;
            margin: 0 15px;
            font-weight: bold;
            text-decoration: none;
        }

        .nav-bar a:hover {
            text-decoration: underline;
        }

        .container {
            max-width: 1000px;
            margin: 50px auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
        }

        .search-bar {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }

        .search-bar form {
            display: flex;
            max-width: 500px;
            width: 100%;
        }

        .search-bar input[type="text"] {
            flex: 1;
            padding: 10px;
            border: 1px solid #ccc;
            border-top-left-radius: 5px;
            border-bottom-left-radius: 5px;
        }

        .search-bar button {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-top-right-radius: 5px;
            border-bottom-right-radius: 5px;
            cursor: pointer;
            font-weight: bold;
        }

        .search-bar button:hover {
            background-color: #0056b3;
        }

        .add-book {
            background-color: #28a745;
            color: #ffffff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-bottom: 20px;
        }

        .add-book:hover {
            background-color: #218838;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 15px;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f8f9fa;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        .edit, .delete {
            padding: 5px 15px;
            border-radius: 5px;
            cursor: pointer;
            color: #ffffff;
        }

        .edit {
            background-color: #007bff;
        }

        .edit:hover {
            background-color: #0069d9;
        }

        .delete {
            background-color: #dc3545;
        }

        .delete:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>

    <!-- Header -->
    <div class="header">
        <h1>Kelola Buku</h1>
        <div class="user-info">
            <span>Welcome, <?= htmlspecialchars($username) ?>!</span>
            <a href="logout.php" class="logout">Logout</a>
        </div>
    </div>

    <!-- Navigation Bar -->
    <div class="nav-bar">
        <a href="admin_dashboard.php">Dasbor</a>
        <a href="manage_books.php">Kelola Buku</a>
        <a href="manage_users.php">Kelola Pelanggan</a>
        <a href="manage_staff.php">Kelola Karyawan</a>
    </div>

    

    <!-- Main Container -->
    <div class="container">

        <!-- Search Form -->
        <div class="search-bar">
            <form method="GET">
                <input type="text" name="keyword" placeholder="Cari judul, penulis, atau penerbit..." 
                       value="<?= htmlspecialchars($keyword) ?>">
                <button type="submit">Cari</button>
            </form>
        </div>

        <button class="add-book" onclick="location.href='add_book.php'">Add New Book</button>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Publisher</th>
                    <th>Price</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= $row['book_id']; ?></td>
                            <td><?= htmlspecialchars($row['title']); ?></td>
                            <td><?= htmlspecialchars($row['author']); ?></td>
                            <td><?= htmlspecialchars($row['publisher']); ?></td>
                            <td><?= number_format($row['price'], 0, ',', '.'); ?></td>
                            <td>
                                <button class="edit" onclick="location.href='edit_book.php?id=<?= $row['book_id'] ?>'">Edit</button>
                                <button class="delete" onclick="location.href='delete_book.php?book_id=<?= $row['book_id'] ?>'">Delete</button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" style="text-align: center;">Tidak ada data buku ditemukan.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</body>
</html>

<?php $conn->close(); ?>
