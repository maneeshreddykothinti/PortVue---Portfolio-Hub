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

// Must be posted from the portfolio submission form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $roll_number = isset($_POST['roll_number']) ? trim($_POST['roll_number']) : '';
    $class_code = isset($_SESSION['current_class_code']) ? $_SESSION['current_class_code'] : '';

    if (empty($roll_number)) {
        echo json_encode(['success'=>false,'msg'=>'Please enter your roll number.']);
        exit;
    }

    if (empty($class_code)) {
        echo json_encode(['success'=>false,'msg'=>'No active classroom session. Please join a classroom first.']);
        exit;
    }

    // Verify class exists
    $stmt = $conn->prepare('SELECT cid FROM classes WHERE ccode = ?');
    $stmt->bind_param('s', $class_code);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        echo json_encode(['success'=>false,'msg'=>'Invalid classroom.']);
        $stmt->close();
        $conn->close();
        exit;
    }
    $stmt->close();

    // Check if files are uploaded
    if (!isset($_FILES['files']) || empty($_FILES['files']['name'][0])) {
        echo json_encode(['success'=>false,'msg'=>'Please select project files to upload.']);
        exit;
    }

    // Generate unique ID for this submission
    $uid = uniqid();
    $folder_name = $roll_number . '_' . $uid;
    
    // Create folder structure: classprojects/classcode/rollnumber_uid/
    $upload_dir = __DIR__ . '/../classprojects/' . $class_code . '/' . $folder_name;
    
    if (!file_exists($upload_dir)) {
        if (!mkdir($upload_dir, 0777, true)) {
            echo json_encode(['success'=>false,'msg'=>'Failed to create upload directory.']);
            $conn->close();
            exit;
        }
    }

    // Handle multiple file uploads
    $files = $_FILES['files'];
    $file_count = count($files['name']);
    $uploaded_files = [];
    $errors = [];

    for ($i = 0; $i < $file_count; $i++) {
        if ($files['error'][$i] === UPLOAD_ERR_OK) {
            $file_name = basename($files['name'][$i]);
            $relative_path = isset($_POST['relative_paths'][$i]) ? $_POST['relative_paths'][$i] : $file_name;
            
            // Create subdirectories if needed
            $file_path = $upload_dir . '/' . $relative_path;
            $file_dir = dirname($file_path);
            
            if (!file_exists($file_dir)) {
                mkdir($file_dir, 0777, true);
            }
            
            if (move_uploaded_file($files['tmp_name'][$i], $file_path)) {
                $uploaded_files[] = $relative_path;
            } else {
                $errors[] = "Failed to upload: " . $file_name;
            }
        }
    }

    if (count($uploaded_files) > 0) {
        // Determine the correct index.html path (handles extra top-level folder like "1.3 portfolio/")
        $index_relative_path = 'index.html';
        $rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($upload_dir, FilesystemIterator::SKIP_DOTS));
        foreach ($rii as $file) {
            if ($file->isFile() && strtolower($file->getFilename()) === 'index.html') {
                $full_index_path = $file->getPathname();
                // Build path relative to $upload_dir
                $index_relative_path = ltrim(str_replace($upload_dir, '', $full_index_path), DIRECTORY_SEPARATOR);
                break;
            }
        }

        // Store in database using the discovered index path
        $project_address = '../classprojects/' . $class_code . '/' . $folder_name . '/' . $index_relative_path;
        
        // Check if student already has a submission for this class
        $check_stmt = $conn->prepare('SELECT sid, paddress FROM students WHERE sroll = ? AND ccode = ?');
        $check_stmt->bind_param('ss', $roll_number, $class_code);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();
        
        if ($check_result->num_rows > 0) {
            // Student is resubmitting - delete old project folder
            $row = $check_result->fetch_assoc();
            $old_paddress = $row['paddress'];
            
            // Extract old folder name from old path
            if (preg_match('/classprojects\/' . preg_quote($class_code, '/') . '\/([^\/]+)/', $old_paddress, $matches)) {
                $old_folder_name = $matches[1];
                $old_folder_path = __DIR__ . '/../classprojects/' . $class_code . '/' . $old_folder_name;
                
                // Delete old folder if it exists
                if (file_exists($old_folder_path) && is_dir($old_folder_path)) {
                    deleteDirectory($old_folder_path);
                }
            }
            
            // Update existing submission with new project
            $update_stmt = $conn->prepare('UPDATE students SET paddress = ? WHERE sroll = ? AND ccode = ?');
            $update_stmt->bind_param('sss', $project_address, $roll_number, $class_code);
            $update_stmt->execute();
            $update_stmt->close();
            
            $msg = 'Project re-uploaded successfully! Previous project has been replaced. Files uploaded: ' . count($uploaded_files);
        } else {
            // Insert new submission
            $insert_stmt = $conn->prepare('INSERT INTO students (sroll, ccode, paddress) VALUES (?, ?, ?)');
            $insert_stmt->bind_param('sss', $roll_number, $class_code, $project_address);
            $insert_stmt->execute();
            $insert_stmt->close();
            
            $msg = 'Project uploaded successfully! Files uploaded: ' . count($uploaded_files);
        }
        $check_stmt->close();
        
        // Add error info if any
        if (count($errors) > 0) {
            $msg .= ' (Some files failed: ' . implode(', ', $errors) . ')';
        }
        
        echo json_encode([
            'success'=>true,
            'msg'=>$msg,
            'folder'=>$folder_name,
            'uploaded'=>count($uploaded_files)
        ]);
    } else {
        echo json_encode(['success'=>false,'msg'=>'No files were uploaded. Errors: ' . implode(', ', $errors)]);
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
