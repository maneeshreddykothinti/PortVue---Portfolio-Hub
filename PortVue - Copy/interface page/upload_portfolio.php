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
    
    // Validate files were uploaded
    if (!isset($_FILES['portfolio_files']) || empty($_FILES['portfolio_files']['name'][0])) {
        $_SESSION['upload_error'] = "Please select files to upload.";
        header("Location: interface.php");
        exit();
    }
    
    $files = $_FILES['portfolio_files'];
    $total_files = count($files['name']);
    
    // Check if index.html is present in root
    $has_index = false;
    for ($i = 0; $i < $total_files; $i++) {
        $file_path = $files['name'][$i];
        // Check if index.html is in the root (no subdirectory)
        if (strtolower(basename($file_path)) === 'index.html' && dirname($file_path) === '.') {
            $has_index = true;
            break;
        }
    }
    
    if (!$has_index) {
        $_SESSION['upload_error'] = "Main file 'index.html' must be in the root of the folder!";
        header("Location: interface.php");
        exit();
    }
    
    // Create unique folder for this portfolio
    // Use user_id, timestamp, and random string to ensure uniqueness
    $max_attempts = 100;
    $attempt = 0;
    $portfolio_folder_name = '';
    $portfolio_path = '';
    
    // Generate a unique folder name (ensure it doesn't exist)
    do {
        $random_string = bin2hex(random_bytes(8)); // 16 character random string
        $portfolio_folder_name = $user_id . '_' . time() . '_' . $random_string;
        $portfolio_path = '../portfolios/' . $portfolio_folder_name;
        $attempt++;
        
        if ($attempt >= $max_attempts) {
            $_SESSION['upload_error'] = "Failed to generate unique folder name. Please try again.";
            header("Location: interface.php");
            exit();
        }
    } while (file_exists($portfolio_path));
    
    // Create portfolio folder (we've verified it doesn't exist)
    if (!mkdir($portfolio_path, 0777, true)) {
        $_SESSION['upload_error'] = "Failed to create portfolio folder.";
        header("Location: interface.php");
        exit();
    }
    
    // Upload files with folder structure
    $upload_success = true;
    
    for ($i = 0; $i < $total_files; $i++) {
        $relative_path = $files['name'][$i];
        $file_tmp = $files['tmp_name'][$i];
        $file_error = $files['error'][$i];
        
        // Check for upload errors
        if ($file_error !== UPLOAD_ERR_OK) {
            $upload_success = false;
            $_SESSION['upload_error'] = "Error uploading file: " . basename($relative_path);
            break;
        }
        
        // Validate file extension
        $allowed_extensions = ['html', 'css', 'js', 'jpg', 'jpeg', 'png', 'gif', 'svg', 'woff', 'woff2', 'ttf', 'eot', 'ico'];
        $file_extension = strtolower(pathinfo($relative_path, PATHINFO_EXTENSION));
        
        if ($file_extension && !in_array($file_extension, $allowed_extensions)) {
            $_SESSION['upload_error'] = "File type not allowed: " . $file_extension;
            $upload_success = false;
            break;
        }
        
        // Create subdirectories if needed
        $destination = $portfolio_path . '/' . $relative_path;
        $destination_dir = dirname($destination);
        
        if (!file_exists($destination_dir)) {
            if (!mkdir($destination_dir, 0777, true)) {
                $_SESSION['upload_error'] = "Failed to create directory structure.";
                $upload_success = false;
                break;
            }
        }
        
        // Move uploaded file
        if (!move_uploaded_file($file_tmp, $destination)) {
            $_SESSION['upload_error'] = "Failed to upload file: " . basename($relative_path);
            $upload_success = false;
            break;
        }
    }
    
    if ($upload_success) {
        // Get tags and description from POST
        $tags = isset($_POST['tags']) ? $_POST['tags'] : '';
        $description = isset($_POST['description']) ? $_POST['description'] : '';
        $paddress = '../portfolios/' . $portfolio_folder_name . '/';
        $ratings = 0;
        
        // Insert into database
        $stmt = $conn->prepare("INSERT INTO portfolios (uid, uname, paddress, tags, description, ratings) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("issssi", $user_id, $user_name, $paddress, $tags, $description, $ratings);
        
        if ($stmt->execute()) {
            $_SESSION['upload_success'] = "Upload Successful!";
        } else {
            $_SESSION['upload_error'] = "Portfolio files uploaded but database save failed: " . $stmt->error;
        }
        
        $stmt->close();
        $conn->close();
        header("Location: interface.php");
        exit();
    } else {
        // Delete uploaded files if upload failed (recursive delete)
        if (file_exists($portfolio_path)) {
            deleteDirectory($portfolio_path);
        }
        
        header("Location: interface.php");
        exit();
    }
} else {
    header("Location: interface.php");
    exit();
}

// Recursive directory delete function
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
