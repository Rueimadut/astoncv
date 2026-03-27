<?php
// Database configuration - update these for your host server
define('DB_HOST', 'localhost');
define('DB_NAME', 'astoncv');
define('DB_USER', 'root');   // Change to your DB username
define('DB_PASS', '');       // Change to your DB password

try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false, // Use real prepared statements (SQL injection prevention)
        ]
    );
} catch (PDOException $e) {
    error_log("DB Connection failed: " . $e->getMessage());
    die("Database connection error. Please try again later.");
}
