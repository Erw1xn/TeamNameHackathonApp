<?php
include 'db.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $instructor_name = $_POST['instructor_name'] ?? '';
    $ID = $_POST['ID'] ?? ''; 
    $expertise = $_POST['expertise'] ?? 'Not Specified';
    $start_time = $_POST['start_time'] ?? null;
    $end_time = $_POST['end_time'] ?? null;
    
    $availability = (isset($_POST['availability']) && is_array($_POST['availability'])) 
                    ? implode(', ', $_POST['availability']) 
                    : 'Not Specified';

    if (!empty($instructor_name) && !empty($ID)) {
        // Updated prepared statement to include start_time and end_time
        $stmt = $conn->prepare("INSERT INTO teachers (instructor_name, ID, expertise, availability, start_time, end_time) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $instructor_name, $ID, $expertise, $availability, $start_time, $end_time);

        if ($stmt->execute()) {
            echo "Success";
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
        exit;
    }
}
?>