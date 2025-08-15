<?php
require_once __DIR__ . '/includes/functions.php';
$slug=$_GET['slug']??'';
$st=$mysqli->prepare("SELECT p.*, u.username FROM posts p JOIN users u ON u.id=p.author_id WHERE slug=? AND status='published'");
$st->bind_param("s",$slug); $st->execute(); $post=$st->get_result()->fetch_assoc();
if(!$post){ http_response_code(404); die('Post not found'); }
record_view($post['id']);
if($_SERVER['REQUEST_METHOD']==='POST' && is_logged_in()){
  require_once __DIR__ . '/includes/csrf.php'; csrf_verify();
  $content=trim($_POST['content']??''); if($content){
    $st=$mysqli->prepare("INSERT INTO comments(post_id,user_id,content,created_at) VALUES(?,?,?,NOW())");
    $uid=current_user_id(); $st->bind_param("iis",$post['id'],$uid,$content); $st->execute();
    header("Location: ".BASE_URL."post.php?slug=".$slug."#comments"); exit;
  }
}
include __DIR__ . '/includes/header.php'; $tags=get_tags_for_post($post['id']);
?>
<article class="post">
<h1><?= e($post['title']) ?></h1>
<div class="meta">By <?= e($post['username']) ?> • <?= date('M j, Y', strtotime($post['created_at'])) ?> • 👁 <?= (int)$post['view_count']+1 ?></div>
<?php if($post['featured_image']): ?><img src="<?= e($post['featured_image']) ?>" style="width:100%;max-height:420px;object-fit:cover;border-radius:12px;margin:10px 0"><?php endif; ?>
<div class="content"><?= $post['content'] ?></div>
<p><?php foreach($tags as $tg): ?><span class="badge">#<?= e($tg['name']) ?></span> <?php endforeach; ?></p>
</article>
<section id="comments">
<h3>Comments</h3>
<?php $r=$mysqli->prepare("SELECT c.*, u.username FROM comments c JOIN users u ON u.id=c.user_id WHERE c.post_id=? ORDER BY c.created_at DESC"); $r->bind_param("i",$post['id']); $r->execute(); $comments=$r->get_result()->fetch_all(MYSQLI_ASSOC); ?>
<?php foreach($comments as $c): ?>
  <div class="card" style="grid-column: span 12;padding:12px"><strong><?= e($c['username']) ?></strong><div class="meta"><?= date('M j, Y H:i', strtotime($c['created_at'])) ?></div><p><?= nl2br(e($c['content'])) ?></p></div>
<?php endforeach; ?>
<?php if(is_logged_in()): ?>
<form method="post"><?php include __DIR__ . '/includes/csrf.php'; csrf_field(); ?>
  <textarea name="content" rows="4" placeholder="Write a comment..." required></textarea>
  <button>Post Comment</button>
</form>
<?php else: ?><p><a class="button" href="<?= BASE_URL ?>auth/login.php">Sign in</a> to comment.</p><?php endif; ?>
</section>
<?php include __DIR__ . '/includes/footer.php'; ?>