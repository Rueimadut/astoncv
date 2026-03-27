<?php
require_once 'includes/db.php';
require_once 'includes/auth.php';
startSecureSession();

// Security: Authorization - only logged-in users can access this page
requireLogin();

$userId  = $_SESSION['user_id'];
$errors  = [];
$success = '';

// Fetch the current user's CV
$stmt = $pdo->prepare("SELECT * FROM cvs WHERE id = ?");
$stmt->execute([$userId]);
$cv = $stmt->fetch();

if (!$cv) {
    die("Account not found.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // ---- Security: Validate CSRF token ----
    validateCsrfToken($_POST['csrf_token'] ?? '');

    // ---- Get and sanitize inputs ----
    $keyprogramming = trim($_POST['keyprogramming'] ?? '');
    $profile        = trim($_POST['profile'] ?? '');
    $education      = trim($_POST['education'] ?? '');
    $urllinks       = trim($_POST['URLlinks'] ?? '');

    // ---- Validation ----
    if (strlen($keyprogramming) > 255) {
        $errors[] = "Key programming language must be 255 characters or fewer.";
    }
    if (strlen($profile) > 500) {
        $errors[] = "Profile must be 500 characters or fewer.";
    }
    if (strlen($education) > 500) {
        $errors[] = "Education must be 500 characters or fewer.";
    }
    if (strlen($urllinks) > 500) {
        $errors[] = "URL links must be 500 characters or fewer.";
    }

    // Validate URLs if provided
    if ($urllinks !== '') {
        $links = preg_split('/[\n,]+/', $urllinks);
        foreach ($links as $link) {
            $link = trim($link);
            if ($link && !filter_var($link, FILTER_VALIDATE_URL)) {
                $errors[] = "Invalid URL detected: " . e($link);
            }
        }
    }

    // ---- Update DB if no errors ----
    if (empty($errors)) {
        // Security: Prepared statement prevents SQL injection
        // Security: Authorization check - WHERE id = $userId ensures users can only update their own CV
        $stmt = $pdo->prepare(
            "UPDATE cvs 
             SET keyprogramming = ?, profile = ?, education = ?, URLlinks = ?
             WHERE id = ?"
        );
        $stmt->execute([$keyprogramming, $profile, $education, $urllinks, $userId]);

        $success = "Your CV has been updated successfully!";

        // Refresh CV data
        $stmt = $pdo->prepare("SELECT * FROM cvs WHERE id = ?");
        $stmt->execute([$userId]);
        $cv = $stmt->fetch();
    }
}

require_once 'includes/header.php';
?>

<h1>My CV</h1>
<p style="color:#555; margin-bottom:1.5rem;">
    Logged in as <strong><?= e($_SESSION['user_name']) ?></strong> &mdash;
    <a href="view_cv.php?id=<?= (int)$userId ?>">View my public CV</a>
</p>

<div class="form-card wide">
    <?php if ($success): ?>
        <div class="alert alert-success"><?= e($success) ?></div>
    <?php endif; ?>

    <?php if ($errors): ?>
        <div class="alert alert-error">
            <?php foreach ($errors as $err): ?>
                <div><?= e($err) ?></div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="update_cv.php" novalidate>
        <!-- CSRF hidden token -->
        <input type="hidden" name="csrf_token" value="<?= e(generateCsrfToken()) ?>">

        <label>Full Name</label>
        <input type="text" value="<?= e($cv['name']) ?>" disabled style="background:#eee; color:#666;">
        <small style="color:#888;">Name cannot be changed here. Contact support if needed.</small>

        <label>Email Address</label>
        <input type="text" value="<?= e($cv['email']) ?>" disabled style="background:#eee; color:#666;">

        <label for="keyprogramming">Key Programming Language</label>
        <input type="text" id="keyprogramming" name="keyprogramming" maxlength="255"
               placeholder="e.g. Python, JavaScript, Java"
               value="<?= e($cv['keyprogramming'] ?? '') ?>">

        <label for="profile">Profile / About Me <small style="font-weight:normal;color:#888;">(max 500 chars)</small></label>
        <textarea id="profile" name="profile" maxlength="500"
                  placeholder="A short paragraph about yourself and your skills..."><?= e($cv['profile'] ?? '') ?></textarea>

        <label for="education">Education <small style="font-weight:normal;color:#888;">(max 500 chars)</small></label>
        <textarea id="education" name="education" maxlength="500"
                  placeholder="e.g. BSc Computer Science, Aston University, 2022-2025"><?= e($cv['education'] ?? '') ?></textarea>

        <label for="URLlinks">URL Links <small style="font-weight:normal;color:#888;">(one per line or comma-separated)</small></label>
        <textarea id="URLlinks" name="URLlinks" maxlength="500"
                  placeholder="https://github.com/yourname&#10;https://linkedin.com/in/yourname"><?= e($cv['URLlinks'] ?? '') ?></textarea>

        <button type="submit" class="btn">Save CV</button>
    </form>
</div>

<?php require_once 'includes/footer.php'; ?>
