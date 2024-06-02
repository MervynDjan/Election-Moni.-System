<?php
include 'db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_POST['polling_station']) || !isset($_POST['party_www']) || !isset($_POST['party_xxx']) || !isset($_POST['party_yyy']) || !isset($_POST['party_zzz'])) {
        echo "<script>alert('All fields are required'); window.location='agent_dashboard.php';</script>";
        exit();
    }

    $agent_id = $_SESSION['user_id'];
    $polling_station_id = $_POST['polling_station'];
    $party_www = $_POST['party_www'];
    $party_xxx = $_POST['party_xxx'];
    $party_yyy = $_POST['party_yyy'];
    $party_zzz = $_POST['party_zzz'];

    // Fetch the number of registered voters for the selected polling station
    $query = "SELECT registered_voters FROM polling_stations WHERE id = $polling_station_id";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        echo "Error: " . mysqli_error($conn);
        exit();
    }

    $row = mysqli_fetch_assoc($result);
    if (!$row) {
        echo "Error: Polling station not found.";
        exit();
    }

    $registered_voters = $row['registered_voters'];

    // Check if the total votes exceed the registered voters
    $total_votes = $party_www + $party_xxx + $party_yyy + $party_zzz;
    if ($total_votes > $registered_voters) {
        echo "Error: Total votes exceed the number of registered voters.";
        exit();
    }

    $media_proof = '';
    if (!empty($_FILES['media_proof']['name'][0])) {
        $media_files = [];
        foreach ($_FILES['media_proof']['tmp_name'] as $key => $tmp_name) {
            $file_name = time() . "_" . $_FILES['media_proof']['name'][$key];
            $file_path = "images/" . $file_name;
            move_uploaded_file($tmp_name, $file_path);
            $media_files[] = $file_path;
        }
        $media_proof = implode(',', $media_files);
    }

    $video_proof = '';
    if (!empty($_FILES['video_proof']['name'])) {
        $video_name = time() . "_" . $_FILES['video_proof']['name'];
        $video_path = "videos/" . $video_name;
        move_uploaded_file($_FILES['video_proof']['tmp_name'], $video_path);
        $video_proof = $video_path;
    }

    if (isset($_POST['submission_id'])) {
        // Update existing submission
        $submission_id = $_POST['submission_id'];

        $update_query = "UPDATE submissions 
                         SET polling_station_id = '$polling_station_id', 
                             party_www = '$party_www', 
                             party_xxx = '$party_xxx', 
                             party_yyy = '$party_yyy', 
                             party_zzz = '$party_zzz', 
                             media_proof = IF('$media_proof' = '', media_proof, '$media_proof'), 
                             video_proof = IF('$video_proof' = '', video_proof, '$video_proof'), 
                             status = 'pending' 
                         WHERE id = '$submission_id'";

        if (mysqli_query($conn, $update_query)) {
            header('Location: agent_dashboard.php');
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    } else {
        // Insert new submission
        $query = "INSERT INTO submissions (agent_id, polling_station_id, party_www, party_xxx, party_yyy, party_zzz, media_proof, video_proof) 
                  VALUES ('$agent_id', '$polling_station_id', '$party_www', '$party_xxx', '$party_yyy', '$party_zzz', '$media_proof', '$video_proof')";

        if (mysqli_query($conn, $query)) {
            header('Location: agent_dashboard.php');
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }
}
?>