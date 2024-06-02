<?php
include 'db.php';
session_start();

$query = "SELECT * FROM  users where user_id = user_id";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'agent') {
    header('Location: login.php');
    exit();
}


$agent_id   = $_SESSION['user_id'];
$query      = mysqli_prepare($conn, "SELECT * FROM submissions WHERE agent_id = ?");
mysqli_stmt_bind_param($query, "i", $agent_id);
mysqli_stmt_execute($query);
$result = mysqli_stmt_get_result($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Agent Dashboard</title>
    <link rel="stylesheet" href="style.css">
    <script>
        function showAlert(message) {
            alert(message);
        }
    </script>
</head>
<body>
    
    <header>
        <?php include 'templates/header.php'; ?>
    </header>
    <nav>
        <?php include 'templates/navbar.php'; ?>
    </nav>
    <main>
    <h1>Agent Dashboard</h1>

    <form method="post" action="submission_handler.php" enctype="multipart/form-data">
        <label for="polling_station">Polling Station:</label>
        <select id="polling_station" name="polling_station">
            <?php
            $polling_stations_query = "
                SELECT assignments.id, polling_stations.name 
                FROM assignments 
                JOIN polling_stations ON assignments.polling_station_id = polling_stations.id 
                WHERE agent_id = $agent_id";
            $polling_stations_result = mysqli_query($conn, $polling_stations_query);
            
            if (!$polling_stations_result) {
                echo "<script>showAlert('Error fetching polling stations: " . mysqli_error($conn) . "');</script>";
            } else {
                while ($station = mysqli_fetch_assoc($polling_stations_result)) {
                    echo "<option value='{$station['id']}'>{$station['name']}</option>";
                }
            }
            ?>
        </select>
        <label for="party_www">WWW:</label>
        <input type="number" id="party_www" name="party_www" min="0">
        <label for="party_xxx">XXX:</label>
        <input type="number" id="party_xxx" name="party_xxx" min="0">
        <label for="party_yyy">YYY:</label>
        <input type="number" id="party_yyy" name="party_yyy" min="0">
        <label for="party_zzz">ZZZ:</label>
        <input type="number" id="party_zzz" name="party_zzz" min="0">
        <label for="media_proof">Media Proof:</label>
        <input type="file" id="media_proof" name="media_proof[]" multiple>
        <label for="video_proof">Video Proof:</label>
        <input type="file" id="video_proof" name="video_proof">
        <button type="submit">Submit</button>
    </form>
    <div>
        <h2>Your Submissions</h2>
        <ul>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <li>
                    Polling Station: <?= $row['polling_station_id'] ?> - Status: <?= ucfirst($row['status']) ?>
                    <?php if ($row['status'] == 'rejected'): ?>
                        <br>Rejection Reason: <?= $row['rejection_reason'] ?>
                    <?php endif; ?>
                    <?php if ($row['status'] != 'pending'): ?>
                        <a href="edit_submission.php?id=<?= $row['id'] ?>">Edit</a>
                    <?php endif; ?>
                </li>
            <?php endwhile; ?>
        </ul>
    </div>
    </main>
    <footer>
        <?php include 'templates/footer.php'; ?>
    </footer>

</body>
</html>
