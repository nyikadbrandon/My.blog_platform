<?php
function csrf_token(){ if(empty($_SESSION['csrf'])) $_SESSION['csrf']=bin2hex(random_bytes(32)); return $_SESSION['csrf']; }
function csrf_field(){ echo '<input type="hidden" name="csrf" value="'.htmlspecialchars(csrf_token(),ENT_QUOTES,'UTF-8').'">'; }
function csrf_verify(){ if(empty($_POST['csrf'])||empty($_SESSION['csrf'])||!hash_equals($_SESSION['csrf'],$_POST['csrf'])){ http_response_code(403); die('Bad CSRF'); } }