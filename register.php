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
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // ---- Security: Validate CSRF token ----
    validateCsrfToken($_POST['csrf_token'] ?? '');

    // ---- Get and sanitize inputs ----
    $name     = trim($_POST['name'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm_password'] ?? '';

    // ---- Form Validation ----
    if ($name === '') {
        $errors[] = "Full name is required.";
    } elseif (strlen($name) > 100) {
        $errors[] = "Name must be 100 characters or fewer.";
    }

    if ($email === '') {
        $errors[] = "Email address is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Please enter a valid email address.";
    } elseif (strlen($email) > 100) {
        $errors[] = "Email must be 100 characters or fewer.";
    }

    if ($password === '') {
        $errors[] = "Password is required.";
    } elseif (strlen($password) < 8) {
        $errors[] = "Password must be at least 8 characters.";
    }

    if ($password !== $confirm) {
        $errors[] = "Passwords do not match.";
    }

    // ---- Check for duplicate email ----
    if (empty($errors)) {
        $stmt = $pdo->prepare("SELECT id FROM cvs WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $errors[] = "An account with this email already exists.";
        }
    }

    // ---- Insert new user if no errors ----
    if (empty($errors)) {
        // Security: Hash the password with bcrypt
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        $stmt = $pdo->prepare(
            "INSERT INTO cvs (name, email, password) VALUES (?, ?, ?)"
        );
        $stmt->execute([$name, $email, $hashedPassword]);

        $success = "Account created successfully! <a href='login.php'>Log in now</a>.";
    }
}

require_once 'includes/header.php';
?>

<h1>Register</h1>

<div class="form-card">
    <?php if ($success): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php endif; ?>

    <?php if ($errors): ?>
        <div class="alert alert-error">
            <?php foreach ($errors as $err): ?>
                <div><?= e($err) ?></div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <?php if (!$success): ?>
    <form method="POST" action="register.php" novalidate>
        <!-- CSRF hidden token -->
        <input type="hidden" name="csrf_token" value="<?= e(generateCsrfToken()) ?>">

        <label for="name">Full Name *</label>
        <input type="text" id="name" name="name" maxlength="100"
               value="<?= e($_POST['name'] ?? '') ?>" required>

        <label for="email">Email Address *</label>
        <input type="email" id="email" name="email" maxlength="100"
               value="<?= e($_POST['email'] ?? '') ?>" required>

        <label for="password">Password * <small style="font-weight:normal;color:#888;">(min. 8 characters)</small></label>
        <input type="password" id="password" name="password" minlength="8" required>

        <label for="confirm_password">Confirm Password *</label>
        <input type="password" id="confirm_password" name="confirm_password" minlength="8" required>

        <button type="submit" class="btn">Create Account</button>
    </form>
    <?php endif; ?>

    <div class="form-footer">Already have an account? <a href="login.php">Log in here</a>.</div>
</div>

<?php require_once 'includes/footer.php'; ?>
