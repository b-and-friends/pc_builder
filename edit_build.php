<?php
header('Content-Type: application/json');
$data = json_decode(file_get_contents('php://input'), true);
$id = isset($data['id']) ? intval($data['id']) : 0;
$name = isset($data['name']) ? trim($data['name']) : '';
if ($id <= 0 || !$name) {
    echo json_encode(['success' => false]);
    exit;
}
$mysqli = new mysqli('localhost', 'root', '', 'pc_builder');
if ($mysqli->connect_errno) {
    echo json_encode(['success' => false]);
    exit;
}
$stmt = $mysqli->prepare("UPDATE builds SET name=?, updated_at=NOW() WHERE id=?");
$stmt->bind_param('si', $name, $id);
$success = $stmt->execute();
echo json_encode(['success' => $success]);
$stmt->close();
$mysqli->close();
?>
