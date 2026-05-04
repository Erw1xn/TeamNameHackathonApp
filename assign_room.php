<?php
include 'db_connect.php'; // Your database connection file

$teacher_id = $_POST['teacher_id'];
$room_id = $_POST['room_id'];

// Check if this room is already assigned to THIS teacher
$check = $conn->query("SELECT assigned_teacher_id FROM rooms WHERE id = $room_id");
$row = $check->fetch_assoc();

if ($row['assigned_teacher_id'] == $teacher_id) {
    // Already assigned to this teacher -> Unassign (Set to NULL)
    $conn->query("UPDATE rooms SET assigned_teacher_id = NULL WHERE id = $room_id");
    echo json_encode(["status" => "unassigned"]);
} else {
    // Not assigned -> Assign teacher
    $conn->query("UPDATE rooms SET assigned_teacher_id = $teacher_id WHERE id = $room_id");
    echo json_encode(["status" => "assigned"]);
}
?>