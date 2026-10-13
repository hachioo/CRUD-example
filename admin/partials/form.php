<?php
$formAction  = $formAction  ?? 'create.php';
$submitLabel = $submitLabel ?? 'Save Post';

$val = static function (string $key, $default = '') use ($form) {
    return htmlspecialchars((string) ($form[$key] ?? $default), ENT_QUOTES);
};
$lines = static function (string $key) use ($form) {
    $v = $form[$key] ?? '';
    return htmlspecialchars(is_array($v) ? implode("\n", $v) : (string) $v, ENT_QUOTES);
};
?>
<form class="card" method="post" action="<?= htmlspecialchars($formAction) ?>">
  <div class="form-grid">

    <div class="field full">
      <label for="title">Title</label>
      <input type="text" id="title" name="title" value="<?= $val('title') ?>" required>
    </div>

    <div class="field">
      <label for="slug">Slug</label>
      <input type="text" id="slug" name="slug" value="<?= $val('slug') ?>" placeholder="auto from title">
      <span class="hint">Leave blank to generate it from the title.</span>
    </div>

    <div class="field">
      <label for="category">Category</label>
      <select id="category" name="category">
        <?php foreach (array_keys(POST_CATEGORIES) as $cat): ?>
          <option value="<?= htmlspecialchars($cat) ?>"<?= ($form['category'] ?? '') === $cat ? ' selected' : '' ?>>
            <?= htmlspecialchars($cat) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="field">
      <label for="author">Author</label>
      <input type="text" id="author" name="author" value="<?= $val('author', 'VoltCore Team') ?>">
    </div>

    <div class="field">
      <label for="post_date">Date</label>
      <input type="date" id="post_date" name="post_date" value="<?= $val('post_date', date('Y-m-d')) ?>">
    </div>

    <div class="field">
      <label for="read_time">Read time (minutes)</label>
      <input type="number" id="read_time" name="read_time" min="0" value="<?= $val('read_time', '5') ?>">
    </div>

    <div class="field">
      <label for="hero_img">Hero image path</label>
      <input type="text" id="hero_img" name="hero_img" value="<?= $val('hero_img') ?>" placeholder="img/example.png">
    </div>

    <div class="field">
      <label for="hero_gradient">Hero gradient</label>
      <input type="text" id="hero_gradient" name="hero_gradient"
             value="<?= $val('hero_gradient', 'linear-gradient(135deg,#1a0d08,#2d1506)') ?>">
    </div>

    <div class="field">
      <label for="tags">Tags (one per line)</label>
      <textarea id="tags" name="tags"><?= $lines('tags') ?></textarea>
    </div>

    <div class="field">
      <label for="toc">Table of contents (one per line)</label>
      <textarea id="toc" name="toc"><?= $lines('toc') ?></textarea>
    </div>

    <div class="field full">
      <label for="content">Content (HTML)</label>
      <textarea id="content" name="content" style="min-height:280px"><?= $val('content') ?></textarea>
      <span class="hint">Allowed HTML: &lt;h2 id="s0"&gt;, &lt;p&gt;, &lt;ul&gt;, &lt;pre&gt;&lt;code&gt;, &lt;blockquote&gt;, &lt;div class="info-box"&gt;.</span>
    </div>

  </div>

  <div class="form-actions">
    <button type="submit" class="btn btn-primary"><?= htmlspecialchars($submitLabel) ?></button>
    <a href="index.php" class="btn">Cancel</a>
  </div>
</form>
