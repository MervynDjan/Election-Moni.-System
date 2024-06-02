<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit();
}

$agents_query = "SELECT * FROM users WHERE role = 'agent'";
$agents_result = mysqli_query($conn, $agents_query);

$stations_query = "SELECT * FROM polling_stations";
$stations_result = mysqli_query($conn, $stations_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Assign Agents to Polling Stations</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Assign Agents to Polling Stations</h1>
    <form method="post" action="assign_handler.php">
        <label for="agent">Agent:</label>
        <select id="agent" name="agent_id">
            <?php while ($agent = mysqli_fetch_assoc($agents_result)): ?>
                <option value="<?= $agent['id'] ?>"><?= $agent['username'] ?></option>
            <?php endwhile; ?>
        </select>
        <label for="polling_station">Polling Station:</label>
        <select id="polling_station" name="polling_station_id">
            <?php while ($station = mysqli_fetch_assoc($stations_result)): ?>
                <option value="<?= $station['id'] ?>"><?= $station['name'] ?></option>
            <?php endwhile; ?>
        </select>
        <button type="submit">Assign</button>
    </form>
</body>
</html>
