<?php
require_once __DIR__ . '/../lib/posts.php';

$errors = [];
$form   = [
    'title'         => '',
    'slug'          => '',
    'category'      => 'Tutorials',
    'author'        => 'VoltCore Team',
    'post_date'     => date('Y-m-d'),
    'read_time'     => '5',
    'views'         => '0',
    'hero_img'      => '',
    'hero_gradient' => 'linear-gradient(135deg,#1a0d08,#2d1506)',
    'tags'          => [],
    'toc'           => [],
    'content'       => '',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = post_collect_input();

    if ($data['title'] === '') {
        $errors[] = 'Title is required.';
    }

    if (!$errors) {
        $base         = $data['slug'] !== '' ? post_slugify($data['slug']) : post_slugify($data['title']);
        $data['slug'] = post_unique_slug($base);

        create_post($data);

        header('Location: index.php?status=created');
        exit;
    }

    $form = array_merge($form, $data);
}

$pageTitle   = 'New Post';
$formAction  = 'create.php';
$submitLabel = 'Create Post';

include __DIR__ . '/partials/header.php';
?>

<div class="admin-head">
  <h1>New Post</h1>
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
