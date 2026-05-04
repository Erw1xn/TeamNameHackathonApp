<?php
// Detect kung localhost o online
if ($_SERVER['HTTP_HOST'] == 'localhost' || $_SERVER['HTTP_HOST'] == '127.0.0.1') {
    // Localhost settings (XAMPP)
    $host = "127.0.0.1";
    $username = "root";
    $password = "";
    $dbname = "schooldb";
    $port = 3307;
} else {
    // InfinityFree settings
    $host = "sql102.infinityfree.com";
    $username = "if0_41822986";
    $password = "020923Erwin";
    $dbname = "if0_41822986_schooldb";
    $port = 3306;
}

// Create connection
$conn = new mysqli($host, $username, $password, $dbname, $port);
$db = $conn;

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set charset to utf8mb4
$conn->set_charset("utf8mb4");
?>