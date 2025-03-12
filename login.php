<?php
session_start();
include 'db.php'; // Database connection

// Set secure session cookie settings
error_reporting(0);
// session_set_cookie_params([
//     'lifetime' => 0,
//     'path' => '/',
//     'domain' => '',
//     'secure' => false,   // Enforce HTTPS (set to false only for localhost testing)
//     'httponly' => true, // Prevent JavaScript access
//     'samesite' => 'Strict'
// ]);

// Security Headers
header("X-Frame-Options: DENY");
header("X-Content-Type-Options: nosniff");
header("Strict-Transport-Security: max-age=31536000; includeSubDomains");

// Generate CSRF token if not set
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Initialize brute force protection (per username & IP)
$ip_address = $_SERVER['REMOTE_ADDR'];
if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = [];
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate CSRF token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Invalid CSRF token");
    }

    // Sanitize input
    $username = filter_var(trim($_POST['username']), FILTER_SANITIZE_STRING);
    $password = trim($_POST['password']);

    // Track attempts per username & IP
    $attempts = $_SESSION['login_attempts'][$username][$ip_address] ?? 0;
    if ($attempts >= 5) {
        $waitTime = pow(2, $attempts - 4) * 60; // Progressive delay (2, 4, 8, 16 mins)
        die("Too many failed attempts. Try again in $waitTime seconds.");
    }

    // Prepared statement to prevent SQL Injection
    $stmt = $conn->prepare("SELECT id, password FROM admins WHERE username=?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    $validLogin = false;
    if ($row = $result->fetch_assoc()) {
        // Verify password
        if (password_verify($password, $row['password'])) {
            $validLogin = true;
        }
    }

    if ($validLogin) {
        session_regenerate_id(true); // Prevent session fixation
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['login_attempts'][$username][$ip_address] = 0; // Reset attempts
        
        // Log successful login
        $stmt = $conn->prepare("INSERT INTO login_attempts (username, ip_address, login_time) VALUES (?, ?, NOW())");
        $stmt->bind_param("ss", $username, $ip_address);
        $stmt->execute();

        // Redirect to dashboard
        header('Location: dashboard.php');
        exit;
    }

    // Log failed attempt and increase counter
    $_SESSION['login_attempts'][$username][$ip_address] = $attempts + 1;

    // Log failed login attempt
    $stmt = $conn->prepare("INSERT INTO login_attempts (username, ip_address, login_time) VALUES (?, ?, NOW())");
    $stmt->bind_param("ss", $username, $ip_address);
    $stmt->execute();

    // Generic error message to prevent username enumeration
    $error = "Invalid username or password.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Security-Policy" content="default-src 'self'; style-src 'self' https://cdn.jsdelivr.net;">
    <title>Admin Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2>Admin Login</h2>
        <?php if (isset($error)) echo "<div class='alert alert-danger'>" . htmlspecialchars($error) . "</div>"; ?>
        <form action="login.php" method="POST">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            <input type="text" name="username" class="form-control mb-2" placeholder="Username" required>
            <input type="password" name="password" class="form-control mb-2" placeholder="Password" required>
            <button type="submit" class="btn btn-primary">Login</button>
        </form>
    </div>
</body>
</html>
