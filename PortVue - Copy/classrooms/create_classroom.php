<?php
session_start();

// DB connect
$servername = "localhost";
$db_username = "root";
$db_password = "";
$dbname = "portvuedb";
$conn = new mysqli($servername, $db_username, $db_password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Must be posted from the classroom create form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $form_username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $class_code = isset($_POST['ccode']) ? trim($_POST['ccode']) : '';
    $class_pass = isset($_POST['pass']) ? trim($_POST['pass']) : '';

    // SESSION username for validation
    $session_username = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : '';

    if (!$session_username || strtolower($session_username) !== strtolower($form_username)) {
        echo json_encode(['success'=>false,'msg'=>'Session username and entered username do not match. Please log in as this user.']);
        exit;
    }
    if (empty($class_code) || empty($class_pass)) {
        echo json_encode(['success'=>false,'msg'=>'Please enter all required fields.']);
        exit;
    }

    // Check if class code already exists
    $check_stmt = $conn->prepare('SELECT cid FROM classes WHERE ccode = ?');
    $check_stmt->bind_param('s', $class_code);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    
    if ($check_result->num_rows > 0) {
        echo json_encode(['success'=>false,'msg'=>'Class code already exists. Please choose a different code.']);
        $check_stmt->close();
        $conn->close();
        exit;
    }
    $check_stmt->close();
    
    // Create folder for the class
    $projects_dir = __DIR__ . '/../classprojects/' . $class_code;
    if (!file_exists($projects_dir)) {
        if (!mkdir($projects_dir, 0777, true)) {
            echo json_encode(['success'=>false,'msg'=>'Failed to create class folder.']);
            $conn->close();
            exit;
        }
    }
    
    $stmt = $conn->prepare('INSERT INTO classes (ccode, pass, host) VALUES (?, ?, ?)');
    $stmt->bind_param('sss', $class_code, $class_pass, $session_username);
    $ok = $stmt->execute();
    if ($ok) {
        echo json_encode(['success'=>true,'msg'=>'Classroom created! Now share your class code with students.', 'ccode'=>$class_code]);
    } else {
        // If DB insert fails, remove the created folder
        if (file_exists($projects_dir)) {
            rmdir($projects_dir);
        }
        echo json_encode(['success'=>false,'msg'=>'Classroom creation failed: ' . $stmt->error]);
    }
    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['success'=>false,'msg'=>'Invalid request.']);
}
