<?php
include 'db.php';
header('Content-Type: application/json'); // Move this to the top

$result = $conn->query("SELECT * FROM rooms ORDER BY id DESC");
$rooms = [];

if ($result) {
    while($row = $result->fetch_assoc()) {
        $rooms[] = $row;
    }
}
echo json_encode($rooms);
$conn->close();
?>