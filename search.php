<?php
require_once 'includes/db.php';
require_once 'includes/header.php';

$results = [];
$searched = false;
$query = '';

if (isset($_GET['q'])) {
    $searched = true;
    // Trim and sanitize the search input
    $query = trim($_GET['q']);

    if ($query !== '') {
        // Prepared statement with LIKE - prevents SQL injection
        $like = '%' . $query . '%';
        $stmt = $pdo->prepare(
            "SELECT id, name, email, keyprogramming 
             FROM cvs 
             WHERE name LIKE ? OR keyprogramming LIKE ?
             ORDER BY name ASC"
        );
        $stmt->execute([$like, $like]);
        $results = $stmt->fetchAll();
    }
}
?>

<h1>Search CVs</h1>

<form method="GET" action="search.php">
    <div class="search-bar">
        <input
            type="text"
            name="q"
            placeholder="Search by name or programming language..."
            value="<?= e($query) ?>"
            maxlength="100"
        >
        <button type="submit" class="btn">Search</button>
        <?php if ($searched): ?>
            <a href="search.php" class="btn" style="background:#6b7280;">Clear</a>
        <?php endif; ?>
    </div>
</form>

<?php if ($searched): ?>
    <?php if ($query === ''): ?>
        <div class="alert alert-error">Please enter a search term.</div>
    <?php elseif (empty($results)): ?>
        <div class="empty">
            <p>No CVs found matching "<strong><?= e($query) ?></strong>".</p>
            <p><a href="index.php">View all CVs</a></p>
        </div>
    <?php else: ?>
        <p style="color:#555; margin-bottom:1rem;">
            Found <strong><?= count($results) ?></strong> result(s) for "<strong><?= e($query) ?></strong>"
        </p>
        <div class="cv-grid">
            <?php foreach ($results as $cv): ?>
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
<?php else: ?>
    <p style="color:#666;">Enter a name or programming language above to search all CVs.</p>
<?php endif; ?>

<?php require_once 'includes/footer.php'; ?>
