<?php
require_once __DIR__ . '/../lib/posts.php';

$posts     = all_posts();
$pageTitle = 'All Posts';

include __DIR__ . '/partials/header.php';
?>

<div class="admin-head">
  <h1>Posts <span style="color:var(--ink3);font-weight:500">(<?= count($posts) ?>)</span></h1>
  <a href="create.php" class="btn btn-primary">+ New Post</a>
</div>

<?php if (!$posts): ?>
  <div class="card">No posts yet. <a href="create.php" style="color:var(--accent)">Create the first one →</a></div>
<?php else: ?>
  <table class="posts">
    <thead>
      <tr>
        <th>Title</th>
        <th>Category</th>
        <th>Date</th>
        <th>Views</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($posts as $p): ?>
        <tr>
          <td>
            <strong><?= htmlspecialchars($p['title']) ?></strong><br>
            <span style="color:var(--ink3);font-size:0.74rem">/<?= htmlspecialchars($p['slug']) ?></span>
          </td>
          <td><span class="cat" style="color:<?= htmlspecialchars($p['catColor']) ?>"><?= htmlspecialchars($p['cat']) ?></span></td>
          <td><?= htmlspecialchars($p['date']) ?></td>
          <td><?= htmlspecialchars($p['views']) ?></td>
          <td class="actions">
            <a class="btn" href="../post.php?id=<?= urlencode($p['slug']) ?>" target="_blank">View</a>
            <a class="btn" href="edit.php?id=<?= $p['id'] ?>">Edit</a>
            <form method="post" action="delete.php" onsubmit="return confirm('Delete this post?');">
              <input type="hidden" name="id" value="<?= $p['id'] ?>">
              <button type="submit" class="btn btn-danger">Delete</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
<?php endif; ?>

<?php include __DIR__ . '/partials/footer.php'; ?>
