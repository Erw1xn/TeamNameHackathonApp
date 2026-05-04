<?php
include 'db.php';

// Fetch all subjects, newest first
$result = $conn->query("SELECT * FROM subjects ORDER BY id DESC");

$subjects = [];
while($row = $result->fetch_assoc()) {
    $subjects[] = $row;
}

// Send the data back to the HTML file in a format it understands
header('Content-Type: application/json');
echo json_encode($subjects);
?>