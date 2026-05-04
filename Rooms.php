<?php
include 'db.php';

// PHP logic to handle the database insertion
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['room_name'];
    $capacity = $_POST['capacity'];
    
    // Capabilities
    $ac = isset($_POST['has_ac']) ? 1 : 0;
    $projector = isset($_POST['has_projector']) ? 1 : 0;
    $lab = isset($_POST['is_lab']) ? 1 : 0;

    // Days Availability
    $mon = isset($_POST['day_mon']) ? 1 : 0;
    $tue = isset($_POST['day_tue']) ? 1 : 0;
    $wed = isset($_POST['day_wed']) ? 1 : 0;
    $thu = isset($_POST['day_thu']) ? 1 : 0;
    $fri = isset($_POST['day_fri']) ? 1 : 0;
    $sat = isset($_POST['day_sat']) ? 1 : 0;

    // Time Availability
    $start_time = !empty($_POST['start_time']) ? $_POST['start_time'] : null;
    $end_time = !empty($_POST['end_time']) ? $_POST['end_time'] : null;

    // Prepared statement for security
    $stmt = $conn->prepare("INSERT INTO rooms (room_name, capacity, has_ac, has_projector, is_lab, day_mon, day_tue, day_wed, day_thu, day_fri, day_sat, start_time, end_time) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("siiiiiiiiisss", $name, $capacity, $ac, $projector, $lab, $mon, $tue, $wed, $thu, $fri, $sat, $start_time, $end_time);

    if ($stmt->execute()) {
        http_response_code(200); 
        echo "Success";
    } else {
        http_response_code(500); 
        echo "Error: " . $stmt->error;
    }
    
    $stmt->close();
    $conn->close();
    exit;
}
?>