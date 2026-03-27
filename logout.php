<?php
require_once 'includes/auth.php';
startSecureSession();

// Security: Destroy the session completely on logout
$_SESSION = [];
session_destroy();

// Delete the session cookie
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Redirect to home page
header("Location: index.php");
exit();
