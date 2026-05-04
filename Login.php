<?php
session_start();
include "db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($email) || empty($password)) {
        echo "<script>alert('Please fill in all fields!'); window.location='Login.html';</script>";
        exit();
    }

    // Prepare statement to find user
    $stmt = $conn->prepare("SELECT * FROM faculty_users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        // Verify the hashed password
        if (password_verify($password, $row['password_hash'])) {
            
            // Store session data
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['name'] = $row['first_name'];
            $_SESSION['role'] = $row['role'];

            // Success: Redirect to Dashboard.html
            header("Location: Dashboard.html");
            exit();

        } else {
            echo "<script>alert('Wrong password!'); window.location='Login.html';</script>";
        }
    } else {
        echo "<script>alert('User not found!'); window.location='Login.html';</script>";
    }
    
    $stmt->close();
    $conn->close();
}
?>