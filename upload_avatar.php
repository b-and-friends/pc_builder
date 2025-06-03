<?php
session_start();
header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit;
}

$user_id = $_SESSION['user_id'];

// Check if file is uploaded
if (!isset($_FILES['avatar']) || $_FILES['avatar']['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['success' => false, 'message' => 'No file uploaded or upload error']);
    exit;
}

// Validate file type (allow only images)
$allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
$file_type = mime_content_type($_FILES['avatar']['tmp_name']);
if (!in_array($file_type, $allowed_types)) {
    echo json_encode(['success' => false, 'message' => 'Invalid file type']);
    exit;
}

// Limit file size (e.g., 2MB)
if ($_FILES['avatar']['size'] > 2 * 1024 * 1024) {
    echo json_encode(['success' => false, 'message' => 'File too large (max 2MB)']);
    exit;
}

// Prepare upload directory
$upload_dir = __DIR__ . '/uploads/avatars/';
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0777, true); // This will create the folders if they do not exist
}

// Generate unique filename
$ext = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
$filename = 'avatar_' . $user_id . '_' . time() . '.' . $ext;
$target_path = $upload_dir . $filename;

// Move uploaded file
if (!move_uploaded_file($_FILES['avatar']['tmp_name'], $target_path)) {
    echo json_encode(['success' => false, 'message' => 'Failed to save file']);
    exit;
}

// Save avatar path to database (relative URL)
$avatar_url = 'uploads/avatars/' . $filename;

$host = 'localhost';
$db   = 'pc_builder';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

// Add avatar column if not exists
try {
    $pdo = new PDO($dsn, $user, $pass, $options);
    $columns = $pdo->query("SHOW COLUMNS FROM users")->fetchAll(PDO::FETCH_COLUMN);
    if (!in_array('avatar', $columns)) {
        $pdo->exec("ALTER TABLE users ADD COLUMN avatar VARCHAR(255) DEFAULT NULL");
    }
    $stmt = $pdo->prepare("UPDATE users SET avatar = ? WHERE id = ?");
    $stmt->execute([$avatar_url, $user_id]);
    echo json_encode(['success' => true, 'avatar_url' => $avatar_url]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
