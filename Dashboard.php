<?php
// On your Dashboard.php
include 'db_connect.php';

// Fetch assignments joined with teacher and room names
$query = "SELECT a.*, t.instructor_name, r.room_name 
          FROM assignments a
          JOIN teachers t ON a.teacher_id = t.id
          JOIN rooms r ON a.room_id = r.id";
$result = $conn->query($query);
$assignments = $result->fetch_all(MYSQLI_ASSOC);
?>