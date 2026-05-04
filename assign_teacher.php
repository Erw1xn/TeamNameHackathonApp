<?php
header('Content-Type: application/json');
include 'db.php';

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['teacher_id']) || !isset($data['room_id'])) {
    echo json_encode(['success' => false, 'message' => 'Missing data']);
    exit;
}

try {
    // Insert new assignment or update existing
    $sql = "INSERT INTO assignments (teacher_id, room_id, day, start_time, end_time) 
            VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iisss", $data['teacher_id'], $data['room_id'], $data['day'], $data['start'], $data['end']);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        throw new Exception($stmt->error);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>