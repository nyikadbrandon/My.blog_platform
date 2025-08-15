<?php
require_once __DIR__ . '/includes/functions.php';
include __DIR__ . '/includes/header.php';
?>
<h1>Search</h1>
<form method="get" action="<?= BASE_URL ?>index.php">
  <input name="q" placeholder="Search posts...">
  <button>Search</button>
</form>
<?php include __DIR__ . '/includes/footer.php'; ?>