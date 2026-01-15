<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login page/login.html");
    exit();
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "portvuedb";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $user_name = $_SESSION['user_name'];
    
    // Get form data
    $website_link = isset($_POST['website_link']) ? trim($_POST['website_link']) : '';
    $tags = isset($_POST['tags']) ? $_POST['tags'] : '';
    $description = isset($_POST['description']) ? $_POST['description'] : '';
    
    // Validate website link
    if (empty($website_link)) {
        $_SESSION['upload_error'] = "Please enter a website link!";
        header("Location: interface.php");
        exit();
    }
    
    // Validate URL format
    if (!filter_var($website_link, FILTER_VALIDATE_URL)) {
        $_SESSION['upload_error'] = "Please enter a valid website URL!";
        header("Location: interface.php");
        exit();
    }
    
    $paddress = $website_link;
    $ratings = 0;
    
    // Insert into database
    $stmt = $conn->prepare("INSERT INTO portfolios (uid, uname, paddress, tags, description, ratings) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issssi", $user_id, $user_name, $paddress, $tags, $description, $ratings);
    
    if ($stmt->execute()) {
        $_SESSION['upload_success'] = "Upload Successful!";
    } else {
        $_SESSION['upload_error'] = "Failed to save portfolio: " . $stmt->error;
    }
    
    $stmt->close();
    $conn->close();
    header("Location: interface.php");
    exit();
} else {
    header("Location: interface.php");
    exit();
}
?>
