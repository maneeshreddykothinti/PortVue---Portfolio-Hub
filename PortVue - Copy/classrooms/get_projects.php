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

// Get class code from session or request
$class_code = isset($_SESSION['admin_class_code']) ? $_SESSION['admin_class_code'] : '';

if (isset($_GET['ccode'])) {
    $class_code = trim($_GET['ccode']);
}

if (empty($class_code)) {
    echo json_encode(['success'=>false,'msg'=>'No classroom selected.']);
    exit;
}

// Fetch all student projects for this class
$stmt = $conn->prepare('SELECT sid, sroll, paddress FROM students WHERE ccode = ? ORDER BY sroll ASC');
$stmt->bind_param('s', $class_code);
$stmt->execute();
$result = $stmt->get_result();

$projects = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        // Extract folder name from paddress
        $folder_name = '';
        if (preg_match('/classprojects\/' . preg_quote($class_code, '/') . '\/([^\/]+)/', $row['paddress'], $matches)) {
            $folder_name = $matches[1];
        }
        
        // Convert relative path to web-accessible URL
        // Remove '../' and ensure forward slashes
        $web_address = str_replace('\\', '/', $row['paddress']);
        $web_address = str_replace('../', '', $web_address);
        
        // Get the server protocol and host
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'];
        
        // Make it absolute URL with full localhost path
        $web_address = $protocol . '://' . $host . '/wt%20project/' . ltrim($web_address, '/');
        
        $projects[] = [
            'sid' => $row['sid'],
            'roll' => $row['sroll'],
            'address' => $web_address,
            'folder' => $folder_name
        ];
    }
}

$stmt->close();
$conn->close();

echo json_encode([
    'success'=>true,
    'projects'=>$projects,
    'count'=>count($projects),
    'ccode'=>$class_code
]);
?>
