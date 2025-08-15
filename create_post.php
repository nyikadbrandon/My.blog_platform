<?php
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/csrf.php';
if (!is_logged_in()) redirect('auth/login.php');

$errors=[];
if($_SERVER['REQUEST_METHOD']==='POST'){
  csrf_verify();
  $title=trim($_POST['title']??'');
  $content=$_POST['content']??'';
  $category=intval($_POST['category']??0);
  $tags=trim($_POST['tags']??'');
  $slug = strtolower(preg_replace('/[^a-z0-9]+/','-', $title)).'-'.substr(md5(uniqid('',true)),0,6);

  $imgUrl = null;
  if(!empty($_FILES['featured']['name'])){
    $f=$_FILES['featured'];
    if($f['size']>2*1024*1024) $errors[]="Image too large (max 2MB).";
    $ext=strtolower(pathinfo($f['name'], PATHINFO_EXTENSION));
    if(!in_array($ext,['jpg','jpeg','png','webp'])) $errors[]="Invalid image type.";
    if(empty($errors)){
      $name = 'post_'.time().'_'.rand(1000,9999).'.'.$ext;
      move_uploaded_file($f['tmp_name'], UPLOAD_DIR.$name);
      $imgUrl = UPLOAD_URL.$name;
    }
  }

  if(!$errors && $title && $content){
    $status='published';
    $stmt=$mysqli->prepare("INSERT INTO posts(author_id,title,slug,content,featured_image,category_label,status,created_at,view_count) VALUES(?,?,?,?,?,?,?,NOW(),0)");
    $author=current_user_id();
    $catLabel = $category? ($mysqli->query("SELECT name FROM categories WHERE id=$category")->fetch_assoc()['name']??'General') : 'General';
    $stmt->bind_param("issssss", $author,$title,$slug,$content,$imgUrl,$catLabel,$status);
    $stmt->execute();
    $post_id = $stmt->insert_id;
    if($category){
      $st=$mysqli->prepare("INSERT INTO post_categories(post_id,category_id) VALUES(?,?)");
      $st->bind_param("ii",$post_id,$category); $st->execute();
    }
    if($tags){
      $arr = array_filter(array_map('trim', explode(',', $tags)));
      foreach($arr as $t){
        $t_lower = strtolower($t);
        $st=$mysqli->prepare("SELECT id FROM tags WHERE name=?");
        $st->bind_param("s",$t_lower); $st->execute();
        $id=null; $r=$st->get_result()->fetch_assoc();
        if($r){ $id=$r['id']; }
        else {
          $st2=$mysqli->prepare("INSERT INTO tags(name) VALUES(?)"); $st2->bind_param("s",$t_lower); $st2->execute(); $id=$st2->insert_id;
        }
        $st3=$mysqli->prepare("INSERT INTO post_tags(post_id,tag_id) VALUES(?,?)"); $st3->bind_param("ii",$post_id,$id); $st3->execute();
      }
    }
    redirect('post.php?slug='.$slug);
  }
}
$cats=get_categories();
include __DIR__ . '/includes/header.php';
?>
<h1>Write a post</h1>
<?php foreach($errors as $e): ?><div class="alert"><?= e($e) ?></div><?php endforeach; ?>
<form method="post" enctype="multipart/form-data">
  <?php csrf_field(); ?>
  <label>Title</label>
  <input name="title" required>
  <label>Category</label>
  <select name="category"><option value="0">General</option><?php foreach($cats as $c): ?><option value="<?=$c['id']?>"><?=e($c['name'])?></option><?php endforeach; ?></select>
  <label>Tags (comma separated)</label>
  <input name="tags" placeholder="design, ui, travel">
  <label>Featured Image</label>
  <input type="file" name="featured" accept=".jpg,.jpeg,.png,.webp">
  <label>Content</label>
  <textarea class="rte" name="content"></textarea>
  <button>Publish</button>
</form>
<?php include __DIR__ . '/includes/footer.php'; ?>