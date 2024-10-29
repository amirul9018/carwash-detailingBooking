<?php
session_start();
require 'config.php';

// Check if the user is already logged in via session or cookie
if (isset($_SESSION['user_id']) || (isset($_COOKIE['user_id']) && isset($_COOKIE['user_role']))) {
    // Redirect based on role
    if ($_SESSION['user_role'] == 'admin' || $_COOKIE['user_role'] == 'admin') {
        header("Location: admin_dashboard.php");
    } else {
        header("Location: customer_dashboard.php");
    }
    exit();
}

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Fetch user from the database
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    // Verify password
    if ($user && password_verify($password, $user['password'])) {
        // Set session variables
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_role'] = $user['role'];

        // Handle "Remember Me" functionality
        if (isset($_POST['remember'])) {
            setcookie('user_id', $user['id'], time() + (86400 * 30), "/"); // Expires in 30 days
            setcookie('user_role', $user['role'], time() + (86400 * 30), "/");
        }

        // Redirect based on role
        if ($user['role'] == 'admin') {
            header("Location: dashboard.php");
        } else {
            header("Location: customer_dashboard.php");
        }
        exit();
    } else {
        $error = "Invalid email or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        <?php if(isset($error)) { echo "<p style='color:red;'>$error</p>"; } ?>
        <form action="login.php" method="post">
            <div class="input-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="input-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
                <span id="togglePassword" class="toggle-password">Show</span>
            </div>
            <div class="input-group">
                <input type="checkbox" id="remember" name="remember">
                <label for="remember">Remember Me</label>
            </div>
            <button type="submit" name="login">Login</button>
        </form>
        <p>Don't have an account? <a href="register.php">Register</a></p>
        <p><a href="reset_request.php">Forgot your password?</a></p>
    </div>
    <script src="script.js"></script>
</body>
</html>
