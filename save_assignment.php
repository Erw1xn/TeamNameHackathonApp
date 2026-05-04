<?php
header('Content-Type: application/json');
require 'db.php'; // Ensure this file defines $conn as a PDO object

$input = json_decode(file_get_contents('php://input'), true);

try {
    // 1. Insert the assignment
    // Use $conn instead of $pdo if that is what's in your db.php
    $stmt = $conn->prepare("
        INSERT INTO assignments (teacher_id, room_id, subject_id, day, start_time, end_time)
        VALUES (?, ?, ?, ?, ?, ?)
    ");
    
    $stmt->execute([
        $input['teacher_id'],
        $input['room_id'],
        $input['subject_id'],
        $input['day'],
        $input['start_time'],
        $input['end_time']
    ]);

    // 2. Mark room as occupied
    $updateStmt = $conn->prepare("UPDATE rooms SET status = 'occupied' WHERE id = ?");
    $updateStmt->execute([$input['room_id']]);

    echo json_encode(['status' => 'success', 'message' => 'Assignment saved']);

} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>