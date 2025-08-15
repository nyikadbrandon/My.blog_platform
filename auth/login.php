<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/csrf.php';
$errors=[];
if($_SERVER['REQUEST_METHOD']==='POST'){
  csrf_verify();
  $email=trim($_POST['email']??''); $password=$_POST['password']??'';
  if(!$email||!$password) $errors[]="All fields are required.";
  if(!$errors){
    $st=$mysqli->prepare("SELECT id,password_hash,role FROM users WHERE email=?");
    $st->bind_param("s",$email); $st->execute(); $re=$st->get_result();
    if($u=$re->fetch_assoc()){
      if(password_verify($password,$u['password_hash'])){ $_SESSION['user_id']=$u['id']; $_SESSION['role']=$u['role']; redirect('index.php'); }
      else $errors[]="Wrong email or password.";
    } else $errors[]="Wrong email or password.";
  }
}
include __DIR__ . '/../includes/header.php';
?>
<h1>Sign in</h1>
<?php foreach($errors as $e): ?><div class="alert"><?= e($e) ?></div><?php endforeach; ?>
<form method="post"><?php csrf_field(); ?>
  <label>Email</label><input name="email" type="email" required>
  <label>Password</label><input name="password" type="password" required>
  <button>Sign in</button>
</form>
<p>No account? <a href="<?= BASE_URL ?>auth/register.php">Register</a></p>
<?php include __DIR__ . '/../includes/footer.php'; ?>