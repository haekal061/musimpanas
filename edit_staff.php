<?php
session_start();
include 'db_connect.php';

// Periksa apakah pengguna sudah login sebagai admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit();
}

// Ambil data karyawan berdasarkan user_id
$staff_id = $_GET['user_id'];
$query = "SELECT * FROM users WHERE user_id = $staff_id AND role = 'employee'";
$result = $conn->query($query);
$staff = $result->fetch_assoc();

// Proses update karyawan
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Hash password jika diperbarui
    if (!empty($password)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $update_query = "UPDATE users SET username = '$username', email = '$email', password = '$hashed_password' WHERE user_id = $staff_id";
    } else {
        $update_query = "UPDATE users SET username = '$username', email = '$email' WHERE user_id = $staff_id";
    }

    if ($conn->query($update_query) === TRUE) {
        echo '<script>alert("Karyawan berhasil diperbarui!"); window.location.href="manage_staff.php";</script>';
    } else {
        echo 'Error: ' . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Staff</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Roboto', sans-serif; background-color: #f9f9f9; margin: 0; }
        .container { max-width: 600px; margin: 50px auto; padding: 20px; background-color: #ffffff; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.2); }
        .container h2 { color: #343a40; }
        .form-group { margin-bottom: 15px; }
        .form-group label { font-weight: bold; }
        .form-group input { width: 100%; padding: 10px; margin-top: 5px; border-radius: 5px; border: 1px solid #ddd; }
        .form-group button { background-color: #007bff; color: #ffffff; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; }
        .form-group button:hover { background-color: #0069d9; }
        .back-button { background-color: #6c757d; color: #ffffff; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; margin-bottom: 15px; }
        .back-button:hover { background-color: #5a6268; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Edit Staff</h2>
        <button class="back-button" onclick="location.href='manage_staff.php'">Back to Staff Management</button>
        <form method="post">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" value="<?php echo $staff['username']; ?>" required>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" value="<?php echo $staff['email']; ?>" required>
            </div>
            <div class="form-group">
                <label>Password (Optional)</label>
                <input type="password" name="password" placeholder="Leave blank to keep current password">
            </div>
            <div class="form-group">
                <button type="submit">Update Staff</button>
            </div>
        </form>
    </div>
</body>
</html>
