<?php
include 'db.php';
session_start();

$response = ['status' => 'error', 'message' => 'An error occurred.'];

if (isset($_SESSION['user_id']) && $_SESSION['role'] == 'admin' && isset($_POST['submission_id']) && isset($_POST['action'])) {
    $submission_id = intval($_POST['submission_id']);
    $action = $_POST['action'];

    if ($action == 'approve') {
        $query = "UPDATE submissions SET status = 'approved', rejection_reason = NULL WHERE id = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "i", $submission_id);
        if (mysqli_stmt_execute($stmt)) {
            $response = ['status' => 'success', 'message' => 'Submission approved and rejection reason cleared.'];
        } else {
            $response['message'] = 'Failed to approve submission.';
        }
    } elseif ($action == 'reject' && isset($_POST['rejection_reason'])) {
        $rejection_reason = $_POST['rejection_reason'];
        $query = "UPDATE submissions SET status = 'rejected', rejection_reason = ? WHERE id = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "si", $rejection_reason, $submission_id);
        if (mysqli_stmt_execute($stmt)) {
            $response = ['status' => 'success', 'message' => 'Submission rejected with reason.'];
        } else {
            $response['message'] = 'Failed to reject submission.';
        }
    }

    mysqli_stmt_close($stmt);
}

echo json_encode($response);
?>
