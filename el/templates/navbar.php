<?php
// session_start();
?>

<nav>
    <ul>
        <li><a href="index.php">Home</a></li>
        <?php if (isset($_SESSION['role'])): ?>
            <?php if ($_SESSION['role'] == 'admin'): ?>
                <li><a href="admin_dashboard.php">Admin Dashboard</a></li>
            <?php elseif ($_SESSION['role'] == 'agent'): ?>
                <li><a href="agent.php">Agent Dashboard</a></li>
            <?php endif; ?>
            <li><a href="logout.php">Logout</a></li>
        <?php else: ?>
            <li><a href="login.php">Login</a></li>
        <?php endif; ?>
    </ul>
</nav>
