<?php
include 'db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "DELETE FROM teachers WHERE id = $id";
    $conn->query($sql);
}
?>