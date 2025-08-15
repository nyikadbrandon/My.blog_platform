<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/csrf.php';
$errors=[];
if($_SERVER['REQUEST_METHOD']==='POST'){
  csrf_verify();
  $username=trim($_POST['username']??''); $email=trim($_POST['email']??''); $password=$_POST['password']??'';
  if(!$username||!$email||!$password) $errors[]="All fields are required.";
  if($email && !filter_var($email,FILTER_VALIDATE_EMAIL)) $errors[]="Invalid email.";
  if(!$errors){
    $st=$mysqli->prepare("SELECT id FROM users WHERE email=? OR username=?");
    $st->bind_param("ss",$email,$username); $st->execute(); $st->store_result();
    if($st->num_rows>0) $errors[]="Email or username already exists.";
    else{
      $hash=password_hash($password,PASSWORD_DEFAULT);
      $role='author'; $count=$mysqli->query("SELECT COUNT(*) c FROM users")->fetch_assoc()['c']; if((int)$count===0) $role='admin';
      $st=$mysqli->prepare("INSERT INTO users(username,email,password_hash,role,created_at) VALUES(?,?,?,?,NOW())");
      $st->bind_param("ssss",$username,$email,$hash,$role); $st->execute();
      $_SESSION['user_id']=$st->insert_id; $_SESSION['role']=$role;
      redirect('index.php');
    }
  }
}
include __DIR__ . '/../includes/header.php';
?>
<h1>Create your account</h1>
<?php foreach($errors as $e): ?><div class="alert"><?= e($e) ?></div><?php endforeach; ?>
<form method="post"><?php csrf_field(); ?>
  <label>Username</label><input name="username" required>
  <label>Email</label><input name="email" type="email" required>
  <label>Password</label><input name="password" type="password" required>
  <button>Create account</button>
</form>
<?php include __DIR__ . '/../includes/footer.php'; ?>