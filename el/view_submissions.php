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
</head>
<style>
        /* Reset default margin and padding */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    /* Apply spacing around elements */
    .container {
        max-width: 800px;
        margin: 0 auto;
        padding: 20px;
    }

    .submission-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    .submission-table th,
    .submission-table td {
        padding: 10px;
        border: 1px solid #ddd;
    }

    .submission-table th {
        background-color: #f2f2f2;
        font-weight: bold;
        text-align: left;
    }

    .submission-table tbody tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    .submission-table tbody tr:hover {
        background-color: #f2f2f2;
    }

    /* CSS for modal pop-up */
    .modal {
      display: none;
      position: fixed;
      z-index: 1;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      overflow: auto;
      background-color: rgba(0, 0, 0, 0.7);
    }

    .modal-content {
      margin: 5% auto;
      width: 80%;
      max-width: 800px;
      background-color: #fefefe;
      position: relative;
      padding: 20px;
    }

    .close {
      color: #aaaaaa;
      float: right;
      font-size: 28px;
      font-weight: bold;
    }

    .close:hover,
    .close:focus {
      color: #000;
      text-decoration: none;
      cursor: pointer;
    }

    #modal-img {
      display: none;
      max-width: 100%;
      height: auto;
    }

    #modal-video {
      display: none;
      max-width: 100%;
      height: auto;
    }


</style>
<body>
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
                <tr>
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
                                <a href="<?= $file ?>" target="_blank">
                                    <img src="<?= $file ?>" alt="Media Proof" style="width: 50px; height: auto;">
                                </a>
                            <?php endif;
                        endforeach;
                        ?>
                    </td>
                    <td>
                        <?php if ($row['video_proof']): ?>
                            <a href="<?= $row['video_proof'] ?>" target="_blank">View Video</a>
                        <?php endif; ?>
                    </td>
                    <td><?= ucfirst($row['status']) ?></td>
                    <td><?= $row['rejection_reason'] ?></td>
                    <td>
                        <?php if ($row['status'] == 'pending'): ?>
                            <form method="post" action="approve_reject_submission.php" style="display:inline;">
                                <input type="hidden" name="submission_id" value="<?= $row['id'] ?>">
                                <button type="submit" name="action" value="approve">Approve</button>
                            </form>
                            <form method="post" action="approve_reject_submission.php" style="display:inline;">
                                <input type="hidden" name="submission_id" value="<?= $row['id'] ?>">
                                <input type="text" name="rejection_reason" placeholder="Reason for rejection" required>
                                <button type="submit" name="action" value="reject">Reject</button>
                            </form>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <script>
        // JavaScript to handle modal pop-up for media and video
    var modal = document.getElementById("myModal");
    var modalImg = document.getElementById("modal-img");
    var modalVideo = document.getElementById("modal-video");
                            
    // Get the <span> element that closes the modal
    var span = document.getElementsByClassName("close")[0];
                            
    // Function to open modal with media
    function openModal(mediaType, mediaSrc) {
      if (mediaType === "image") {
        modalImg.src = mediaSrc;
        modalImg.style.display = "block";
        modalVideo.style.display = "none";
      } else if (mediaType === "video") {
        modalVideo.src = mediaSrc;
        modalVideo.style.display = "block";
        modalImg.style.display = "none";
      }
      modal.style.display = "block";
    }
    
    // When the user clicks on <span> (x), close the modal
    span.onclick = function() {
      modal.style.display = "none";
      modalImg.src = "";
      modalVideo.src = "";
    }
    
    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function(event) {
      if (event.target == modal) {
        modal.style.display = "none";
        modalImg.src = "";
        modalVideo.src = "";
      }
    }

    </script>
</body>
</html>
