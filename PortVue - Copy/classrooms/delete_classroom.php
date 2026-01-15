<?php
session_start();

// DB connect
$servername = "localhost";
$db_username = "root";
$db_password = "";
$dbname = "portvuedb";
$conn = new mysqli($servername, $db_username, $db_password, $dbname);
if ($conn->connect_error) {
    die(json_encode(['success'=>false,'msg'=>'Connection failed: ' . $conn->connect_error]));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $class_code = isset($_POST['ccode']) ? trim($_POST['ccode']) : '';
    $session_username = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : '';

    if (empty($class_code)) {
        echo json_encode(['success'=>false,'msg'=>'Class code is required.']);
        exit;
    }

    if (empty($session_username)) {
        echo json_encode(['success'=>false,'msg'=>'You must be logged in to delete a classroom.']);
        exit;
    }

    // Verify user is the host of this classroom
    $stmt = $conn->prepare('SELECT cid, host FROM classes WHERE ccode = ?');
    $stmt->bind_param('s', $class_code);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        echo json_encode(['success'=>false,'msg'=>'Classroom not found.']);
        $stmt->close();
        $conn->close();
        exit;
    }
    
    $row = $result->fetch_assoc();
    if (strtolower($row['host']) !== strtolower($session_username)) {
        echo json_encode(['success'=>false,'msg'=>'Only the classroom host can delete this classroom.']);
        $stmt->close();
        $conn->close();
        exit;
    }
    $stmt->close();

    // Delete all student submissions for this class
    $delete_students = $conn->prepare('DELETE FROM students WHERE ccode = ?');
    $delete_students->bind_param('s', $class_code);
    $delete_students->execute();
    $delete_students->close();

    // Delete the classroom
    $delete_class = $conn->prepare('DELETE FROM classes WHERE ccode = ?');
    $delete_class->bind_param('s', $class_code);
    $delete_success = $delete_class->execute();
    $delete_class->close();

    if ($delete_success) {
        // Delete the classroom folder
        $class_folder = __DIR__ . '/../classprojects/' . $class_code;
        if (file_exists($class_folder)) {
            deleteDirectory($class_folder);
        }
        
        // Clear session variables
        unset($_SESSION['admin_class_code']);
        unset($_SESSION['admin_class_id']);
        
        echo json_encode(['success'=>true,'msg'=>'Classroom and all associated data deleted successfully.']);
    } else {
        echo json_encode(['success'=>false,'msg'=>'Failed to delete classroom.']);
    }
    
    $conn->close();
} else {
    echo json_encode(['success'=>false,'msg'=>'Invalid request.']);
}

// Helper function to recursively delete directory
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
