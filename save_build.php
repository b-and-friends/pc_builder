<?php
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

// Get POST data
$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (!$data) {
    echo json_encode(['success' => false, 'message' => 'Invalid JSON']);
    exit;
}

// In production, get user_id from session
$user_id = isset($data['user_id']) ? intval($data['user_id']) : 0;
$name = isset($data['name']) ? trim($data['name']) : '';
$components = isset($data['components']) ? $data['components'] : null;

if ($user_id <= 0 || !$name || !$components) {
    echo json_encode(['success' => false, 'message' => 'Missing data']);
    exit;
}

$mysqli = new mysqli('localhost', 'root', '', 'pc_builder');
if ($mysqli->connect_errno) {
    echo json_encode(['success' => false, 'message' => 'DB connection error']);
    exit;
}

$components_json = json_encode($components);

$stmt = $mysqli->prepare("INSERT INTO builds (user_id, name, components) VALUES (?, ?, ?)");
if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Prepare failed: ' . $mysqli->error]);
    $mysqli->close();
    exit;
}
$stmt->bind_param('iss', $user_id, $name, $components_json);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'build_id' => $stmt->insert_id]);
} else {
    echo json_encode(['success' => false, 'message' => 'Insert failed: ' . $stmt->error]);
}

$stmt->close();
$mysqli->close();
?>
