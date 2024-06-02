<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $submission_id = $_POST['submission_id'];
    $action = $_POST['action'];
    
    if ($action == 'approve') {
        $query = "UPDATE submissions SET status = 'approved', rejection_reason = NULL WHERE id = $submission_id";
        if (mysqli_query($conn, $query)) {
            header('Location: view_submissions.php');
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    } elseif ($action == 'reject') {
        $rejection_reason = mysqli_real_escape_string($conn, $_POST['rejection_reason']);
        $query = "UPDATE submissions SET status = 'rejected', rejection_reason = '$rejection_reason' WHERE id = $submission_id";
        if (mysqli_query($conn, $query)) {
            header('Location: view_submissions.php');
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }
}
?>
