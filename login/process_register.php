<?php
// Database configuration
$db_host = 'localhost'; // Database host
$db_username = 'root'; // Replace with your MySQL username (default is 'root')
$db_password = ''; // Replace with your MySQL password (default is empty for XAMPP)
$db_name = 'pc_builder'; // Database name

// Create database connection
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Process form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate CSRF token (in a real application, you would generate and validate this properly)
    if (!isset($_POST['csrf_token'])) {
        die("CSRF token missing");
    }

    // Get and sanitize input data
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Validate inputs
    $errors = [];
    
    // Check if passwords match
    if ($password !== $confirm_password) {
        $errors[] = "Passwords do not match.";
    }
    
    // Check password strength
    if (!preg_match("/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}$/", $password)) {
        $errors[] = "Password must contain at least one number, one uppercase and lowercase letter, and at least 8 characters.";
    }
    
    // Check if email is valid
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }
    
    // Check if terms were accepted
    if (!isset($_POST['terms'])) {
        $errors[] = "You must accept the terms and conditions.";
    }
    
    // Check if email already exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        $errors[] = "Email is already registered.";
    }
    $stmt->close();
    
    // If no errors, proceed with registration
    if (empty($errors)) {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // Insert user into database
        $stmt = $conn->prepare("INSERT INTO users (full_name, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $full_name, $email, $hashed_password);
        
        if ($stmt->execute()) {
            // Registration successful
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
                    <h2>Registration Successful!</h2>
                    <p>Your account has been created.<br>
                    Redirecting to sign-in page...</p>
                    <div class="spinner"></div>
                </div>
                <script>
                    setTimeout(function() {
                        window.location.href = "signin.html";
                    }, 3000); // Redirect after 3 seconds
                </script>
            </body>
            </html>
            ';
            exit();
        } else {
            $errors[] = "Something went wrong. Please try again later.";
        }
        $stmt->close();
    }
    
    // If there are errors, display them
    if (!empty($errors)) {
        // In a real application, you might want to redirect back to the form with error messages
        foreach ($errors as $error) {
            echo "<p>Error: $error</p>";
        }
        echo '<p><a href="register.html">Go back</a></p>';
    }
}

// Close connection
$conn->close();
?>