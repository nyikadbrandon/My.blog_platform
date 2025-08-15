<?php require_once __DIR__ . '/../config/db.php'; ?>
<!doctype html><html lang="en" data-theme="light"><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>My.blog_platform</title>
<meta name="description" content="A modern, lively blog platform with a 3D gradient design.">
<link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css">
<link rel="icon" href="<?= BASE_URL ?>assets/img/favicon-light.png" media="(prefers-color-scheme: light)">
<link rel="icon" href="<?= BASE_URL ?>assets/img/favicon-dark.png" media="(prefers-color-scheme: dark)">
<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>window.BASE_URL="<?= BASE_URL ?>";</script>
</head><body>
<header class="site-header"><div class="container header-inner">
  <a class="logo" href="<?= BASE_URL ?>"><span class="logo-icon">📸</span><span class="logo-text">VividLens <strong>Agora</strong></span></a>
  <nav class="main-nav">
    <a href="<?= BASE_URL ?>">Home</a>
    <a href="<?= BASE_URL ?>create_post.php">Write</a>
    <a href="<?= BASE_URL ?>search.php">Search</a>
    <?php if(is_logged_in()): ?>
      <a href="<?= BASE_URL ?>profile.php">Profile</a>
      <?php if(is_admin()): ?><a href="<?= BASE_URL ?>admin/dashboard.php">Admin</a><?php endif; ?>
      <a href="<?= BASE_URL ?>auth/logout.php">Logout</a>
    <?php else: ?>
      <a href="<?= BASE_URL ?>auth/login.php">Sign In</a>
      <a class="btn" href="<?= BASE_URL ?>auth/register.php">Sign Up</a>
    <?php endif; ?>
    <button id="themeToggle" aria-label="Toggle dark mode">🌓</button>
  </nav>
</div></header>
<main class="container page-content">