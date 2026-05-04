<?php
include "db.php";
header('Content-Type: application/json');

$current_time = date('H:i:s');

// Available Rooms
$res = $conn->query("SELECT COUNT(*) as count FROM rooms WHERE id NOT IN 
                    (SELECT room_id FROM schedules WHERE '$current_time' BETWEEN start_time AND end_time)");
$available = $res->fetch_assoc()['count'];

// Conflicts
$res = $conn->query("SELECT COUNT(*) as count FROM schedules s1 
                    JOIN schedules s2 ON s1.room_id = s2.room_id 
                    WHERE s1.id < s2.id AND s1.day = s2.day 
                    AND s1.start_time < s2.end_time AND s2.start_time < s1.end_time");
$conflicts = $res->fetch_assoc()['count'];

// Load
$res = $conn->query("SELECT SUM(TIMESTAMPDIFF(HOUR, start_time, end_time)) as total FROM schedules");
$load = $res->fetch_assoc()['total'] ?? 0;

echo json_encode([
    'available' => $available,
    'conflicts' => $conflicts,
    'total_load' => $load
]);