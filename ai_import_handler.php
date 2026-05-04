<?php
error_reporting(E_ALL);
ini_set('display_errors', 1); 
header('Content-Type: application/json');

include 'db.php'; 

$input = file_get_contents('php://input');
$data = json_decode($input, true);
$text = $data['pdfText'] ?? '';

// 1. CLEAN THE TEXT
$text = preg_replace('/[[:cntrl:]]/', '', $text); 
$text = mb_convert_encoding($text, 'UTF-8', 'UTF-8'); 

if (empty(trim($text))) {
    die(json_encode(["status" => "error", "message" => "Empty PDF text."]));
}

// 2. CONFIGURATION (Paste your free tier API key here)
$apiKey = ""; 
$apiUrl = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key=" . $apiKey;

$payload = [
    "contents" => [
        [
            "parts" => [
                ["text" => "Extract the following teacher data into a JSON array of objects. Keys: 'name', 'expertise', 'availability', 'start_time', 'end_time'. Data: " . $text]
            ]
        ]
    ],
    "generationConfig" => [
        "temperature" => 0.1,
        "responseMimeType" => "application/json" // Note: camelCase used for API 
    ]
];

// 3. CURL EXECUTION
$ch = curl_init($apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

// 4. DIAGNOSTIC
if ($httpCode !== 200) {
    $errorDetail = json_decode($response, true);
    die(json_encode([
        "status" => "error", 
        "message" => "Google API Error (HTTP $httpCode)",
        "error_type" => $errorDetail['error']['status'] ?? 'UNKNOWN',
        "reason" => $errorDetail['error']['message'] ?? 'Check your payload structure or text content.'
    ]));
}

$aiResult = json_decode($response, true);
$rawJson = $aiResult['candidates'][0]['content']['parts'][0]['text'] ?? '[]';
$extractedData = json_decode($rawJson, true);

// 5. DB INSERTION
$count = 0;
if (is_array($extractedData)) {
    $stmt = $conn->prepare("INSERT INTO teachers (instructor_name, ID, expertise, availability, start_time, end_time) VALUES (?, ?, ?, ?, ?, ?)");
    foreach ($extractedData as $row) {
        $name = $row['name'] ?? 'N/A';
        $exp  = $row['expertise'] ?? 'N/A';
        $avail = $row['availability'] ?? 'N/A';
        $startTime = $row['start_time'] ?? null;
        $endTime = $row['end_time'] ?? null;
        $tempId = "T-" . rand(1000, 9999); 

        $stmt->bind_param("ssssss", $name, $tempId, $exp, $avail, $startTime, $endTime);
        if ($stmt->execute()) { $count++; }
    }
    $stmt->close();
}

echo json_encode(["status" => "success", "inserted" => $count]);
?>