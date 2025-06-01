<?php
session_start();
include 'db_connect.php';

// Periksa apakah pengguna sudah login sebagai admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit();
}

$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Roboto', sans-serif; background-color: #f9f9f9; margin: 0; }
        .header { background-color: #343a40; color: #ffffff; padding: 20px; text-align: center; position: relative; }
        .header h1 { margin: 0; font-size: 36px; }
        .navbar { display: flex; justify-content: center; background-color: #212529; padding: 10px; }
        .navbar a { color: #ffffff; margin: 0 15px; text-decoration: none; font-weight: bold; }
        .navbar a:hover { text-decoration: underline; }
        .user-info { position: absolute; top: 20px; right: 20px; color: #ffffff; }
        .logout { background-color: #dc3545; color: #ffffff; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-weight: bold; }
        .logout:hover { background-color: #c82333; }
        .dashboard { max-width: 1000px; margin: 50px auto; padding: 20px; background-color: #ffffff; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.2); }
        .dashboard h2 { color: #343a40; }
        .dashboard .card { background-color: #f8f9fa; padding: 20px; margin-bottom: 20px; border-radius: 10px; }
        .dashboard .card h3 { margin: 0 0 10px 0; }
        .dashboard .card p { margin: 0; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Admin Dashboard</h1>
        <div class="user-info">
            <span>Welcome, <?php echo $username; ?>!</span>
            <a href="logout.php" class="logout">Logout</a>
        </div>
    </div>

    <div class="navbar">
        <a href="admin_dashboard.php">Dashboard</a>
        <a href="manage_books.php">Manage Books</a>
        <a href="manage_users.php">Manage Users</a>
        <a href="manage_orders.php">Manage Orders</a>
        <a href="manage_staff.php">Manage Employees</a>


    </div>

    <div class="dashboard">
        <h2>Admin Control Panel</h2>

        <div class="card">
            <h3>Manage Books</h3>
            <p>Add, edit, or delete books in the catalog.</p>
        </div>

        <div class="card">
            <h3>Manage Users</h3>
            <p>View, activate, or deactivate user accounts.</p>
        </div>

        <div class="card">
            <h3>Manage Orders</h3>
            <p>View and manage customer orders.</p>
        </div>

        <div class="card">
            <h3>Manage Employees</h3>
            <p>Manage karyawan and their access rights.</p>
        </div>
    </div>
</body>
</html>
