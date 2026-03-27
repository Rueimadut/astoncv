<?php
require_once 'includes/db.php';
require_once 'includes/header.php';

// Validate and sanitize the ID from URL
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    echo '<div class="alert alert-error">Invalid CV ID.</div>';
    require_once 'includes/footer.php';
    exit();
}

// Prepared statement prevents SQL injection
$stmt = $pdo->prepare("SELECT * FROM cvs WHERE id = ?");
$stmt->execute([$id]);
$cv = $stmt->fetch();

if (!$cv) {
    echo '<div class="alert alert-error">CV not found.</div>';
    require_once 'includes/footer.php';
    exit();
}
?>

<a href="index.php" class="back-link">&larr; Back to all CVs</a>

<div class="cv-detail">
    <h1><?= e($cv['name']) ?></h1>

    <div class="detail-row">
        <div class="detail-label">Email</div>
        <div class="detail-value"><a href="mailto:<?= e($cv['email']) ?>"><?= e($cv['email']) ?></a></div>
    </div>

    <?php if ($cv['keyprogramming']): ?>
    <div class="detail-row">
        <div class="detail-label">Key Programming Language</div>
        <div class="detail-value"><span class="tag"><?= e($cv['keyprogramming']) ?></span></div>
    </div>
    <?php endif; ?>

    <?php if ($cv['profile']): ?>
    <div class="detail-row">
        <div class="detail-label">Profile</div>
        <div class="detail-value"><?= e($cv['profile']) ?></div>
    </div>
    <?php endif; ?>

    <?php if ($cv['education']): ?>
    <div class="detail-row">
        <div class="detail-label">Education</div>
        <div class="detail-value"><?= e($cv['education']) ?></div>
    </div>
    <?php endif; ?>

    <?php if ($cv['URLlinks']): ?>
    <div class="detail-row">
        <div class="detail-label">Links</div>
        <div class="detail-value">
            <?php
            // Split multiple links by newline or comma and display each
            $links = preg_split('/[\n,]+/', $cv['URLlinks']);
            foreach ($links as $link):
                $link = trim($link);
                if ($link):
            ?>
                <a href="<?= e($link) ?>" target="_blank" rel="noopener noreferrer"><?= e($link) ?></a><br>
            <?php endif; endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <?php if (!$cv['profile'] && !$cv['education'] && !$cv['URLlinks']): ?>
        <p style="color:#888; font-style:italic;">This user has not added full CV details yet.</p>
    <?php endif; ?>
</div>

<?php require_once 'includes/footer.php'; ?>
