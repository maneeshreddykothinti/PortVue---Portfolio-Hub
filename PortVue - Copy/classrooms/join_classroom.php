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

// Must be posted from the classroom join form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $class_code = isset($_POST['ccode']) ? trim($_POST['ccode']) : '';
    $class_pass = isset($_POST['pass']) ? trim($_POST['pass']) : '';

    if (empty($class_code) || empty($class_pass)) {
        echo json_encode(['success'=>false,'msg'=>'Please enter class code and password.']);
        exit;
    }

    // Verify class exists and password matches
    $stmt = $conn->prepare('SELECT cid, host FROM classes WHERE ccode = ? AND pass = ?');
    $stmt->bind_param('ss', $class_code, $class_pass);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Store class info in session for later use
        $_SESSION['current_class_code'] = $class_code;
        $_SESSION['current_class_id'] = $row['cid'];
        $_SESSION['current_class_host'] = $row['host'];
        
        echo json_encode([
            'success'=>true,
            'msg'=>'Successfully joined classroom!',
            'ccode'=>$class_code
        ]);
    } else {
        echo json_encode(['success'=>false,'msg'=>'Invalid class code or password.']);
    }
    
    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['success'=>false,'msg'=>'Invalid request.']);
}
?>
