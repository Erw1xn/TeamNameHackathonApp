<?php
include "db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = trim($_POST['first_name']);
    $last_name  = trim($_POST['last_name']);
    $email      = trim($_POST['email']);
    $password   = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    $role       = 'user'; 

    // 1. Validation: Empty fields
    if (empty($first_name) || empty($last_name) || empty($email) || empty($password) || empty($confirm_password)) {
        echo "<script>alert('Please fill in all fields!'); window.history.back();</script>";
        exit();
    }

    // 2. Validation: Password Match
    if ($password !== $confirm_password) {
        echo "<script>alert('Passwords do not match!'); window.history.back();</script>";
        exit();
    }

    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // 3. Check if email exists
    $check = $conn->prepare("SELECT id FROM faculty_users WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        echo "<script>alert('Email already exists!'); window.history.back();</script>";
        exit();
    }
    $check->close();

    // 4. Insert data (Ensure 'role' column exists in your DB)
    $stmt = $conn->prepare("INSERT INTO faculty_users (first_name, last_name, email, password_hash, role) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $first_name, $last_name, $email, $password_hash, $role);

    if ($stmt->execute()) {
        // Immediate redirect to login
        header("Location: Login.html");
        exit();
    } else {
        echo "Database Error: " . $stmt->error;
    }
    
    $stmt->close();
    $conn->close();
} else {
    header("Location: Signup.html");
    exit();
}
?>