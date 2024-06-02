<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit();
}

if (isset($_POST['create_admin'])) {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $query = mysqli_prepare($conn, "INSERT INTO users (username, password, role) VALUES (?, ?, 'admin')");
    mysqli_stmt_bind_param($query, "ss", $username, $password);
    
    if (mysqli_stmt_execute($query)) {
        echo "<script>alert('Admin created successfully!');</script>";
    } else {
        echo "<script>alert('Error creating admin: " . mysqli_error($conn) . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Admin</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Create Admin</h1>
    <form method="post" action="create_admin.php">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        <button type="submit" name="create_admin">Create Admin</button>
    </form>
    <a href="admin_dashboard.php">Back to Admin Dashboard</a>
</body>
</html>
