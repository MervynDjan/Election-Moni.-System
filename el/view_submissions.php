<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit();
}

$query = "SELECT submissions.*, polling_stations.name AS polling_station_name, users.username AS agent_name
          FROM submissions
          JOIN polling_stations ON submissions.polling_station_id = polling_stations.id
          JOIN users ON submissions.agent_id = users.id";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Submissions</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Reset default margin and padding */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f0f2f5;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        /* .container {
            width: 90%;
            max-width: 1200px;
            margin: 20px;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        } */

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        .submission-table {
            width: 100%;
            border-collapse: collapse;
            background-color: #fff;
            border-radius: 8px;
            overflow: hidden;
        }

        .submission-table th,
        .submission-table td {
            padding: 12px 15px;
            border: 1px solid #ddd;
            text-align: center;
        }

        .submission-table th {
            background-color: #f4f4f4;
            font-weight: bold;
        }

        .submission-table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .submission-table tbody tr:hover {
            background-color: #f1f1f1;
        }

        .submission-table img,
        .submission-table video {
            max-width: 100px;
            max-height: 100px;
            border-radius: 4px;
            transition: transform 0.2s;
        }

        .submission-table img:hover,
        .submission-table video:hover {
            transform: scale(1.1);
        }

        .action-buttons {
            display: flex;
            gap: 10px;
            justify-content: center;
        }

        .action-buttons form {
            display: inline-block;
        }

        .action-buttons input[type="text"] {
            padding: 5px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-right: 5px;
        }

        .action-buttons button {
            padding: 5px 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .action-buttons button:hover {
            background-color: #e1e1e1;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Submissions</h1>
        <table class="submission-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Agent</th>
                    <th>Polling Station</th>
                    <th>WWW</th>
                    <th>XXX</th>
                    <th>YYY</th>
                    <th>ZZZ</th>
                    <th>Media Proof</th>
                    <th>Video Proof</th>
                    <th>Status</th>
                    <th>Rejection Reason</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr id="submission-<?= $row['id'] ?>">
                        <td><?= $row['id'] ?></td>
                        <td><?= $row['agent_name'] ?></td>
                        <td><?= $row['polling_station_name'] ?></td>
                        <td><?= $row['party_www'] ?></td>
                        <td><?= $row['party_xxx'] ?></td>
                        <td><?= $row['party_yyy'] ?></td>
                        <td><?= $row['party_zzz'] ?></td>
                        <td>
                            <?php 
                            $media_files = explode(',', $row['media_proof']);
                            foreach ($media_files as $file):
                                if ($file): ?>
                                    <img src="<?= $file ?>" alt="Media Proof">
                                <?php endif;
                            endforeach;
                            ?>
                        </td>
                        <td>
                            <?php if ($row['video_proof']): ?>
                                <video src="<?= $row['video_proof'] ?>" controls></video>
                            <?php endif; ?>
                        </td>
                        <td id="status-<?= $row['id'] ?>"><?= ucfirst($row['status']) ?></td>
                        <td id="reason-<?= $row['id'] ?>"><?= $row['rejection_reason'] ?></td>
                        <td class="action-buttons">
                            <form method="post" class="approve-reject-form">
                                <input type="hidden" name="submission_id" value="<?= $row['id'] ?>">
                                <input type="hidden" name="action" value="approve">
                                <button type="button" onclick="submitForm(this, 'approve')">Approve</button>
                            </form>
                            <form method="post" class="approve-reject-form">
                                <input type="hidden" name="submission_id" value="<?= $row['id'] ?>">
                                <input type="hidden" name="action" value="reject">
                                <input type="text" name="rejection_reason" placeholder="Reason for rejection" required>
                                <button type="button" onclick="submitForm(this, 'reject')">Reject</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        </div>
<script>
    function submitForm(button, action) {
        const form = button.closest('form');
        const formData = new FormData(form);
        const submissionId = formData.get('submission_id');

        if (action === 'reject' && !formData.get('rejection_reason')) {
            alert('Please provide a reason for rejection.');
            return;
        }

        fetch('approve_reject_submission.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                if (action === 'approve') {
                    document.getElementById(`status-${submissionId}`).innerText = 'Approved';
                    document.getElementById(`reason-${submissionId}`).innerText = '';
                } else if (action === 'reject') {
                    document.getElementById(`status-${submissionId}`).innerText = 'Rejected';
                    document.getElementById(`reason-${submissionId}`).innerText = formData.get('rejection_reason');
                }
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => console.error('Error:', error));
    }
</script>
</body>
</html>
