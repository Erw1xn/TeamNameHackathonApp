<?php
include 'db.php';

header('Content-Type: application/json');

$sql = "SELECT * FROM teachers ORDER BY teacher_id DESC";
$result = $conn->query($sql);

$teachers = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $teachers[] = $row;
    }
}

echo json_encode($teachers);
$conn->close();
?>