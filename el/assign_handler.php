<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $agent_id = $_POST['agent_id'];
    $polling_station_id = $_POST['polling_station_id'];

    $query = "INSERT INTO assignments (agent_id, polling_station_id) VALUES ('$agent_id', '$polling_station_id')";

    if (mysqli_query($conn, $query)) {
        header('Location: admin_dashboard.php');
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
