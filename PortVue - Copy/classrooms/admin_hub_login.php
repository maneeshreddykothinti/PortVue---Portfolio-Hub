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

// Must be posted from the admin hub login form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $form_username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $class_code = isset($_POST['ccode']) ? trim($_POST['ccode']) : '';
    $class_pass = isset($_POST['pass']) ? trim($_POST['pass']) : '';

    // SESSION username for validation
    $session_username = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : '';

    if (!$session_username || strtolower($session_username) !== strtolower($form_username)) {
        echo json_encode(['success'=>false,'msg'=>'Session username and entered username do not match. Please log in first.']);
        exit;
    }

    if (empty($class_code) || empty($class_pass)) {
        echo json_encode(['success'=>false,'msg'=>'Please enter all required fields.']);
        exit;
    }

    // Verify class exists, password matches, and user is the host
    $stmt = $conn->prepare('SELECT cid, host FROM classes WHERE ccode = ? AND pass = ?');
    $stmt->bind_param('ss', $class_code, $class_pass);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        // Check if the logged-in user is the host
        if (strtolower($row['host']) === strtolower($session_username)) {
            $_SESSION['admin_class_code'] = $class_code;
            $_SESSION['admin_class_id'] = $row['cid'];
            
            echo json_encode([
                'success'=>true,
                'msg'=>'Admin access granted!',
                'ccode'=>$class_code
            ]);
        } else {
            echo json_encode(['success'=>false,'msg'=>'You are not the host of this classroom.']);
        }
    } else {
        echo json_encode(['success'=>false,'msg'=>'Invalid class code or password.']);
    }
    
    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['success'=>false,'msg'=>'Invalid request.']);
}
?>
