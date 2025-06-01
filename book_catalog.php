<?php
session_start();
include 'db_connect.php';

// Periksa apakah pengguna sudah login
$logged_in = isset($_SESSION['user_id']);
$username = $logged_in ? $_SESSION['username'] : '';

// Tangkap kata kunci pencarian
$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';

// Buat query berdasarkan pencarian
if ($search !== '') {
    $query = "SELECT * FROM books WHERE title LIKE '%$search%' OR author LIKE '%$search%' OR publisher LIKE '%$search%' ORDER BY title ASC";
} else {
    $query = "SELECT * FROM books ORDER BY title ASC";
}
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Katalog Buku</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Roboto', sans-serif; background-color: #f9f9f9; margin: 0; }
        .header { background-color: #007bff; color: #ffffff; padding: 20px; text-align: center; position: relative; }
        .header h1 { margin: 0; font-size: 36px; }
        .navbar { display: flex; justify-content: center; background-color: #0056b3; padding: 10px; }
        .navbar a { color: #ffffff; margin: 0 15px; text-decoration: none; font-weight: bold; }
        .navbar a:hover { text-decoration: underline; }
        .user-info { position: absolute; top: 20px; right: 20px; color: #ffffff; }
        .logout { background-color: #dc3545; color: #ffffff; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-weight: bold; }
        .logout:hover { background-color: #c82333; }

        .container { max-width: 1200px; margin: 20px auto; padding: 20px; background-color: #ffffff; border-radius: 10px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
        .search-bar { margin-bottom: 20px; text-align: center; }
        .search-bar input[type="text"] { padding: 10px; width: 300px; border: 1px solid #ccc; border-radius: 5px; }
        .search-bar button { padding: 10px 15px; margin-left: 10px; background: #007bff; color: #fff; border: none; border-radius: 5px; cursor: pointer; }
        .search-bar button:hover { background-color: #0056b3; }

        .book-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
        }

        .book-card {
            background-color: #f4f4f4;
            border-radius: 10px;
            padding: 15px;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .book-card img {
            width: 150px;
            height: 220px;
            border-radius: 5px;
            margin-bottom: 15px;
            object-fit: cover;
        }

        .book-card h3 { margin: 0; color: #555; }
        .book-card p { color: #777; font-size: 14px; margin: 5px 0; }
        .price { font-weight: bold; color: #007bff; margin: 10px 0; }
        button { padding: 10px 20px; background-color: #007bff; color: #fff; border: none; border-radius: 5px; cursor: pointer; font-weight: bold; }
        button:hover { background-color: #0056b3; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Katalog Buku</h1>
        <?php if ($logged_in): ?>
            <div class="user-info">
                <span>Welcome, <?php echo $username; ?>!</span>
                <a href="logout.php" class="logout">Logout</a>
            </div>
        <?php endif; ?>
    </div>
    
    <div class="navbar">
        <a href="index.php">Home</a>
        <a href="book_catalog.php">Katalog</a>
        <a href="subscription_status.php">Status Langganan</a>
        <a href="request_subscription.php">Ajukan Langganan</a>
        <a href="order_history.php">Riwayat Pesanan</a>
        <?php if (!$logged_in): ?>
            <a href="register.php">Register</a>
            <a href="login.php">Login</a>
        <?php endif; ?>
    </div>

    <div class="container">
        <div class="search-bar">
            <form method="GET">
                <input type="text" name="search" placeholder="Cari judul, penulis, atau penerbit..." value="<?= htmlspecialchars($search) ?>">
                <button type="submit">Cari</button>
            </form>
        </div>

        <?php if ($result->num_rows > 0): ?>
            <div class="book-grid">
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="book-card">
                        <img src="book_images/<?php echo htmlspecialchars($row['cover_image']); ?>" alt="<?php echo htmlspecialchars($row['title']); ?>">
                        <h3><?php echo $row['title']; ?></h3>
                        <p><strong>Penulis:</strong> <?php echo $row['author']; ?></p>
                        <p><strong>Penerbit:</strong> <?php echo $row['publisher']; ?></p>
                        <p class="price">Rp <?php echo number_format($row['price'], 0, ',', '.'); ?></p>
                        <p><strong>Deskripsi:</strong>
                            <?php
                                $desc = strip_tags($row['description']);
                                echo strlen($desc) > 20 ? substr($desc, 0, 20) . '...' : $desc;
                            ?>
                        </p>
                        <a href="book_details.php?book_id=<?php echo $row['book_id']; ?>"><button>Beli Sekarang</button></a>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <p style="text-align: center;">Buku tidak ditemukan.</p>
        <?php endif; ?>
    </div>
</body>
</html>

<?php $conn->close(); ?>