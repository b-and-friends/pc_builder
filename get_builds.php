<?php
header('Content-Type: application/json');
$user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;
if ($user_id <= 0) {
    echo json_encode([]);
    exit;
}
$mysqli = new mysqli('localhost', 'root', '', 'pc_builder');
if ($mysqli->connect_errno) {
    echo json_encode([]);
    exit;
}
$res = $mysqli->query("SELECT id, name, components, created_at, updated_at FROM builds WHERE user_id=$user_id ORDER BY created_at DESC");
$builds = [];
while ($row = $res->fetch_assoc()) {
    $builds[] = $row;
}
echo json_encode($builds);
$mysqli->close();
?>
