<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login page/login.html");
    exit();
}

$user_id = $_SESSION['user_id'];

// Database connection
$servername = "localhost";
$db_username = "root";
$db_password = "";
$dbname = "portvuedb";

$conn = new mysqli($servername, $db_username, $db_password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if request is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get ALL portfolios for this user before deletion
    $stmt = $conn->prepare("SELECT pid, paddress FROM portfolios WHERE uid = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $portfolios_deleted = 0;
        $folders_deleted = 0;
        
        // Delete each portfolio's folder individually
        while ($portfolio = $result->fetch_assoc()) {
            $paddress = $portfolio['paddress'];
            
            // Check if it's a hosted portfolio (starts with ../portfolios/)
            if (strpos($paddress, '../portfolios/') === 0) {
                // Extract the folder path - remove '../portfolios/' prefix and trailing slash
                $folder_path = str_replace('../portfolios/', '', $paddress);
                $folder_path = rtrim($folder_path, '/');
                $folder_path = trim($folder_path);
                
                // Security check: ensure folder_path is not empty and doesn't contain path traversal
                // Folder names should only contain alphanumeric, underscore, and dash characters
                // Format: userid_timestamp_randomstring (no slashes allowed)
                if (!empty($folder_path) && 
                    strpos($folder_path, '..') === false && 
                    strpos($folder_path, '/') === false &&
                    preg_match('/^[a-zA-Z0-9_-]+$/', $folder_path)) {
                    
                    $full_path = __DIR__ . '/../portfolios/' . $folder_path;
                    
                    // Additional security: ensure the path is within the portfolios directory using realpath
                    $real_full_path = realpath($full_path);
                    $real_portfolios_dir = realpath(__DIR__ . '/../portfolios/');
                    
                    if ($real_full_path && 
                        $real_portfolios_dir && 
                        strpos($real_full_path, $real_portfolios_dir) === 0 &&
                        is_dir($real_full_path)) {
                        // Delete the portfolio folder and its contents
                        if (deleteDirectory($real_full_path)) {
                            $folders_deleted++;
                        }
                    }
                }
            }
        }
        
        // Close the result set before executing delete
        $stmt->close();
        
        // Delete ALL portfolio records from database for this user
        $delete_stmt = $conn->prepare("DELETE FROM portfolios WHERE uid = ?");
        $delete_stmt->bind_param("i", $user_id);
        
        if ($delete_stmt->execute()) {
            $portfolios_deleted = $delete_stmt->affected_rows;
            $_SESSION['delete_success'] = "Portfolio(s) deleted successfully! (Deleted " . $portfolios_deleted . " portfolio(s) and " . $folders_deleted . " folder(s))";
        } else {
            $_SESSION['delete_error'] = "Error deleting portfolio: " . $conn->error;
        }
        
        $delete_stmt->close();
    } else {
        $_SESSION['delete_error'] = "No portfolio found to delete.";
        $stmt->close();
    }
}

$conn->close();

// Redirect back to interface page
header("Location: interface.php");
exit();

// Function to recursively delete a directory
function deleteDirectory($dir) {
    if (!file_exists($dir)) {
        return true;
    }
    
    if (!is_dir($dir)) {
        return unlink($dir);
    }
    
    foreach (scandir($dir) as $item) {
        if ($item == '.' || $item == '..') {
            continue;
        }
        
        if (!deleteDirectory($dir . DIRECTORY_SEPARATOR . $item)) {
            return false;
        }
    }
    
    return rmdir($dir);
}
?>
