<?php
require_once 'db_config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    // Check if email already exists
    $check_stmt = $conn->prepare("SELECT user_email FROM users WHERE user_email = ?");
    $check_stmt->bind_param("s", $email);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    
    if ($check_result->num_rows > 0) {
        echo "<script>alert('Email already exists!'); window.location.href='login.html';</script>";
        exit();
    }
    
    // Insert new user
    $stmt = $conn->prepare("INSERT INTO users (user_name, user_email, user_pass) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $password);
    
    if ($stmt->execute()) {
        echo "<script>alert('Account created successfully! Please login.'); window.location.href='login.html';</script>";
    } else {
        echo "<script>alert('Error creating account!'); window.location.href='login.html';</script>";
    }
    
    $stmt->close();
    $check_stmt->close();
    $conn->close();
}
?>
