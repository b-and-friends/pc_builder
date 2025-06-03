<?php
header('Content-Type: application/json');

// Database connection (update credentials as needed)
$mysqli = new mysqli('localhost', 'root', '', 'pc_builder');
if ($mysqli->connect_errno) {
    echo json_encode([]);
    exit;
}

$type = isset($_GET['component_type']) ? trim($mysqli->real_escape_string($_GET['component_type'])) : '';

if ($type === '') {
    echo json_encode(['error' => 'Missing component_type']);
    exit;
}

$sql = "SELECT name, image, price, specs, rating, reviews FROM products WHERE component_type='$type' AND stock > 0";
// Uncomment the next line for debugging
// error_log("SQL: $sql");

$result = $mysqli->query($sql);

$products = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $products[] = [
            'name' => $row['name'],
            'image' => $row['image'],
            'price' => $row['price'],
            'specs' => $row['specs'],
            'rating' => floatval($row['rating']),
            'reviews' => intval($row['reviews'])
        ];
    }
} else {
    // Uncomment for debugging SQL errors
    // error_log("MySQL Error: " . $mysqli->error);
}

echo json_encode($products);
$mysqli->close();
?>
