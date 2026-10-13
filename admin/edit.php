<?php
require_once __DIR__ . '/../lib/posts.php';

$id   = (int) ($_GET['id'] ?? 0);
$post = find_post($id);

if (!$post) {
    http_response_code(404);
    $pageTitle = 'All Posts';
    include __DIR__ . '/partials/header.php';
    echo '<div class="card">Post not found. <a href="index.php" style="color:var(--accent)">← Back to posts</a></div>';
    include __DIR__ . '/partials/footer.php';
    exit;
}

$errors = [];
$form   = [
    'title'         => $post['title'],
    'slug'          => $post['slug'],
    'category'      => $post['cat'],
    'author'        => $post['author'],
    'post_date'     => $post['dateISO'],
    'read_time'     => $post['time'],
    'views'         => (string) $post['viewsRaw'],
    'hero_img'      => $post['heroImg'],
    'hero_gradient' => $post['heroGradient'],
    'tags'          => $post['tags'],
    'toc'           => $post['toc'],
    'content'       => $post['content'],
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = post_collect_input();

    if ($data['title'] === '') {
        $errors[] = 'Title is required.';
    }

    if (!$errors) {
        $base         = $data['slug'] !== '' ? post_slugify($data['slug']) : post_slugify($data['title']);
        $data['slug'] = post_unique_slug($base, $id);

        update_post($id, $data);

        header('Location: index.php?status=updated');
        exit;
    }

    $form = array_merge($form, $data);
}

$pageTitle   = 'Edit Post';
$formAction  = 'edit.php?id=' . $id;
$submitLabel = 'Save Changes';

include __DIR__ . '/partials/header.php';
?>

<div class="admin-head">
  <h1>Edit Post</h1>
  <a href="../post.php?id=<?= urlencode($post['slug']) ?>" class="btn" target="_blank">View ↗</a>
</div>

<?php if ($errors): ?>
  <div class="errors">
    <?php foreach ($errors as $e): ?>
      <div>• <?= htmlspecialchars($e) ?></div>
    <?php endforeach; ?>
  </div>
<?php endif; ?>

<?php include __DIR__ . '/partials/form.php'; ?>

<?php include __DIR__ . '/partials/footer.php'; ?>
