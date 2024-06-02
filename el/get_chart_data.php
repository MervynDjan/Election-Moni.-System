<?php
include 'db.php';

$query = "SELECT 
            SUM(party_www) as www_votes, 
            SUM(party_xxx) as xxx_votes, 
            SUM(party_yyy) as yyy_votes, 
            SUM(party_zzz) as zzz_votes 
          FROM submissions WHERE status = 'approved'";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);

$data = [
    (int)$row['www_votes'],
    (int)$row['xxx_votes'],
    (int)$row['yyy_votes'],
    (int)$row['zzz_votes']
];

echo json_encode($data);
?>
