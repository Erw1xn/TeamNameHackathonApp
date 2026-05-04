<?php
include 'db.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $conn->query("DELETE FROM rooms WHERE id = $id");
    echo "Deleted";
}
$conn->close();
?>