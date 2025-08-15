<?php
require_once __DIR__ . '/includes/functions.php';
include __DIR__ . '/includes/header.php';

// Get query parameters
$q = trim($_GET['q'] ?? '');
$cat = intval($_GET['cat'] ?? 0);
$page = max(1, intval($_GET['page'] ?? 1));
$per = 9;
$off = ($page - 1) * $per;

// Base query
$where = "WHERE p.status='published'";
$params = [];
$types = "";

// Search filter
if ($q) {
    $where .= " AND (p.title LIKE CONCAT('%', ?, '%') OR p.content LIKE CONCAT('%', ?, '%'))";
    $params[] = $q;
    $params[] = $q;
    $types .= "ss";
}

// Category filter
if ($cat) {
    $where .= " AND EXISTS(
        SELECT 1 FROM post_categories pc 
        WHERE pc.post_id = p.id AND pc.category_id = ?
    )";
    $params[] = $cat;
    $types .= "i";
}

// Prepare SQL query
$sql = "SELECT SQL_CALC_FOUND_ROWS p.*, u.username 
        FROM posts p 
        JOIN users u ON u.id = p.author_id 
        $where 
        ORDER BY p.created_at DESC 
        LIMIT ? OFFSET ?";

$params[] = $per;
$params[] = $off;
$types .= "ii";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param($types, ...$params); // Spread operator works in PHP 7.4+
$stmt->execute();
$posts = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Pagination
$total = $mysqli->query("SELECT FOUND_ROWS() AS t")->fetch_assoc()['t'];
$pages = max(1, ceil($total / $per));

// Categories and trending posts
$cats = get_categories();
$trending = get_trending_posts();
?>

<div class="layout">
    <section>
        <form method="get">
            <input name="q" placeholder="Search posts..." value="<?= e($q) ?>">
            <select name="cat">
                <option value="0">All categories</option>
                <?php foreach ($cats as $c): ?>
                    <option value="<?= $c['id'] ?>" <?= $cat == $c['id'] ? 'selected' : '' ?>>
                        <?= e($c['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button>Search</button>
        </form>

        <div class="grid">
            <?php foreach ($posts as $p): ?>
                <article class="card">
                    <a href="<?= BASE_URL ?>post.php?slug=<?= e($p['slug']) ?>">
                        <img src="<?= $p['featured_image'] ? e($p['featured_image']) : BASE_URL . 'assets/img/favicon-light.png' ?>" alt="">
                    </a>
                    <div class="pad">
                        <div class="badges">
                            <span class="badge"><?= e($p['category_label'] ?? 'General') ?></span>
                        </div>
                        <h2 class="post-title">
                            <a href="<?= BASE_URL ?>post.php?slug=<?= e($p['slug']) ?>"><?= e($p['title']) ?></a>
                        </h2>
                        <p class="excerpt"><?= e(get_excerpt($p['content'])) ?></p>
                        <div class="meta">
                            <span><?= date('M j, Y', strtotime($p['created_at'])) ?> • by <?= e($p['username']) ?></span>
                            <span>👁 <?= (int)$p['view_count'] ?></span>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>

        <div style="margin:18px 0">
            <?php for ($i = 1; $i <= $pages; $i++): ?>
                <a class="button" 
                   href="?q=<?= urlencode($q) ?>&cat=<?= $cat ?>&page=<?= $i ?>" 
                   style="margin-right:6px;<?= $i == $page ? 'filter:brightness(1.15)' : '' ?>">
                   <?= $i ?>
                </a>
            <?php endfor; ?>
        </div>
    </section>

    <aside>
        <h3>🔥 Trending</h3>
        <?php foreach ($trending as $t): ?>
            <div class="card" style="grid-column: span 12; display:flex;gap:10px;align-items:center">
                <img src="<?= $t['featured_image'] ? e($t['featured_image']) : BASE_URL . 'assets/img/favicon-light.png' ?>" 
                     style="width:90px;height:70px">
                <div class="pad">
                    <a href="<?= BASE_URL ?>post.php?slug=<?= e($t['slug']) ?>">
                        <strong><?= e($t['title']) ?></strong>
                    </a>
                    <div class="meta">
                        <span><?= date('M j, Y', strtotime($t['created_at'])) ?></span>
                        <span>👁 <?= (int)$t['view_count'] ?></span>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </aside>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>