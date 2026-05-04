<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // real_escape_string protects your database from breaking due to special characters
    $subj_code = $conn->real_escape_string($_POST['subj_code']);
    $units = intval($_POST['units']); // Ensures units is always a number
    $type = $conn->real_escape_string($_POST['type']);
    $expertise = $conn->real_escape_string($_POST['expertise']);
    
    $req_ac = isset($_POST['req_ac']) ? 1 : 0;
    $req_proj = isset($_POST['req_proj']) ? 1 : 0;

    $sql = "INSERT INTO subjects (subj_code, units, type, expertise, req_ac, req_proj) 
            VALUES ('$subj_code', $units, '$type', '$expertise', $req_ac, $req_proj)";

    if ($conn->query($sql) === TRUE) {
        header("Location: Subjects.html?success=1");
        exit(); // Always add exit() after a header redirect
    } else {
        echo "Error: " . $conn->error;
    }
}
?>