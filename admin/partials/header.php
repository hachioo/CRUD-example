<?php
require_once __DIR__ . '/../../lib/posts.php';

$pageTitle = $pageTitle ?? 'Posts';
$status    = $_GET['status'] ?? '';
$messages  = [
    'created' => 'Post created successfully.',
    'updated' => 'Post updated successfully.',
    'deleted' => 'Post deleted.',
];
?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($pageTitle) ?> – VoltCore Admin</title>
<link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<style>
:root {
  --bg:#f5f2ec; --ink:#1a1612; --ink2:#3d3630; --ink3:#7a7068;
  --accent:#c8410f; --border:rgba(26,22,18,0.1); --card:#fff;
}
* { margin:0; padding:0; box-sizing:border-box; }
body {
  background:var(--bg); color:var(--ink);
  font-family:'JetBrains Mono',monospace; line-height:1.6;
}
a { color:inherit; }
.admin-bar {
  position:sticky; top:0; z-index:10;
  display:flex; align-items:center; justify-content:space-between;
  gap:20px; padding:0 32px; height:64px;
  background:var(--ink); color:#fff;
}
.admin-logo { font-weight:800; font-size:1.15rem; text-decoration:none; letter-spacing:-0.02em; }
.admin-logo span { color:var(--accent); }
.admin-bar nav { display:flex; gap:22px; }
.admin-bar nav a {
  color:rgba(255,255,255,0.65); text-decoration:none;
  font-size:0.8rem; font-weight:600; letter-spacing:0.04em; text-transform:uppercase;
  transition:color .2s;
}
.admin-bar nav a:hover, .admin-bar nav a.active { color:#fff; }
.admin-wrap { max-width:1100px; margin:0 auto; padding:40px 32px 80px; }
.admin-head { display:flex; align-items:center; justify-content:space-between; gap:20px; margin-bottom:28px; }
.admin-head h1 { font-size:1.6rem; font-weight:800; letter-spacing:-0.02em; }
.btn {
  display:inline-flex; align-items:center; gap:8px;
  border:1px solid var(--border); border-radius:10px; padding:9px 18px;
  font-family:'JetBrains Mono',monospace; font-weight:700; font-size:0.8rem;
  text-decoration:none; cursor:pointer; transition:all .2s; background:var(--card); color:var(--ink);
}
.btn:hover { background:var(--ink); color:#fff; border-color:var(--ink); }
.btn-primary { background:var(--accent); border-color:var(--accent); color:#fff; }
.btn-primary:hover { background:#a8340b; border-color:#a8340b; }
.btn-danger { color:var(--accent); }
.btn-danger:hover { background:var(--accent); border-color:var(--accent); color:#fff; }
.flash {
  padding:12px 18px; border-radius:12px; margin-bottom:24px;
  background:rgba(26,107,74,0.1); border:1px solid rgba(26,107,74,0.25);
  color:#1a6b4a; font-size:0.84rem; font-weight:600;
}
table.posts { width:100%; border-collapse:collapse; background:var(--card);
  border:1px solid var(--border); border-radius:16px; overflow:hidden; }
table.posts th, table.posts td { padding:14px 18px; text-align:left; font-size:0.84rem; }
table.posts th {
  background:var(--bg); font-size:0.72rem; text-transform:uppercase;
  letter-spacing:0.08em; color:var(--ink3); border-bottom:1px solid var(--border);
}
table.posts tr + tr td { border-top:1px solid var(--border); }
table.posts td.actions { white-space:nowrap; display:flex; gap:8px; }
td .cat { font-size:0.7rem; font-weight:700; text-transform:uppercase; letter-spacing:0.06em; }
.card { background:var(--card); border:1px solid var(--border); border-radius:16px; padding:28px; }
.form-grid { display:grid; grid-template-columns:1fr 1fr; gap:18px; }
.field { display:flex; flex-direction:column; gap:6px; }
.field.full { grid-column:1 / -1; }
.field label { font-size:0.74rem; font-weight:700; text-transform:uppercase; letter-spacing:0.06em; color:var(--ink3); }
.field input, .field select, .field textarea {
  font-family:'JetBrains Mono',monospace; font-size:0.85rem; color:var(--ink);
  background:var(--bg); border:1px solid var(--border); border-radius:10px;
  padding:10px 12px; outline:none; transition:border-color .2s; width:100%;
}
.field input:focus, .field select:focus, .field textarea:focus { border-color:var(--accent); }
.field textarea { resize:vertical; min-height:110px; line-height:1.6; }
.field .hint { font-size:0.7rem; color:var(--ink3); }
.form-actions { display:flex; gap:12px; margin-top:24px; }
.errors {
  padding:12px 18px; border-radius:12px; margin-bottom:24px;
  background:rgba(200,65,15,0.08); border:1px solid rgba(200,65,15,0.25);
  color:var(--accent); font-size:0.84rem;
}
</style>
</head>
<body>

<header class="admin-bar">
  <a href="index.php" class="admin-logo">Volt<span>Core</span> Admin</a>
  <nav>
    <a href="index.php"<?= $pageTitle === 'All Posts' ? ' class="active"' : '' ?>>Posts</a>
    <a href="create.php"<?= $pageTitle === 'New Post' ? ' class="active"' : '' ?>>New Post</a>
    <a href="../blog.php" target="_blank">View Blog ↗</a>
  </nav>
</header>

<main class="admin-wrap">
<?php if (isset($messages[$status])): ?>
  <div class="flash"><?= htmlspecialchars($messages[$status]) ?></div>
<?php endif; ?>
