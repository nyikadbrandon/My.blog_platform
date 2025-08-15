<?php
require_once __DIR__ . '/../includes/functions.php';
if(!is_admin()) redirect('../auth/login.php');
include __DIR__ . '/../includes/header.php';
$stats = [
  'posts' => $mysqli->query("SELECT COUNT(*) c FROM posts")->fetch_assoc()['c'],
  'users' => $mysqli->query("SELECT COUNT(*) c FROM users")->fetch_assoc()['c'],
  'comments' => $mysqli->query("SELECT COUNT(*) c FROM comments")->fetch_assoc()['c'],
];
?>
<h1>Admin Dashboard</h1>
<div class="grid">
  <div class="card"><div class="pad"><h3>Posts</h3><p><?= (int)$stats['posts'] ?></p></div></div>
  <div class="card"><div class="pad"><h3>Users</h3><p><?= (int)$stats['users'] ?></p></div></div>
  <div class="card"><div class="pad"><h3>Comments</h3><p><?= (int)$stats['comments'] ?></p></div></div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>