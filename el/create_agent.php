<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit();
}

if (isset($_POST['create_agent'])) {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $query = mysqli_prepare($conn, "INSERT INTO users (username, password, role) VALUES (?, ?, 'agent')");
    mysqli_stmt_bind_param($query, "ss", $username, $password);
    
    if (mysqli_stmt_execute($query)) {
        echo "<script>alert('Agent created successfully!');</script>";
    } else {
        echo "<script>alert('Error creating agent: " . mysqli_error($conn) . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Agent</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Create Agent</h1>
    <form method="post" action="create_agent.php">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        <button type="submit" name="create_agent">Create Agent</button>
    </form>
    <a href="admin_dashboard.php">Back to Admin Dashboard</a>
</body>
</html>
