<?php
require_once __DIR__ . '/lib/posts.php';

$activePage  = 'Blog';
$footerEmail = 'support@voltcore.vn';

$slug  = trim((string) ($_GET['id'] ?? ''));
$post  = $slug !== '' ? find_post_by_slug($slug) : null;
$found = $post !== null;

if (!$found) {
    http_response_code(404);
    $post = [
        'id' => 0, 'slug' => '', 'title' => 'Article Not Found', 'cat' => 'Blog',
        'catColor' => '#7a7068', 'heroImg' => '', 'heroEmoji' => '',
        'heroGradient' => 'linear-gradient(135deg,#1a1612,#2d2620)', 'author' => 'VoltCore',
        'avatar' => '', 'date' => '–', 'time' => '–', 'views' => '–',
        'tags' => [], 'toc' => [],
        'content' => '<p>Sorry, this article could not be found. <a href="blog.php" style="color:var(--accent)">← Return to Blog</a></p>',
    ];
}

$related = $found ? related_posts($post['cat'], $post['slug'], 3) : [];
?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($post['title']) ?> – VoltCore Blog</title>
<link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<style>
:root {
  --bg:      #f5f2ec;
  --bg2:     #ede9e0;
  --ink:     #1a1612;
  --ink2:    #3d3630;
  --ink3:    #7a7068;
  --accent:  #c8410f;
  --accent2: #1a6b4a;
  --accent3: #2563a8;
  --border:  rgba(26,22,18,0.1);
  --border2: rgba(26,22,18,0.06);
  --card:    #fff;
}

* { margin:0; padding:0; box-sizing:border-box; }
html { scroll-behavior:smooth; }

body {
  background:var(--bg);
  color:var(--ink);
  font-family:'JetBrains Mono', monospace;
  line-height:1.6;
  overflow-x:hidden;
}

body::before {
  content:'';
  position:fixed; inset:0;
  background-image:url("data:image/svg+xml,%3Csvg viewBox='0 0 512 512' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.75' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.025'/%3E%3C/svg%3E");
  pointer-events:none; z-index:0;
}

/* NAV */
nav {
  position:fixed; top:0; width:100%; z-index:100;
  padding:0 5%; height:68px;
  display:flex; align-items:center; justify-content:space-between;
  background:rgba(245,242,236,0.92);
  backdrop-filter:blur(16px);
  border-bottom:1px solid var(--border);
}
.logo {
  font-family:'JetBrains Mono',monospace; font-weight:800; font-size:1.4rem;
  letter-spacing:-0.02em; color:var(--ink); text-decoration:none;
  display:flex; align-items:center; gap:8px;
}
.logo-mark {
  width:28px; height:28px; background:var(--accent);
  clip-path:polygon(50% 0%,100% 25%,100% 75%,50% 100%,0% 75%,0% 25%);
  display:flex; align-items:center; justify-content:center;
  font-size:12px; color:#fff;
}
.logo-mark img { width:18px; height:18px; display:block; }
.logo span { color:var(--accent); }
img.ico { display:block; flex-shrink:0; }
.nav-links { display:flex; gap:32px; list-style:none; }
.nav-links a {
  text-decoration:none; color:var(--ink3); font-size:0.85rem;
  font-weight:500; letter-spacing:0.04em; text-transform:uppercase; transition:color .2s;
}
.nav-links a:hover, .nav-links a.active { color:var(--accent); }
.nav-cta {
  background:var(--ink); color:#fff; border:none; border-radius:8px;
  padding:8px 20px; font-family:'JetBrains Mono',monospace;
  font-weight:700; font-size:0.82rem; cursor:pointer; transition:background .2s;
  text-decoration:none;
}
.nav-cta:hover { background:var(--accent); }

/* BREADCRUMB */
.breadcrumb {
  padding:100px 5% 0;
  display:flex; align-items:center; gap:8px;
  font-size:0.75rem; color:var(--ink3);
  position:relative; z-index:1;
}
.breadcrumb a { color:var(--ink3); text-decoration:none; transition:color .2s; }
.breadcrumb a:hover { color:var(--accent); }
.breadcrumb span { opacity:.5; }

/* PAGE WRAP */
.page-wrap {
  max-width:1200px; margin:0 auto;
  padding:0 5% 80px;
  display:grid;
  grid-template-columns:1fr 300px;
  gap:60px;
  position:relative; z-index:1;
  align-items:start;
}

/* ARTICLE */
article {
  padding-top:40px;
}

.post-tag {
  display:inline-flex; align-items:center; gap:8px;
  font-size:0.72rem; font-weight:700; letter-spacing:0.12em;
  text-transform:uppercase; color:var(--accent);
  margin-bottom:20px;
}
.post-tag::before {
  content:''; display:block; width:20px; height:1px; background:var(--accent);
}

