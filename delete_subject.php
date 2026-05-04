<?php
include 'db.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Ensure it's a number for security
    $conn->query("DELETE FROM subjects WHERE id = $id");
}
?>