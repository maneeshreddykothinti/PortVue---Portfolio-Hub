<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Please login to like portfolios']);
    exit();
}

// Database configuration
$host = 'localhost';
$db_name = 'portvuedb';
$username = 'root';
$password = '';

// Create connection
$conn = new mysqli($host, $username, $password, $db_name);

// Check connection
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit();
}

// Get portfolio ID from POST request
$portfolio_id = isset($_POST['portfolio_id']) ? intval($_POST['portfolio_id']) : 0;
$user_id = $_SESSION['user_id'];

if ($portfolio_id <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid portfolio']);
    exit();
}

// Create likes table if it doesn't exist
$create_table_sql = "CREATE TABLE IF NOT EXISTS portfolio_likes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    portfolio_id INT NOT NULL,
    user_id INT NOT NULL,
    liked_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_like (portfolio_id, user_id),
    FOREIGN KEY (portfolio_id) REFERENCES portfolios(pid) ON DELETE CASCADE
)";
$conn->query($create_table_sql);

// Check if user already liked this portfolio
$check_stmt = $conn->prepare("SELECT id FROM portfolio_likes WHERE portfolio_id = ? AND user_id = ?");
$check_stmt->bind_param("ii", $portfolio_id, $user_id);
$check_stmt->execute();
$result = $check_stmt->get_result();

if ($result->num_rows > 0) {
    // User already liked - unlike (remove like)
    $delete_stmt = $conn->prepare("DELETE FROM portfolio_likes WHERE portfolio_id = ? AND user_id = ?");
    $delete_stmt->bind_param("ii", $portfolio_id, $user_id);
    
    if ($delete_stmt->execute()) {
        // Decrease rating
        $update_stmt = $conn->prepare("UPDATE portfolios SET ratings = GREATEST(0, ratings - 1) WHERE pid = ?");
        $update_stmt->bind_param("i", $portfolio_id);
        $update_stmt->execute();
        
        // Get updated rating
        $rating_stmt = $conn->prepare("SELECT ratings FROM portfolios WHERE pid = ?");
        $rating_stmt->bind_param("i", $portfolio_id);
        $rating_stmt->execute();
        $rating_result = $rating_stmt->get_result();
        $row = $rating_result->fetch_assoc();
        
        echo json_encode([
            'success' => true, 
            'message' => 'Like removed',
            'liked' => false,
            'ratings' => $row['ratings']
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to remove like']);
    }
} else {
    // User hasn't liked yet - add like
    $insert_stmt = $conn->prepare("INSERT INTO portfolio_likes (portfolio_id, user_id) VALUES (?, ?)");
    $insert_stmt->bind_param("ii", $portfolio_id, $user_id);
    
    if ($insert_stmt->execute()) {
        // Increase rating
        $update_stmt = $conn->prepare("UPDATE portfolios SET ratings = ratings + 1 WHERE pid = ?");
        $update_stmt->bind_param("i", $portfolio_id);
        $update_stmt->execute();
        
        // Get updated rating
        $rating_stmt = $conn->prepare("SELECT ratings FROM portfolios WHERE pid = ?");
        $rating_stmt->bind_param("i", $portfolio_id);
        $rating_stmt->execute();
        $rating_result = $rating_stmt->get_result();
        $row = $rating_result->fetch_assoc();
        
        echo json_encode([
            'success' => true, 
            'message' => 'Portfolio liked',
            'liked' => true,
            'ratings' => $row['ratings']
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to add like']);
    }
}

$conn->close();
?>
