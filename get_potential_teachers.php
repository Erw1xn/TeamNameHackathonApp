<?php
header('Content-Type: application/json');
error_reporting(0); // Prevents PHP errors from breaking the JSON format

// Ensure this matches your actual database connection filename
include 'db.php'; 

$room_id = isset($_GET['room_id']) ? intval($_GET['room_id']) : 0;

if (!$conn) {
    echo json_encode(["error" => "Database connection failed"]);
    exit;
}

// 1. Fetch Room Availability from the rooms table
$room_stmt = $conn->prepare("SELECT day_mon, day_tue, day_wed, day_thu, day_fri, day_sat FROM rooms WHERE id = ?");
$room_stmt->bind_param("i", $room_id);
$room_stmt->execute();
$room = $room_stmt->get_result()->fetch_assoc();

if (!$room) {
    echo json_encode([]);
    exit;
}

// Map the room's column values to an array of day labels
$room_days = [];
if ($room['day_mon'] == 1) $room_days[] = 'Mon';
if ($room['day_tue'] == 1) $room_days[] = 'Tue';
if ($room['day_wed'] == 1) $room_days[] = 'Wed';
if ($room['day_thu'] == 1) $room_days[] = 'Thu';
if ($room['day_fri'] == 1) $room_days[] = 'Fri';
if ($room['day_sat'] == 1) $room_days[] = 'Sat';

// 2. Fetch Teachers and check their availability string
$teacher_query = "SELECT instructor_name, expertise, availability FROM teachers"; 
$teachers_result = $conn->query($teacher_query);

$matches = [];
if ($teachers_result) {
    while ($row = $teachers_result->fetch_assoc()) {
        // Convert the string "Mon, Wed, Fri" into an array ["Mon", "Wed", "Fri"]
        $teacher_availability_string = $row['availability'] ?? '';
        $teacher_days = array_map('trim', explode(',', $teacher_availability_string));

        // Find overlapping days between the Room and Teacher
        $overlap = array_intersect($room_days, $teacher_days);

        if (!empty($overlap)) {
            // Determine if it's a full or partial match
            $status = (count($overlap) == count($room_days)) ? "Matches" : "Partial Match";
            $matches[] = [
                "name" => $row['instructor_name'],
                "expertise" => $row['expertise'],
                "match_text" => "$status (" . implode('/', $overlap) . ")"
            ];
        }
    }
}

echo json_encode($matches);
?>