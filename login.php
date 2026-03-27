<?php
require_once 'includes/db.php';
require_once 'includes/auth.php';
startSecureSession();

// Redirect if already logged in
if (isLoggedIn()) {
    header("Location: update_cv.php");
    exit();
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // ---- Security: Validate CSRF token ----
    validateCsrfToken($_POST['csrf_token'] ?? '');

    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    // ---- Basic validation ----
    if ($email === '' || $password === '') {
        $errors[] = "Please enter both email and password.";
    }

    if (empty($errors)) {
        // Fetch user by email (prepared statement)
        $stmt = $pdo->prepare("SELECT id, name, password FROM cvs WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        // Security: Use password_verify() to check bcrypt hash
        if ($user && password_verify($password, $user['password'])) {
            // Regenerate session ID on login to prevent session fixation
            session_regenerate_id(true);

            $_SESSION['user_id']   = $user['id'];
            $_SESSION['user_name'] = $user['name'];

            header("Location: update_cv.php");
            exit();
        } else {
            // Deliberately vague error message (don't reveal which field is wrong)
            $errors[] = "Incorrect email or password. Please try again.";
        }
    }
}

require_once 'includes/header.php';
?>

<h1>Login</h1>

<div class="form-card">
    <?php if ($errors): ?>
        <div class="alert alert-error">
            <?php foreach ($errors as $err): ?>
                <div><?= e($err) ?></div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="login.php" novalidate>
        <!-- CSRF hidden token -->
        <input type="hidden" name="csrf_token" value="<?= e(generateCsrfToken()) ?>">

        <label for="email">Email Address</label>
        <input type="email" id="email" name="email"
               value="<?= e($_POST['email'] ?? '') ?>" required autofocus>

        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>

        <button type="submit" class="btn">Log In</button>
    </form>

    <div class="form-footer">
        Don't have an account? <a href="register.php">Register here</a>.
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
