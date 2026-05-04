<?php
ini_set('display_errors', 0);
error_reporting(E_ALL);
header('Content-Type: application/json');

include 'db.php';

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method');
    }

    $teacher_id = $_POST['teacher_id'] ?? null;
    $room_id = $_POST['room_id'] ?? null;
    $subject_id = (!empty($_POST['subject_id'])) ? $_POST['subject_id'] : null; 

    if (!$teacher_id) {
        throw new Exception('Missing Teacher ID');
    }

    // CHECK LOGIC: Mayroon na bang assignment ang teacher na ito?
    // Note: Inalis natin ang room_id sa check para mas flexible ang assignment mula sa Subjects page
    $checkQuery = "SELECT assignment_id, room_id FROM assignments WHERE teacher_id = ? LIMIT 1";
    $stmtCheck = $conn->prepare($checkQuery);
    $stmtCheck->bind_param("i", $teacher_id);
    $stmtCheck->execute();
    $result = $stmtCheck->get_result();
    $existingAssignment = $result->fetch_assoc();

    if ($existingAssignment) {
        // UPDATE LOGIC: Kung may record na, i-update ang subject_id
        // Kung galing sa Subjects page, maaaring null ang room_id sa POST, kaya gamitin ang dati niyang room_id
        $final_room_id = $room_id ?? $existingAssignment['room_id'];
        
        $updateSql = "UPDATE assignments SET subject_id = ?, room_id = ? WHERE teacher_id = ?";
        $stmtUpd = $conn->prepare($updateSql);
        $stmtUpd->bind_param("iii", $subject_id, $final_room_id, $teacher_id);
        
        if ($stmtUpd->execute()) {
            echo json_encode(['success' => true, 'status' => 'updated', 'message' => 'Subject assigned to instructor!']);
        } else {
            throw new Exception("Error updating assignment: " . $stmtUpd->error);
        }
    } else {
        // INSERT LOGIC: Kung wala pang record, kailangan ng room_id
        if (!$room_id) {
            throw new Exception('Instructor has no room assignment yet. Assign a room first.');
        }

        $teacherQuery = "SELECT availability, start_time, end_time FROM teachers WHERE teacher_id = ?";
        $stmtT = $conn->prepare($teacherQuery);
        $stmtT->bind_param("i", $teacher_id);
        $stmtT->execute();
        $teacherData = $stmtT->get_result()->fetch_assoc();

        if (!$teacherData) {
            throw new Exception('Teacher record not found');
        }

        $day = !empty($teacherData['availability']) ? explode(',', $teacherData['availability'])[0] : 'TBA'; 
        $start = $teacherData['start_time'];
        $end = $teacherData['end_time'];

        $sql = "INSERT INTO assignments (teacher_id, room_id, subject_id, day, start_time, end_time) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iiisss", $teacher_id, $room_id, $subject_id, $day, $start, $end);

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'status' => 'assigned', 'message' => 'Instructor and Subject assigned!']);
        } else {
            throw new Exception("Database Error: " . $stmt->error);
        }
    }

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>