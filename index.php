<?php
require_once 'includes/db.php';
require_once 'includes/header.php';

// Fetch all CVs
$stmt = $pdo->query("SELECT id, name, email, keyprogramming FROM cvs ORDER BY id DESC");
$cvs = $stmt->fetchAll();
?>

<div class="hero">
    <h1>Welcome to AstonCV</h1>
    <p>Browse and search CVs of talented programmers at Aston University.</p>
    <?php if (!isLoggedIn()): ?>
        <a href="register.php" class="btn">Register &amp; Add Your CV</a>
    <?php else: ?>
        <a href="update_cv.php" class="btn">Update My CV</a>
    <?php endif; ?>
</div>

<h2>All CVs (<?= count($cvs) ?>)</h2>

<?php if (empty($cvs)): ?>
    <div class="empty">
        <p>No CVs yet. <a href="register.php">Register</a> to add yours!</p>
    </div>
<?php else: ?>
    <div class="cv-grid">
        <?php foreach ($cvs as $cv): ?>
            <div class="cv-card">
                <h3><?= e($cv['name']) ?></h3>
                <div class="email"><?= e($cv['email']) ?></div>
                <?php if ($cv['keyprogramming']): ?>
                    <span class="tag"><?= e($cv['keyprogramming']) ?></span>
                <?php endif; ?>
                <br>
                <a href="view_cv.php?id=<?= (int)$cv['id'] ?>" class="btn-sm">View CV</a>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php require_once 'includes/footer.php'; ?>
