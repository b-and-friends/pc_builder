<?php
session_start();
header('Content-Type: application/json');

// Replace with your actual authentication/session logic
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit;
}

$user_id = $_SESSION['user_id'];
$data = json_decode(file_get_contents('php://input'), true);

if (!$data || !isset($data['fullname']) || !isset($data['email'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid input']);
    exit;
}

$full_name = trim($data['fullname']);
$email = trim($data['email']);
$password = isset($data['password']) ? $data['password'] : '';
$phone = isset($data['phone']) ? trim($data['phone']) : null;
$address = isset($data['address']) ? trim($data['address']) : null;

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

    // Check if 'phone', 'address', and 'avatar' columns exist
    $columns = $pdo->query("SHOW COLUMNS FROM users")->fetchAll(PDO::FETCH_COLUMN);
    $hasPhone = in_array('phone', $columns);
    $hasAddress = in_array('address', $columns);
    $hasAvatar = in_array('avatar', $columns);

    // Optionally allow avatar update via JSON (if sent)
    $avatar = isset($data['avatar']) && $hasAvatar ? trim($data['avatar']) : null;

    if ($password && $password !== '********') {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        if ($hasPhone && $hasAddress && $hasAvatar && $avatar) {
            $stmt = $pdo->prepare("UPDATE users SET full_name = ?, email = ?, password = ?, phone = ?, address = ?, avatar = ? WHERE id = ?");
            $stmt->execute([$full_name, $email, $hashed, $phone, $address, $avatar, $user_id]);
        } elseif ($hasPhone && $hasAddress) {
            $stmt = $pdo->prepare("UPDATE users SET full_name = ?, email = ?, password = ?, phone = ?, address = ? WHERE id = ?");
            $stmt->execute([$full_name, $email, $hashed, $phone, $address, $user_id]);
        } else {
            $stmt = $pdo->prepare("UPDATE users SET full_name = ?, email = ?, password = ? WHERE id = ?");
            $stmt->execute([$full_name, $email, $hashed, $user_id]);
        }
    } else {
        if ($hasPhone && $hasAddress && $hasAvatar && $avatar) {
            $stmt = $pdo->prepare("UPDATE users SET full_name = ?, email = ?, phone = ?, address = ?, avatar = ? WHERE id = ?");
            $stmt->execute([$full_name, $email, $phone, $address, $avatar, $user_id]);
        } elseif ($hasPhone && $hasAddress) {
            $stmt = $pdo->prepare("UPDATE users SET full_name = ?, email = ?, phone = ?, address = ? WHERE id = ?");
            $stmt->execute([$full_name, $email, $phone, $address, $user_id]);
        } else {
            $stmt = $pdo->prepare("UPDATE users SET full_name = ?, email = ? WHERE id = ?");
            $stmt->execute([$full_name, $email, $user_id]);
        }
    }

    echo json_encode(['success' => true]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
