<?php
session_start();
include 'db_connect.php';

// Periksa apakah pengguna sudah login sebagai admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit();
}

// Ambil data pengguna dari database
$query = "SELECT * FROM users WHERE role = 'user'";
$result = $conn->query($query);
$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
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

        .action-buttons {
            display: flex;
            gap: 10px;
        }

        .activate, .deactivate, .delete {
            padding: 5px 15px;
            border-radius: 5px;
            cursor: pointer;
            color: #ffffff;
        }

        .activate {
            background-color: #28a745;
        }

        .activate:hover {
            background-color: #218838;
        }

        .deactivate {
            background-color: #ffc107;
            color: #000;
        }

        .deactivate:hover {
            background-color: #e0a800;
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
        <h1>Manage Users</h1>
        <div class="user-info">
            <span>Welcome, <?= $username ?>!</span>
            <a href="logout.php" class="logout">Logout</a>
        </div>
    </div>

    <!-- Navigation Bar -->
    <div class="nav-bar">
        <a href="admin_dashboard.php">Dashboard</a>
        <a href="manage_books.php">Manage Books</a>
        <a href="manage_users.php">Manage Users</a>
        <a href="manage_orders.php">Manage Orders</a>
        <a href="manage_staff.php">Manage Employees</a>
    </div>

    <!-- Main Content -->
    <div class="container">
        <table>
            <thead>
                <tr>
                    <th>User ID</th>
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
                            <?php if ($row['status'] == 'inactive'): ?>
                                <button class="activate" onclick="location.href='activate_user.php?user_id=<?= $row['user_id']; ?>'">Activate</button>
                            <?php else: ?>
                                <button class="deactivate" onclick="location.href='deactivate_user.php?user_id=<?= $row['user_id']; ?>'">Deactivate</button>
                            <?php endif; ?>
                            <button class="delete" onclick="location.href='delete_user.php?user_id=<?= $row['user_id']; ?>'">Delete</button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

</body>
</html>

<?php $conn->close(); ?>