article h1 {
  font-family:'JetBrains Mono',monospace;
  font-weight:800;
  font-size:clamp(2rem,4vw,3.2rem);
  line-height:1.1;
  letter-spacing:-0.03em;
  color:var(--ink);
  margin-bottom:24px;
}

.post-meta-row {
  display:flex; flex-wrap:wrap; align-items:center; gap:16px;
  font-size:0.8rem; color:var(--ink3);
  padding-bottom:28px;
  border-bottom:1px solid var(--border);
  margin-bottom:36px;
}
.meta-author {
  display:flex; align-items:center; gap:8px;
  font-weight:600; color:var(--ink2);
}
.meta-avatar {
  width:32px; height:32px; border-radius:50%;
  background:var(--bg2); border:1px solid var(--border);
  display:flex; align-items:center; justify-content:center; font-size:16px;
}
.meta-avatar img { width:18px; height:18px; display:block; }

/* Hero image placeholder */
.post-hero-img {
  width:100%; aspect-ratio:16/7; border-radius:20px;
  background:linear-gradient(135deg,#1a0d08,#2d1506);
  display:flex; align-items:center; justify-content:center;
  font-size:5rem; margin-bottom:40px;
  border:1px solid var(--border);
  overflow:hidden;
}

/* Article body */
.post-content h2 {
  font-family:'JetBrains Mono',monospace; font-weight:700;
  font-size:1.5rem; letter-spacing:-0.02em;
  color:var(--ink); margin:44px 0 16px;
  padding-left:16px; border-left:3px solid var(--accent);
}
.post-content h3 {
  font-family:'JetBrains Mono',monospace; font-weight:700;
  font-size:1.1rem; color:var(--ink); margin:28px 0 12px;
}
.post-content p {
  font-size:0.92rem; color:var(--ink2); line-height:1.85;
  margin-bottom:20px;
}
.post-content ul, .post-content ol {
  padding-left:24px; margin-bottom:20px;
}
.post-content li {
  font-size:0.92rem; color:var(--ink2); line-height:1.75; margin-bottom:8px;
}
.post-content code {
  font-family:'JetBrains Mono',monospace;
  background:var(--bg2); border:1px solid var(--border);
  border-radius:5px; padding:2px 8px; font-size:0.84rem; color:var(--accent);
}
.post-content pre {
  background:var(--ink); border-radius:14px; padding:24px;
  overflow-x:auto; margin:28px 0;
  border:1px solid rgba(255,255,255,0.06);
}
.post-content pre code {
  background:none; border:none; padding:0;
  color:#e8e0d5; font-size:0.85rem; line-height:1.7;
}
.post-content blockquote {
  border-left:3px solid var(--accent2);
  padding:16px 20px; margin:28px 0;
  background:rgba(26,107,74,0.06); border-radius:0 10px 10px 0;
}
.post-content blockquote p { color:var(--ink2); font-style:italic; margin:0; }

/* Info box */
.info-box {
  background:rgba(37,99,168,0.06);
  border:1px solid rgba(37,99,168,0.2);
  border-radius:14px; padding:20px 24px; margin:28px 0;
  display:flex; gap:14px; align-items:flex-start;
}
.info-box .icon { font-size:1.4rem; flex-shrink:0; margin-top:2px; }
.info-box p { margin:0; font-size:0.88rem; color:var(--ink2); line-height:1.7; }

/* Tags row */
.post-tags {
  display:flex; flex-wrap:wrap; gap:8px;
  margin:48px 0 0; padding-top:32px; border-top:1px solid var(--border);
}
.post-tag-chip {
  font-size:0.74rem; font-weight:600; color:var(--ink2);
  background:var(--bg2); border:1px solid var(--border);
  padding:5px 14px; border-radius:100px; cursor:pointer;
  transition:all .2s;
}
.post-tag-chip:hover { background:var(--ink); color:#fff; border-color:var(--ink); }

/* Share row */
.share-row {
  display:flex; align-items:center; gap:12px;
  margin-top:28px; padding-top:24px; border-top:1px solid var(--border);
}
.share-label { font-size:0.78rem; font-weight:700; text-transform:uppercase; letter-spacing:.1em; color:var(--ink3); }
.share-btn {
  width:36px; height:36px; border-radius:10px; border:1px solid var(--border);
  background:var(--card); display:flex; align-items:center; justify-content:center;
  cursor:pointer; font-size:1rem; transition:all .2s; text-decoration:none;
}
.share-btn:hover { background:var(--ink); border-color:var(--ink); }

/* SIDEBAR */
.sidebar { position:sticky; top:100px; }

.sidebar-card {
  background:var(--card); border:1px solid var(--border);
  border-radius:18px; padding:24px; margin-bottom:20px;
}
.sidebar-head {
  font-family:'JetBrains Mono',monospace; font-weight:800;
  font-size:0.82rem; text-transform:uppercase; letter-spacing:.1em;
  color:var(--ink); margin-bottom:18px;
  display:flex; align-items:center; gap:8px;
}
.sidebar-head .ico { width:15px; height:15px; display:block; flex-shrink:0; }
.meta-ico { width:14px; height:14px; display:block; }
.meta-item { display:inline-flex; align-items:center; gap:6px; }

/* TOC */
.toc-list { list-style:none; }
.toc-list li { margin-bottom:10px; }
.toc-list a {
  text-decoration:none; font-size:0.82rem; color:var(--ink3);
  transition:color .2s; display:flex; gap:8px; align-items:baseline;
}
.toc-list a:hover { color:var(--accent); }
.toc-num { font-size:0.68rem; font-weight:700; color:var(--border); min-width:18px; }

/* Related */
.related-item {
  display:flex; gap:12px; padding:12px 0;
  border-bottom:1px solid var(--border2); cursor:pointer;
  transition:all .2s;
}
.related-item:last-child { border-bottom:none; padding-bottom:0; }
.related-item:hover h4 { color:var(--accent); }
.rel-thumb {
  width:56px; height:44px; border-radius:8px; flex-shrink:0;
}
.related-item h4 {
  font-size:0.82rem; font-weight:600; line-height:1.35;
  color:var(--ink); transition:color .2s; margin-bottom:4px;
}
.related-item span { font-size:0.7rem; color:var(--ink3); }

/* Progress bar */
.progress-bar {
  position:fixed; top:68px; left:0; height:2px;
  background:var(--accent); width:0%; z-index:99;
  transition:width .1s linear;
}

/* Back btn */
.back-btn {
  display:inline-flex; align-items:center; gap:8px;
  color:var(--ink3); text-decoration:none; font-size:0.82rem;
  font-weight:600; transition:color .2s; margin-bottom:32px;
}
.back-btn:hover { color:var(--accent); }

/* FOOTER */
footer {
  background:var(--ink); color:rgba(255,255,255,.7);
  padding:60px 5% 30px; position:relative; z-index:1;
}
.footer-top {
  display:flex; justify-content:space-between; align-items:center;
  padding-bottom:40px; border-bottom:1px solid rgba(255,255,255,.08); margin-bottom:40px;
}
.footer-logo {
  font-family:'JetBrains Mono',monospace; font-weight:800; font-size:1.4rem;
  color:#fff; text-decoration:none; display:flex; align-items:center; gap:8px;
}
.footer-logo span { color:var(--accent); }
.footer-links { display:flex; gap:28px; }
.footer-links a { color:rgba(255,255,255,.5); text-decoration:none; font-size:.84rem; transition:color .2s; }
.footer-links a:hover { color:#fff; }
.footer-bottom { display:flex; justify-content:space-between; font-size:.8rem; color:rgba(255,255,255,.35); }
</style>
</head>
<body>

<div class="progress-bar" id="progressBar"></div>

<?php include __DIR__ . '/includes/nav.php'; ?>

<div class="breadcrumb">
  <a href="main.html">Home</a>
  <span>›</span>
  <a href="blog.php">Blog</a>
  <span>›</span>
  <span><?= htmlspecialchars($post['cat']) ?></span>
</div>

<div class="page-wrap">

  <!-- ARTICLE -->
  <article id="articleArea">
    <a href="blog.php" class="back-btn">← Back to Blog</a>

    <div class="post-tag" style="color:<?= htmlspecialchars($post['catColor']) ?>"><?= htmlspecialchars($post['cat']) ?></div>

    <h1><?= htmlspecialchars($post['title']) ?></h1>

    <div class="post-meta-row">
      <div class="meta-author">
        <div class="meta-avatar"><img src="assets/userlogo.svg" alt=""></div>
        <span><?= htmlspecialchars($post['author']) ?></span>
      </div>
      <span class="meta-item"><img class="meta-ico" src="assets/dates.svg" alt=""><span><?= htmlspecialchars($post['date']) ?></span></span>
      <span>·</span>
      <span><?= htmlspecialchars($post['time']) ?> min read</span>
      <span>·</span>
      <span><?= htmlspecialchars($post['views']) ?> reads</span>
    </div>

    <div class="post-hero-img"<?= $post['heroImg'] !== ''
        ? " style=\"background-image:url('" . htmlspecialchars($post['heroImg']) . "');background-size:cover;background-position:center;\""
        : ' style="background:' . htmlspecialchars($post['heroGradient']) . ';"' ?>><?= $post['heroImg'] === '' ? '<img src="assets/logo.svg" alt="" style="width:96px;height:96px">' : '' ?></div>

    <div class="post-content"><?= $post['content'] ?></div>

    <div class="post-tags">
      <?php foreach ($post['tags'] as $tag): ?>
        <a class="post-tag-chip" href="blog.php"><?= htmlspecialchars($tag) ?></a>
      <?php endforeach; ?>
    </div>

    <div class="share-row">
      <span class="share-label">Share</span>
      <a class="share-btn" title="Copy link" onclick="copyLink()"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg></a>
      <a class="share-btn" title="Twitter/X" href="#" target="_blank">𝕏</a>
      <a class="share-btn" title="Facebook"  href="#" target="_blank">f</a>
    </div>
  </article>

  <!-- SIDEBAR -->
  <aside class="sidebar">

    <div class="sidebar-card">
      <div class="sidebar-head"><img class="ico" src="assets/topics.svg" alt="">Contents</div>
      <ul class="toc-list">
        <?php if ($post['toc']): ?>
          <?php foreach ($post['toc'] as $i => $item): ?>
            <li><a href="#s<?= $i ?>"><span class="toc-num"><?= str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT) ?></span><?= htmlspecialchars($item) ?></a></li>
          <?php endforeach; ?>
        <?php else: ?>
          <li><span style="color:var(--ink3);font-size:.8rem">No sections</span></li>
        <?php endif; ?>
      </ul>
    </div>

    <div class="sidebar-card">
      <div class="sidebar-head"><img class="ico" src="assets/userlogo.svg" alt="">Related Posts</div>
      <div>
        <?php if ($related): ?>
          <?php foreach ($related as $rp): ?>
            <div class="related-item" onclick="window.location.href='post.php?id=<?= urlencode($rp['slug']) ?>'">
              <div class="rel-thumb" style="<?= $rp['heroImg'] !== ''
                  ? "background-image:url('" . htmlspecialchars($rp['heroImg']) . "');background-size:cover;background-position:center;"
                  : 'background:' . htmlspecialchars($rp['heroGradient']) . ';' ?>display:flex;align-items:center;justify-content:center"><?= $rp['heroImg'] === '' ? '<img src="assets/logo.svg" alt="" style="width:24px;height:24px">' : '' ?></div>
              <div>
                <h4><?= htmlspecialchars($rp['title']) ?></h4>
                <span><?= htmlspecialchars($rp['time']) ?> min read · <?= htmlspecialchars($rp['cat']) ?></span>
              </div>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <p style="font-size:.8rem;color:var(--ink3)">No related posts yet.</p>
        <?php endif; ?>
      </div>
    </div>

    <div class="sidebar-card" style="background:var(--ink);border-color:var(--ink)">
      <div class="sidebar-head" style="color:#fff"><img class="ico" src="assets/Newsletter.svg" alt="">Newsletter</div>
      <p style="font-size:.82rem;color:rgba(255,255,255,.6);line-height:1.65;margin-bottom:16px">
        Get new articles in your inbox every week.
      </p>
      <input type="email" placeholder="your@email.com"
        style="width:100%;background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.15);border-radius:10px;padding:9px 12px;color:#fff;font-family:'JetBrains Mono',monospace;font-size:.82rem;outline:none;margin-bottom:10px">
      <button style="width:100%;background:var(--accent);color:#fff;border:none;border-radius:10px;padding:10px;font-family:'JetBrains Mono',monospace;font-weight:700;font-size:.82rem;cursor:pointer">
        Subscribe
      </button>
    </div>

  </aside>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>

<script>
/* ══ READING PROGRESS ══ */
window.addEventListener('scroll', () => {
  const article = document.getElementById('articleArea');
  if (!article) return;
  const rect  = article.getBoundingClientRect();
  const total = article.offsetHeight || 1;
  const read  = Math.max(0, -rect.top);
  const pct   = Math.min(100, (read / total) * 100);
  document.getElementById('progressBar').style.width = pct + '%';
});

/* ══ COPY LINK ══ */
const LINK_ICON = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>';
const CHECK_ICON = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>';
function copyLink() {
  navigator.clipboard.writeText(window.location.href).then(() => {
    const btn = document.querySelector('[title="Copy link"]');
    btn.innerHTML = CHECK_ICON;
    setTimeout(() => { btn.innerHTML = LINK_ICON; }, 1500);
  });
}
</script>
</body>
</html>
