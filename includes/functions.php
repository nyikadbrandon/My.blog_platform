<?php
require_once __DIR__ . '/../config/db.php';
function is_logged_in(){ return isset($_SESSION['user_id']); }
function current_user_id(){ return $_SESSION['user_id'] ?? null; }
function is_admin(){ return (($_SESSION['role'] ?? '')==='admin'); }
function e($s){ return htmlspecialchars($s??'',ENT_QUOTES,'UTF-8'); }
function redirect($p){ header("Location: ".BASE_URL.ltrim($p,'/')); exit; }
function get_trending_posts($a=3,$b=2){
  global $mysqli; $t=[];
  $r1=$mysqli->query("SELECT id,title,slug,featured_image,view_count,created_at FROM posts ORDER BY view_count DESC LIMIT ".intval($a));
  while($r1 && ($row=$r1->fetch_assoc())) $t[$row['id']]=$row;
  $r2=$mysqli->query("SELECT p.id,p.title,p.slug,p.featured_image,p.view_count,p.created_at,MAX(c.created_at) lc FROM posts p JOIN comments c ON c.post_id=p.id GROUP BY p.id ORDER BY lc DESC LIMIT ".intval($b));
  while($r2 && ($row=$r2->fetch_assoc())) $t[$row['id']]=$row;
  return array_values($t);
}
function get_categories(){ global $mysqli; $r=$mysqli->query("SELECT * FROM categories ORDER BY name ASC"); return $r?$r->fetch_all(MYSQLI_ASSOC):[]; }
function get_tags_for_post($id){ global $mysqli; $st=$mysqli->prepare("SELECT t.* FROM tags t JOIN post_tags pt ON pt.tag_id=t.id WHERE pt.post_id=? ORDER BY t.name"); $st->bind_param("i",$id); $st->execute(); $re=$st->get_result(); return $re?$re->fetch_all(MYSQLI_ASSOC):[]; }
function get_excerpt($h,$l=140){ $t=trim(strip_tags($h)); return (mb_strlen($t)>$l)?mb_substr($t,0,$l).'…':$t; }
function record_view($id){ global $mysqli; $st=$mysqli->prepare("UPDATE posts SET view_count=view_count+1 WHERE id=?"); $st->bind_param("i",$id); $st->execute(); }