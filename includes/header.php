<?php
require_once __DIR__ . '/auth.php';
startSecureSession();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AstonCV – Programmer CV Database</title>
    <link rel="stylesheet" href="<?= isset($rootPath) ? $rootPath : '' ?>css/style.css">
</head>
<body>
<header>
    <div class="header-inner">
        <a href="<?= isset($rootPath) ? $rootPath : '' ?>index.php" class="logo">AstonCV</a>
        <nav>
            <a href="<?= isset($rootPath) ? $rootPath : '' ?>index.php">Home</a>
            <a href="<?= isset($rootPath) ? $rootPath : '' ?>search.php">Search</a>
            <?php if (isLoggedIn()): ?>
                <a href="<?= isset($rootPath) ? $rootPath : '' ?>update_cv.php">My CV</a>
                <a href="<?= isset($rootPath) ? $rootPath : '' ?>logout.php">Logout</a>
            <?php else: ?>
                <a href="<?= isset($rootPath) ? $rootPath : '' ?>login.php">Login</a>
                <a href="<?= isset($rootPath) ? $rootPath : '' ?>register.php">Register</a>
            <?php endif; ?>
        </nav>
    </div>
</header>
<main>
