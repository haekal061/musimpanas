<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit();
}

$query = "SELECT * FROM users WHERE role = 'employee'";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Kelola Karyawan</title>
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
            margin: 30px auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 15px;
            border-bottom: 1px solid #ccc;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
        }

        .add, .edit, .activate, .deactivate, .delete {
            padding: 6px 12px;
            border-radius: 5px;
            color: white;
            border: none;
            cursor: pointer;
        }

        .add {
            background: #007bff;
        }

        .edit {
            background: #17a2b8;
        }

        .activate {
            background: #28a745;
        }

        .deactivate {
            background: #ffc107;
            color: black;
        }

        .delete {
            background: #dc3545;
        }
    </style>
</head>
<body>

<div class="header">
    <h1>Kelola Karyawan</h1>
    <div class="user-info">
        <span>Welcome, <?php echo $_SESSION['username']; ?>!</span>
        <a href="logout.php" class="logout">Logout</a>
    </div>
</div>

    <div class="nav-bar">
        <a href="admin_dashboard.php">Dasbor</a>
        <a href="manage_books.php">Kelola Buku</a>
        <a href="manage_users.php">Kelola Pelanggan</a>
        <a href="manage_staff.php">Kelola Karyawan</a>
    </div>

<div class="container">
    <button class="add" onclick="location.href='add_staff.php'">Tambahkan Karyawan Baru</button>
    <br><br>
    <table>
        <thead>
            <tr>
                <th>Employee ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['user_id']; ?></td>
                    <td><?php echo $row['username']; ?></td>
                    <td><?php echo $row['email']; ?></td>
                    <td><?php echo ucfirst($row['status']); ?></td>
                    <td class="action-buttons">
                        <button class="edit" onclick="location.href='edit_staff.php?user_id=<?php echo $row['user_id']; ?>'">Edit</button>
                        <?php if ($row['status'] == 'inactive'): ?>
                            <button class="activate" onclick="if(confirm('Aktifkan karyawan ini?')) location.href='activate_staff.php?user_id=<?php echo $row['user_id']; ?>'">Activate</button>
                        <?php else: ?>
                            <button class="deactivate" onclick="if(confirm('Nonaktifkan karyawan ini?')) location.href='deactivate_staff.php?user_id=<?php echo $row['user_id']; ?>'">Deactivate</button>
                        <?php endif; ?>
                        <button class="delete" onclick="if(confirm('Hapus karyawan ini?')) location.href='remove_staff.php?user_id=<?php echo $row['user_id']; ?>'">Remove</button>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

</body>
</html>

<?php $conn->close(); ?>
