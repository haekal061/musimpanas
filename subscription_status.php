<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$today = date("Y-m-d");
$active = false;
$request_made = false;
$message = '';

// Cek status langganan
$sub_query = "SELECT * FROM subscriptions WHERE user_id = $user_id ORDER BY end_date DESC LIMIT 1";
$sub_result = $conn->query($sub_query);
if ($sub_result->num_rows > 0) {
    $subscription = $sub_result->fetch_assoc();
    $end_date = $subscription['end_date'];
    $active = $today <= $end_date;
}

// Cek request pending
$check = $conn->query("SELECT * FROM subscription_requests WHERE user_id = $user_id AND status = 'pending'");
if ($check->num_rows > 0) {
    $request_made = true;
}

// Proses form pengajuan langganan
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$active && !$request_made) {
    $description = $_POST['description'];
    $request_date = date('Y-m-d');

    if (isset($_FILES['proof']) && $_FILES['proof']['error'] === 0) {
        $filename = basename($_FILES['proof']['name']);
        $allowed_exts = ['jpg', 'jpeg', 'png', 'gif'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        if (!in_array($ext, $allowed_exts)) {
            $message = "File tidak valid. Hanya gambar (jpg, jpeg, png, gif).";
        } else {
            $target_dir = "uploads/";
            if (!file_exists($target_dir)) mkdir($target_dir, 0777, true);
            $target_file = $target_dir . time() . "_" . $filename;

            if (move_uploaded_file($_FILES['proof']['tmp_name'], $target_file)) {
                $stmt = $conn->prepare("INSERT INTO subscription_requests (user_id, request_date, description, proof_file) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("isss", $user_id, $request_date, $description, $target_file);
                if ($stmt->execute()) {
                    $message = "Permintaan langganan berhasil dikirim.";
                    $request_made = true;
                } else {
                    $message = "Gagal menyimpan data: " . $stmt->error;
                }
            } else {
                $message = "Upload bukti transfer gagal.";
            }
        }
    } else {
        $message = "Silakan unggah bukti transfer.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Status Langganan eBook</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet" />
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background: url('https://png.pngtree.com/background/20230527/original/pngtree-an-old-bookcase-in-a-library-picture-image_2760144.jpg') no-repeat center center/cover;
            margin: 0;
            padding: 0;
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
            margin-left: 10px;
        }
        .logout:hover {
            background-color: #c82333;
        }

        .container {
            max-width: 900px;
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            margin: 30px auto;
            box-shadow: 0 0 10px rgba(0,0,0,0.15);
        }

        .success-box {
            background-color: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 6px;
            border: 1px solid #c3e6cb;
            font-weight: bold;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 12px;
            border: 1px solid #ccc;
            text-align: left;
        }

        thead {
            background-color: #5c3d25;
            color: white;
        }

        button {
            padding: 10px 20px;
            background-color: #7a5230;
            color: #fff;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        button:hover {
            background-color:rgb(82, 54, 30);
        }

        .info {
            background: #e9ecef;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
        }

        .message {
            margin-top: 15px;
            color: green;
            font-weight: bold;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 15px;
            margin-top: 20px;
        }

        .form-field {
            display: flex;
            flex-direction: column;
        }

        textarea, input[type="file"] {
            padding: 10px;
            border-radius: 6px;
            border: 1px solid #ccc;
            font-size: 14px;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Status Langganan eBook</h1>
        <div class="user-info">
            <a href="logout.php" class="logout">Logout</a>
        </div>
    </div>

    <div class="navbar">
        <a href="index.php">Home</a>
        <a href="book_catalog.php">Katalog</a>
        <a href="subscription_status.php">Status Langganan</a>
        <a href="order_history.php">Riwayat</a>
    </div>

    <div class="container">
        <h2>Status Langganan Anda</h2>

        <?php if ($active): ?>
            <div class="success-box">
                Langganan aktif sampai tanggal: <?php echo $end_date; ?>
            </div>

            <h3>eBook Gratis Anda</h3>
            <table>
                <thead>
                    <tr>
                        <th>Judul</th>
                        <th>Deskripsi</th>
                        <th>Baca</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $ebooks = $conn->query("SELECT * FROM ebooks");
                    if ($ebooks->num_rows > 0):
                        while ($ebook = $ebooks->fetch_assoc()):
                    ?>
                    <tr>
                        <td><?php echo htmlspecialchars($ebook['title']); ?></td>
                        <td><?php echo htmlspecialchars($ebook['description']); ?></td>
                        <td>
                            <button onclick="openModal('view_ebook.php?file=<?php echo urlencode($ebook['file']); ?>')">Lihat eBook</button>
                        </td>
                    </tr>
                    <?php endwhile; else: ?>
                    <tr><td colspan="3">Belum ada eBook tersedia.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>

        <?php else: ?>
            <?php if ($request_made): ?>
                <p class="message">Permintaan langganan Anda sudah dikirim dan sedang menunggu verifikasi.</p>
            <?php else: ?>
                <div class="info">
                    <p><strong>Biaya Langganan:</strong> Rp50.000 / Bulan</p>
                    <p><strong>No. Rekening:</strong> 1234567890 (BANK DEMO)</p>
                </div>
                <form method="POST" enctype="multipart/form-data" class="form-group">
                    <div class="form-field">
                        <label for="description">Deskripsi Pembayaran</label>
                        <textarea id="description" name="description" required placeholder="Contoh: Sudah transfer Rp50.000 ke rekening bank DEMO."></textarea>
                    </div>

                    <div class="form-field">
                        <label for="proof">Upload Bukti Transfer</label>
                        <input type="file" id="proof" name="proof" accept="image/*" required>
                    </div>

                    <button type="submit">Kirim Permintaan</button>
                </form>
            <?php endif; ?>
        <?php endif; ?>

        <?php if (!empty($message)): ?>
            <p class="message"><?php echo $message; ?></p>
        <?php endif; ?>
    </div>

    <!-- Modal Viewer -->
    <div id="ebookModal" style="display:none; position:fixed; top:2%; left:1%; width:98%; height:95%; background:white; border:2px solid #444; box-shadow:0 0 20px rgba(0,0,0,0.3); z-index:1000;">
        <iframe id="ebookFrame" src="" style="width:100%; height:93%; border:none;"></iframe>
        <div style="text-align:right; padding:10px;">
            <button onclick="closeModal()" style="padding:8px 15px; background:#dc3545; color:white; border:none; border-radius:5px;">Tutup</button>
        </div>
    </div>

    <script>
    function openModal(file) {
        document.getElementById("ebookFrame").src = file + "#toolbar=0&navpanes=0&scrollbar=0&view=FitH";
        document.getElementById("ebookModal").style.display = "block";
    }

    function closeModal() {
        document.getElementById("ebookModal").style.display = "none";
        document.getElementById("ebookFrame").src = "";
    }

    document.addEventListener('contextmenu', event => event.preventDefault());
    document.onkeydown = function(e) {
        if (
            (e.ctrlKey && ['s', 'p', 'u'].includes(e.key.toLowerCase())) ||
            e.key === 'F12'
        ) {
            e.preventDefault();
            return false;
        }
    };
    </script>
</body>
</html>
