<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit();
}

$submission_id = $_GET['id'];
$query = "UPDATE submissions SET status = 'approved' WHERE id = $submission_id";

if (mysqli_query($conn, $query)) {
    header('Location: view_submissions.php');
} else {
    echo "Error: " . mysqli_error($conn);
}
?>
