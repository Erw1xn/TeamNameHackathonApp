<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $subj_code = $_POST['subj_code'];
    $units = $_POST['units'];
    $type = $_POST['type'];
    $expertise = $_POST['expertise'];
    
    $req_ac = isset($_POST['req_ac']) ? 1 : 0;
    $req_proj = isset($_POST['req_proj']) ? 1 : 0;

    $sql = "INSERT INTO subjects (subj_code, units, type, expertise, req_ac, req_proj) 
            VALUES ('$subj_code', $units, '$type', '$expertise', $req_ac, $req_proj)";

    if ($conn->query($sql) === TRUE) {
        header("Location: Subjects.html?success=1");
    } else {
        echo "Error: " . $conn->error;
    }
}
?>