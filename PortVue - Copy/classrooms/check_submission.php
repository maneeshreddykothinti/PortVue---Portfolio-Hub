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
    $roll_number = isset($_POST['roll_number']) ? trim($_POST['roll_number']) : '';
    $class_code = isset($_SESSION['current_class_code']) ? $_SESSION['current_class_code'] : '';

    if (empty($roll_number) || empty($class_code)) {
        echo json_encode(['success'=>false,'msg'=>'Invalid request.']);
        exit;
    }

    // Check if student already has a submission
    $stmt = $conn->prepare('SELECT sid, paddress FROM students WHERE sroll = ? AND ccode = ?');
    $stmt->bind_param('ss', $roll_number, $class_code);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo json_encode([
            'success'=>true,
            'exists'=>true,
            'msg'=>'You have already submitted a project for this classroom.',
            'sid'=>$row['sid']
        ]);
    } else {
        echo json_encode([
            'success'=>true,
            'exists'=>false,
            'msg'=>'No previous submission found.'
        ]);
    }
    
    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['success'=>false,'msg'=>'Invalid request.']);
}
?>
