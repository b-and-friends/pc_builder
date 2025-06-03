<?php
header('Content-Type: application/json');
$data = json_decode(file_get_contents('php://input'), true);
$id = isset($data['id']) ? intval($data['id']) : 0;
if ($id <= 0) {
    echo json_encode(['success' => false]);
    exit;
}
$mysqli = new mysqli('localhost', 'root', '', 'pc_builder');
if ($mysqli->connect_errno) {
    echo json_encode(['success' => false]);
    exit;
}
$stmt = $mysqli->prepare("DELETE FROM builds WHERE id=?");
$stmt->bind_param('i', $id);
$success = $stmt->execute();
echo json_encode(['success' => $success]);
$stmt->close();
$mysqli->close();
?>
