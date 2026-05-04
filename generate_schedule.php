<?php
header('Content-Type: application/json');
ini_set('display_errors', 0);

try {
    if (!isset($_FILES['teacher_list'])) {
        throw new Exception("No file uploaded.");
    }

    $fileName = $_FILES['teacher_list']['tmp_name'];
    $results = [];

    if (($handle = fopen($fileName, "r")) !== FALSE) {
        // Skip header if your CSV has one
        fgetcsv($handle); 

        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            // Mapping: 0:Time, 1:Year/Section, 2:Subject, 3:Teacher, 4:Status
            $time    = htmlspecialchars($data[0] ?? '');
            $section = htmlspecialchars($data[1] ?? '');
            $subject = htmlspecialchars($data[2] ?? '');
            $teacher = htmlspecialchars($data[3] ?? '');
            $status  = htmlspecialchars($data[4] ?? 'Present');

            // AI Logic: Detect if it is a Break
            $isBreak = (stripos($subject, 'Lunch') !== false || $section == '---');

            $results[] = [
                "time"    => $time,
                "section" => $isBreak ? "---" : $section,
                "subject" => $subject,
                "teacher" => $isBreak ? "---" : $teacher,
                "status"  => $status,
                "isBreak" => $isBreak
            ];
        }
        fclose($handle);
    }
    echo json_encode($results);
} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
exit;