<?php
include 'db.php';

/**
 * Kunin ang expertise mula sa URL parameter.
 * Gagamit tayo ng trim() para masiguro na walang extra spaces 
 * na makakaapekto sa paghahanap.
 */
$expertise = isset($_GET['expertise']) ? trim($_GET['expertise']) : '';

// I-sanitize ang input para iwas SQL injection
$expertise = $conn->real_escape_string($expertise);

/**
 * SQL FIX:
 * 1. Inalis ang 'NOT IN (SELECT teacher_id FROM assignments)'. 
 *    Dahil dito, lalabas na ang lahat ng matching teachers gaya ni Michael Rodriguez.
 * 2. Ginagamit ang 'LIKE' para maging flexible ang matching sa database records.
 */
$sql = "SELECT * FROM teachers 
        WHERE expertise LIKE '%$expertise%'";

$result = $conn->query($sql);

$teachers = [];

if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        // Idagdag ang bawat teacher sa array
        $teachers[] = $row;
    }
}

/**
 * I-return ang data bilang JSON format para mabasa ng 
 * JavaScript function na viewDetails() sa iyong Subjects.html.
 */
header('Content-Type: application/json');
echo json_encode($teachers);

$conn->close();
?>