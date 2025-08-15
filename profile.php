<?php
require_once __DIR__ . '/includes/functions.php';
if(!is_logged_in()) redirect('auth/login.php');
$uid=current_user_id(); $user=$mysqli->query("SELECT * FROM users WHERE id=".$uid)->fetch_assoc();
include __DIR__ . '/includes/header.php';
?>
<h1>Your Profile</h1>
<p><strong>Username:</strong> <?= e($user['username']) ?></p>
<p><strong>Email:</strong> <?= e($user['email']) ?></p>
<?php include __DIR__ . '/includes/footer.php'; ?>