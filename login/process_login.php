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

        // Show styled login success message before redirecting
        echo '
        <html>
        <head>
            <style>
                body {
                    background: #f4f6f8;
                    font-family: Arial, sans-serif;
                }
                .success-card {
                    max-width: 400px;
                    margin: 80px auto;
                    background: #fff;
                    border-radius: 10px;
                    box-shadow: 0 2px 12px rgba(0,0,0,0.08);
                    padding: 32px 24px 24px 24px;
                    text-align: center;
                }
                .success-icon {
                    color: #4BB543;
                    font-size: 48px;
                    margin-bottom: 16px;
                }
                .spinner {
                    margin: 18px auto 0 auto;
                    border: 4px solid #f3f3f3;
                    border-top: 4px solid #4BB543;
                    border-radius: 50%;
                    width: 36px;
                    height: 36px;
                    animation: spin 1s linear infinite;
                }
                @keyframes spin {
                    0% { transform: rotate(0deg);}
                    100% { transform: rotate(360deg);}
                }
            </style>
        </head>
        <body>
            <div class="success-card">
                <div class="success-icon">&#10004;</div>
                <h2>Login Successful!</h2>
                <p>Welcome back, ' . htmlspecialchars($user['full_name']) . '.<br>
                Redirecting to your dashboard...</p>
                <div class="spinner"></div>
            </div>
            <script>
                setTimeout(function() {
                    window.location.href = "/pc_builder/dashboard.html";
                }, 2000); // Redirect after 2 seconds
            </script>
        </body>
        </html>
        ';
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