<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    
    <header>
        <?php include 'templates/header.php'; ?>
    </header>
    <nav>
        <?php include 'templates/navbar.php'; ?>
    </nav>
    <main>
    <h1>Admin Dashboard</h1>
    <ul>
        <li><a href="view_submissions.php">View Submissions</a></li>
        <li><a href="assign_agents.php">Assign Agents to Polling Stations</a></li>
        <li><a href="create_agent.php">Create Agent</a></li>
        <li><a href="create_admin.php">Create Admin</a></li>
    </ul>
    </main>
    <footer>
        <?php include 'templates/footer.php'; ?>
    </footer>

</body>
</html>
