<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'agent') {
    header('Location: login.php');
    exit();
}

$submission_id = $_GET['id'];
$query = "SELECT * FROM submissions WHERE id = $submission_id";
$result = mysqli_query($conn, $query);
$submission = mysqli_fetch_assoc($result);

if (!$submission) {
    echo "Invalid submission ID.";
    exit();
}

$polling_station_query = "SELECT * FROM polling_stations";
$polling_stations_result = mysqli_query($conn, $polling_station_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Submission</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Edit Submission</h1>
    <form id="editSubmissionForm" method="post" action="submission_handler.php" enctype="multipart/form-data">
        <input type="hidden" name="submission_id" value="<?= $submission['id'] ?>">
        <label for="polling_station">Polling Station:</label>
        <select id="polling_station" name="polling_station">
            <?php while ($station = mysqli_fetch_assoc($polling_stations_result)): ?>
                <option value="<?= $station['id'] ?>" <?= $station['id'] == $submission['polling_station_id'] ? 'selected' : '' ?>>
                    <?= $station['name'] ?>
                </option>
            <?php endwhile; ?>
        </select>
        <label for="party_www">WWW:</label>
        <input type="number" id="party_www" name="party_www" min="0" value="<?= $submission['party_www'] ?>" required>
        <label for="party_xxx">XXX:</label>
        <input type="number" id="party_xxx" name="party_xxx" min="0" value="<?= $submission['party_xxx'] ?>" required>
        <label for="party_yyy">YYY:</label>
        <input type="number" id="party_yyy" name="party_yyy" min="0" value="<?= $submission['party_yyy'] ?>" required>
        <label for="party_zzz">ZZZ:</label>
        <input type="number" id="party_zzz" name="party_zzz" min="0" value="<?= $submission['party_zzz'] ?>" required>
        <label for="media_proof">Media Proof:</label>
        <input type="file" id="media_proof" name="media_proof[]" multiple>
        <div id="media_preview">
            <?php
            $media_files = explode(',', $submission['media_proof']);
            foreach ($media_files as $file):
                if ($file): ?>
                    <a href="<?= $file ?>" target="_blank">
                        <img src="<?= $file ?>" alt="Media Proof" style="width: 50px; height: auto;">
                    </a>
                <?php endif;
            endforeach;
            ?>
        </div>
        <label for="video_proof">Video Proof:</label>
        <input type="file" id="video_proof" name="video_proof">
        <div id="video_preview">
            <?php if ($submission['video_proof']): ?>
                <a href="<?= $submission['video_proof'] ?>" target="_blank">View Video</a>
            <?php endif; ?>
        </div>
        <button type="submit">Update Submission</button>
    </form>
</body>
</html>
