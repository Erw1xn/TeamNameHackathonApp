<?php
error_reporting(E_ALL);
ini_set('display_errors', 0); 
header('Content-Type: application/json');
include 'db.php'; 

if (!$conn) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit;
}

try {
    $response = [
        'success' => true,
        'timeline' => [],
        'available_rooms' => [],
        'pending_assignments' => [],
        'unassigned_count' => 0
    ];

    // UPDATED QUERY: Matches your phpMyAdmin structure
    $query = "SELECT 
                a.day, 
                TIME_FORMAT(a.start_time, '%H:%i') as start_time, 
                TIME_FORMAT(a.end_time, '%H:%i') as end_time,
                r.room_name, 
                r.id as room_db_id,
                t.instructor_name,
                COALESCE(s.subj_code, 'TBA') as subject_display
              FROM assignments a
              LEFT JOIN rooms r ON a.room_id = r.id
              LEFT JOIN teachers t ON a.teacher_id = t.teacher_id -- Verified from your screenshot
              LEFT JOIN subjects s ON a.subject_id = s.id
              ORDER BY FIELD(a.day, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'), a.start_time ASC";

    $stmt = $conn->prepare($query);
    if (!$stmt) {
        throw new Exception("Query Prep Failed: " . $conn->error);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    $response['timeline'] = $result->fetch_all(MYSQLI_ASSOC);

    // Fetch Available Rooms
    $availQuery = "SELECT room_name FROM rooms WHERE id NOT IN (SELECT room_id FROM assignments WHERE room_id IS NOT NULL)";
    $resAvail = $conn->query($availQuery);
    $response['available_rooms'] = $resAvail->fetch_all(MYSQLI_ASSOC);
    $response['unassigned_count'] = count($response['available_rooms']);

    // Fetch Pending Subjects
    $resSubj = $conn->query("SELECT subj_code FROM subjects LIMIT 3");
    $response['pending_assignments'] = $resSubj->fetch_all(MYSQLI_ASSOC);

    echo json_encode($response);

} catch (Exception $e) {
    // This ensures that even on failure, the JS receives JSON, not an HTML error
    echo json_encode([
        'success' => false, 
        'message' => $e->getMessage()
    ]);
}
?>