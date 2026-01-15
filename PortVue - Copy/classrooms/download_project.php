<?php
session_start();

// Check if folder parameter is provided
if (!isset($_GET['folder']) || empty($_GET['folder'])) {
    die('No folder specified');
}

$folder = basename($_GET['folder']); // Sanitize folder name
$class_code = isset($_GET['ccode']) ? basename($_GET['ccode']) : '';

if (empty($class_code)) {
    die('No class code specified');
}

// Construct the full path
$project_path = __DIR__ . '/../classprojects/' . $class_code . '/' . $folder;

if (!file_exists($project_path) || !is_dir($project_path)) {
    die('Project folder not found: ' . htmlspecialchars($project_path));
}

// Check if ZipArchive is available
if (!class_exists('ZipArchive')) {
    die('ZipArchive extension is not enabled. Please enable php_zip extension in your php.ini file. Restart XAMPP after enabling.');
}

// Create a zip file
$zip_file = sys_get_temp_dir() . '/' . $folder . '_' . time() . '.zip';
$zip = new ZipArchive();

if ($zip->open($zip_file, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== TRUE) {
    die('Could not create zip file');
}

// Add files to zip
$files = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($project_path),
    RecursiveIteratorIterator::LEAVES_ONLY
);

foreach ($files as $name => $file) {
    if (!$file->isDir()) {
        $filePath = $file->getRealPath();
        $relativePath = substr($filePath, strlen($project_path) + 1);
        $zip->addFile($filePath, $relativePath);
    }
}

$zip->close();

// Send zip file to browser
header('Content-Type: application/zip');
header('Content-Disposition: attachment; filename="' . $folder . '.zip"');
header('Content-Length: ' . filesize($zip_file));
readfile($zip_file);

// Delete temporary zip file
unlink($zip_file);
exit;
?>
