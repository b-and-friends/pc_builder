<?php
session_start();
header('Content-Type: application/json');

// Debug: Show errors for development
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Replace with your actual authentication/session logic
// For demo, assume user_id is stored in session
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Not logged in']);
    exit;
}

$user_id = $_SESSION['user_id'];

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

try {
    $pdo = new PDO($dsn, $user, $pass, $options);

    // Fetch all relevant fields including avatar
    $stmt = $pdo->prepare("SELECT full_name AS fullname, email, phone, address, avatar, created_at, last_login FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch();
    if ($user) {
        // Always provide avatar_url for the frontend
        if (!empty($user['avatar'])) {
            // Ensure the path is relative to your web root and correct for browser
            $avatar_path = $user['avatar'];
            // Remove any leading slashes for consistency
            $avatar_path = ltrim($avatar_path, '/\\');
            // Prepend the correct base path for your project
            $user['avatar_url'] = "/pc_builder/" . $avatar_path;
        } else {
            $user['avatar_url'] = null;
        }
        echo json_encode($user);
    } else {
        echo json_encode(['error' => 'User not found']);
    }
} catch (Exception $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}

// No changes needed in this file for 404 errors.
// The 404 error means the file (get_profile.php) is missing or the path is incorrect.
// Make sure this file exists at: c:\xampp\htdocs\pc_builder\get_profile.php
// and that your frontend is requesting it as 'get_profile.php' (not a different path or typo).
