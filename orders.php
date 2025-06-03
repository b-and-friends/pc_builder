<?php
header('Content-Type: application/json');

// Simple DB connection (adjust credentials as needed)
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
} catch (\PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed: ' . $e->getMessage()]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Support fetching orders by user_id (int)
    $user_id = isset($_GET['user_id']) ? $_GET['user_id'] : null;
    if (!$user_id) {
        echo json_encode([]);
        exit;
    }
    // Only allow numeric user_id (your orders table uses int)
    if (!ctype_digit(strval($user_id))) {
        echo json_encode([]);
        exit;
    }

    // Debug: Output user_id and all orders for troubleshooting
    // Remove/comment out after debugging
    error_log("orders.php GET user_id: $user_id");

    // Show all orders for debugging
    // $allOrders = $pdo->query("SELECT * FROM orders")->fetchAll();
    // error_log("All orders: " . json_encode($allOrders));

    $stmt = $pdo->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC");
    $stmt->execute([$user_id]);
    $orders = $stmt->fetchAll();

    // Debug: If no orders, log for troubleshooting
    if (empty($orders)) {
        error_log("orders.php: No orders found for user_id $user_id");
    } else {
        error_log("orders.php: Found " . count($orders) . " orders for user_id $user_id");
    }

    echo json_encode($orders);
    exit;
}

// Get POST data
$data = json_decode(file_get_contents('php://input'), true);

// Debug: If data is null, show error and raw input for troubleshooting
if ($data === null) {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid JSON or empty request body',
        'raw_input' => file_get_contents('php://input')
    ]);
    exit;
}

if (!isset($data['user_id']) || !isset($data['products']) || !is_array($data['products']) || count($data['products']) === 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid order data']);
    exit;
}

$user_id = intval($data['user_id']);
$products = $data['products'];
$order_date = date('Y-m-d H:i:s');

// Calculate total price
$total = 0;
foreach ($products as $p) {
    $price = isset($p['price']) ? floatval($p['price']) : 0;
    $total += $price;
}

// Insert order
try {
    $pdo->beginTransaction();

    // Insert order (use 'total' column, which now exists)
    $stmt = $pdo->prepare("INSERT INTO orders (user_id, created_at, total) VALUES (?, ?, ?)");
    $stmt->execute([$user_id, $order_date, $total]);
    $order_id = $pdo->lastInsertId();

    $stmt_item = $pdo->prepare("INSERT INTO order_items (order_id, component_type, name, price, image, specs) VALUES (?, ?, ?, ?, ?, ?)");
    foreach ($products as $p) {
        $stmt_item->execute([
            $order_id,
            isset($p['type']) ? $p['type'] : '',
            isset($p['name']) ? $p['name'] : '',
            isset($p['price']) ? $p['price'] : 0,
            isset($p['image']) ? $p['image'] : '',
            isset($p['specs']) ? $p['specs'] : ''
        ]);
    }

    $pdo->commit();
    echo json_encode(['success' => true, 'order_id' => $order_id]);
} catch (Exception $e) {
    // Output the real error for debugging
    error_log('Order Save Error: ' . $e->getMessage());
    // Only roll back if a transaction is active and not already rolled back
    try {
        if ($pdo && method_exists($pdo, 'inTransaction') && $pdo->inTransaction()) {
            $pdo->rollBack();
        }
    } catch (Exception $rollbackError) {
        // Ignore rollback errors
    }
    echo json_encode([
        'success' => false,
        'message' => 'Order failed to save: ' . $e->getMessage(),
        'debug' => [
            'order_id' => isset($order_id) ? $order_id : null,
            'last_sql_error' => $pdo->errorInfo()[2] ?? null
        ]
    ]);
}
