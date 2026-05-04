<?php
include "db.php"; // Connects to your database (port 3307)

$format = $_GET['format'] ?? 'csv';
$filename = "Schedule_Export_" . date('Y-m-d') . "." . $format;

// Fetch your schedule data
$query = "SELECT r.room_name, s.start_time, s.end_time, sub.subject_name 
          FROM schedules s 
          JOIN rooms r ON s.room_id = r.id 
          JOIN subjects sub ON s.subject_id = sub.id";
$result = $conn->query($query);

if ($format === 'csv') {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    
    $output = fopen('php://output', 'w');
    fputcsv($output, ['Room', 'Start Time', 'End Time', 'Subject']);
    
    while ($row = $result->fetch_assoc()) {
        fputcsv($output, $row);
    }
    fclose($output);
} else {
    // Basic PDF structure (For a full PDF, you'd usually use a library like FPDF or Dompdf)
    echo "PDF Export feature requires a PDF library like Dompdf. For now, try CSV!";
}
exit();