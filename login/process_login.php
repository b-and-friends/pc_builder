<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Database configuration
$db_host = 'localhost';
$db_username = 'root';
$db_password = '';
$db_name = 'pc_builder';

// Create connection
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

// Check connection
if ($conn->connect_error) {
    $_SESSION['error'] = "Database connection failed";
    header("Location: signin.html");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($conn->real_escape_string($_POST['email']));
    $password = $_POST['password'];
    $remember = isset($_POST['remember']) ? true : false;

    // Basic validation
    if (empty($email) || empty($password)) {
        $_SESSION['error'] = "Email and password are required";
        header("Location: signin.html");
        exit();
    }

    try {
        // Check user exists
        $stmt = $conn->prepare("SELECT id, full_name, password FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            throw new Exception("Invalid email or password");
        }

        $user = $result->fetch_assoc();
        
        // Verify password
        if (!password_verify($password, $user['password'])) {
            throw new Exception("Invalid email or password");
        }

        // Login successful
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['full_name'];
        $_SESSION['logged_in'] = true;

        // Remember me cookie (30 days)
        if ($remember) {
            setcookie('remember_user', $user['id'], time() + (86400 * 30), "/");
        }

        // Redirect to dashboard
        header("Location: dashboard.html");
        exit();
        
    } catch (Exception $e) {
        $_SESSION['error'] = $e->getMessage();
        $_SESSION['form_data'] = ['email' => $email];
        header("Location: signin.html");
        exit();
    }
}

$conn->close();
?>