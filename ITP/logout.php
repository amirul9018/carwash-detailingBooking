<?php
session_start();

// Unset all session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Clear user authentication cookies, if they exist
if (isset($_COOKIE['user_id'])) {
    setcookie('user_id', '', time() - 3600, '/'); // Expire the cookie
}
if (isset($_COOKIE['user_role'])) {
    setcookie('user_role', '', time() - 3600, '/'); // Expire the cookie
}

// Redirect to the login page with a logout message
header("Location: index.html?message=logged_out");
exit();
?>
