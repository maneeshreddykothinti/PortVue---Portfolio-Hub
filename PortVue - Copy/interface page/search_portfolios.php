<?php
session_start();

// Database configuration
$host = 'localhost';
$db_name = 'portvuedb';
$username = 'root';
$password = '';

// Create connection
$conn = new mysqli($host, $username, $password, $db_name);

// Check connection
if ($conn->connect_error) {
    die(json_encode(['error' => 'Connection failed: ' . $conn->connect_error]));
}

// Get search query
$search_query = isset($_GET['query']) ? trim($_GET['query']) : '';

if (empty($search_query)) {
    // Return all portfolios if no search query
    $sql = "SELECT * FROM portfolios ORDER BY ratings DESC";
    $stmt = $conn->prepare($sql);
} else {
    // Search in tags, description, and user name
    $search_param = "%{$search_query}%";
    $sql = "SELECT * FROM portfolios WHERE tags LIKE ? OR description LIKE ? OR uname LIKE ? ORDER BY ratings DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $search_param, $search_param, $search_param);
}

$stmt->execute();
$result = $stmt->get_result();

$portfolios = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $portfolios[] = $row;
    }
}

// Get user's liked portfolios if logged in
$user_likes = [];
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $likes_sql = "SELECT portfolio_id FROM portfolio_likes WHERE user_id = ?";
    $likes_stmt = $conn->prepare($likes_sql);
    if ($likes_stmt) {
        $likes_stmt->bind_param("i", $user_id);
        $likes_stmt->execute();
        $likes_result = $likes_stmt->get_result();
        while($like = $likes_result->fetch_assoc()) {
            $user_likes[] = $like['portfolio_id'];
        }
        $likes_stmt->close();
    }
}

$stmt->close();
$conn->close();

// Return JSON response
header('Content-Type: application/json');
echo json_encode([
    'success' => true,
    'portfolios' => $portfolios,
    'user_likes' => $user_likes
]);
?>
