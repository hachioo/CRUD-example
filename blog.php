<?php
require_once __DIR__ . '/lib/posts.php';

$activePage  = 'Blog';
$footerEmail = 'hakidabest@gmail.com';

$allPosts = all_posts();
$featured = $allPosts[0] ?? null;
$cards    = array_slice($allPosts, 1, 6);
$more     = array_slice($allPosts, 7);
?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Blog – VoltCore Electronics Components</title>
<link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="css/blog.css">
<style>
  .filter-pill[data-cat="Tutorials"].active   { background:#c8410f; border-color:#c8410f; color:#fff; }
  .filter-pill[data-cat="Reviews"].active     { background:#1a6b4a; border-color:#1a6b4a; color:#fff; }
  .filter-pill[data-cat="Technical"].active   { background:#2563a8; border-color:#2563a8; color:#fff; }
  .filter-pill[data-cat="Tips & Tricks"].active { background:#7c3aed; border-color:#7c3aed; color:#fff; }
  .filter-pill[data-cat="AI & IoT"].active    { background:#0891b2; border-color:#0891b2; color:#fff; }
  .filter-pill[data-cat="Projects"].active    { background:#b45309; border-color:#b45309; color:#fff; }

  .post-card.hidden, .post-horizontal.hidden { display:none; }

  .no-results {
    display: none; grid-column:1/-1; text-align:center;
    padding:60px 20px; color:var(--ink3); font-size:0.95rem;
  }
  .no-results.show { display:block; }

  .topic.active { background:var(--ink); color:#fff; border-color:var(--ink); }

  .sort-row {
    display:flex; align-items:center; justify-content:space-between; margin-bottom:28px;
  }
  .sort-select {
    background:var(--card); border:1px solid var(--border); border-radius:10px;
    padding:7px 14px; font-family:'JetBrains Mono',monospace;
    font-size:0.8rem; color:var(--ink); cursor:pointer; outline:none;
  }
  .result-count { font-size:0.78rem; color:var(--ink3); padding:12px 0 0; display:block; min-height:28px; }

  .featured-section.hidden { display:none; }

  .show-more-topics {
    font-size:0.74rem; color:var(--accent); cursor:pointer; margin-top:8px;
    background:none; border:none; font-family:'JetBrains Mono',monospace;
    font-weight:700; padding:0;
  }
  .topic.extra { display:none; }
  .topic.extra.visible { display:inline-block; }
  .post-card { cursor:pointer; }
  .post-horizontal { cursor:pointer; }
</style>
</head>
<body>

<?php include __DIR__ . '/includes/nav.php'; ?>

<header class="blog-hero">
  <div class="hero-label">Knowledge & Tutorials</div>
  <h1>Learn <em>Engineering</em><br>from the Field</h1>
  <p>In-depth articles on electronics, embedded programming, IoT, and maker culture — written by engineers, for those who truly want to build.</p>
  <div class="hero-stats-row">
    <div><div class="hero-stat-num">86</div><div class="hero-stat-label">Articles</div></div>
    <div><div class="hero-stat-num">12</div><div class="hero-stat-label">Authors</div></div>
    <div><div class="hero-stat-num">140K</div><div class="hero-stat-label">Reads / Month</div></div>
    <div><div class="hero-stat-num">4.9★</div><div class="hero-stat-label">Rating</div></div>
  </div>
</header>

<!-- FILTER BAR -->
<div class="filter-bar">
  <div class="filter-cats">
    <button class="filter-pill active" data-cat="All">All</button>
    <button class="filter-pill" data-cat="Tutorials">Tutorials</button>
    <button class="filter-pill" data-cat="Reviews">Reviews</button>
    <button class="filter-pill" data-cat="Technical">Technical</button>
    <button class="filter-pill" data-cat="Tips & Tricks">Tips &amp; Tricks</button>
    <button class="filter-pill" data-cat="AI & IoT">AI &amp; IoT</button>
    <button class="filter-pill" data-cat="Projects">Projects</button>
  </div>
  <div class="search-wrap">
    <img class="icon" src="assets/search.svg" alt="" style="width:16px;height:16px">
    <input type="text" id="searchInput" placeholder="Search articles…" autocomplete="off">
  </div>
</div>

<!-- MAIN -->
<div class="blog-main">
  <div class="content-area">

    <!-- Featured -->
    <?php if ($featured): ?>
    <div class="featured-section reveal" id="featuredSection">
      <div class="sec-label">Featured Article</div>
      <div class="featured-post"
           data-cat="<?= htmlspecialchars($featured['cat']) ?>"
           data-title="<?= htmlspecialchars($featured['title']) ?>"
           onclick="goPost('<?= htmlspecialchars($featured['slug']) ?>')">
        <div class="featured-img" style="<?= $featured['heroImg'] !== ''
            ? "background-image: url('" . htmlspecialchars($featured['heroImg']) . "'); background-size: cover; background-position: center;"
            : 'background: ' . htmlspecialchars($featured['heroGradient']) . ';' ?>">
          <?php if ($featured['heroImg'] === ''): ?><img src="assets/logo.svg" alt="" style="width:72px;height:72px"><?php endif; ?>
          <div class="featured-tag"><?= htmlspecialchars($featured['cat']) ?></div>
        </div>
        <div class="featured-body">
          <div class="featured-meta">
            <span><img class="ico" src="assets/userlogo.svg" alt=""><?= htmlspecialchars($featured['author']) ?></span><span>·</span>
            <span><img class="ico" src="assets/dates.svg" alt=""><?= htmlspecialchars($featured['date']) ?></span><span>·</span>
            <span><?= htmlspecialchars($featured['time']) ?> min read</span>
          </div>
          <h2><?= htmlspecialchars($featured['title']) ?></h2>
          <p><?= htmlspecialchars(post_excerpt($featured, 220)) ?></p>
          <span class="read-btn" style="cursor:pointer">Read Article →</span>
        </div>
      </div>
    </div>
    <?php endif; ?>

    <!-- Latest Posts -->
    <div class="sort-row reveal">
      <div class="sec-label" style="margin-bottom:0">Latest Posts</div>
      <select class="sort-select" id="sortSelect">
        <option value="newest">Newest First</option>
        <option value="oldest">Oldest First</option>
        <option value="popular">Most Popular</option>
        <option value="shortest">Shortest Read</option>
      </select>
    </div>

    <span class="result-count reveal" id="resultCount"></span>

    <div class="posts-grid reveal" id="postsGrid">

      <?php foreach ($cards as $p): ?>
      <div class="post-card"
           data-cat="<?= htmlspecialchars($p['cat']) ?>"
           data-title="<?= htmlspecialchars($p['title']) ?>"
           data-date="<?= htmlspecialchars($p['dateISO']) ?>" data-reads="<?= $p['viewsRaw'] ?>" data-time="<?= (int) $p['time'] ?>"
           onclick="goPost('<?= htmlspecialchars($p['slug']) ?>')">
        <div class="post-thumb" style="<?= $p['heroImg'] !== ''
            ? "background-image: url('" . htmlspecialchars($p['heroImg']) . "'); background-size: cover; background-position: center;"
            : 'background: ' . htmlspecialchars($p['heroGradient']) . ';' ?>">
          <?php if ($p['heroImg'] === ''): ?><img src="assets/logo.svg" alt="" style="width:40px;height:40px"><?php endif; ?>
          <span class="post-cat" style="color:<?= htmlspecialchars($p['catColor']) ?>"><?= htmlspecialchars($p['cat']) ?></span>
        </div>
        <div class="post-body">
          <div class="post-meta"><span><?= htmlspecialchars($p['date']) ?></span><span>·</span><span><?= (int) $p['time'] ?> min read</span></div>
          <h3><?= htmlspecialchars($p['title']) ?></h3>
          <p><?= htmlspecialchars(post_excerpt($p, 130)) ?></p>
          <div class="post-footer">
            <div class="author-chip"><div class="avatar"><img src="assets/userlogo.svg" alt=""></div><?= htmlspecialchars($p['author']) ?></div>
            <span class="post-link">Read →</span>
          </div>
        </div>
      </div>
      <?php endforeach; ?>

      <div class="no-results" id="noResults">No articles found. Try a different filter or search term.</div>
    </div>

    <!-- More Articles -->
    <?php if ($more): ?>
    <div class="sec-label reveal" id="moreLabel">More Articles</div>
    <?php foreach ($more as $p): ?>
    <div class="post-horizontal reveal"
         data-cat="<?= htmlspecialchars($p['cat']) ?>"
         data-title="<?= htmlspecialchars($p['title']) ?>"
         onclick="goPost('<?= htmlspecialchars($p['slug']) ?>')">
      <div class="post-h-thumb" style="<?= $p['heroImg'] !== ''
          ? "background-image: url('" . htmlspecialchars($p['heroImg']) . "');background-size: cover; background-position: center;"
          : 'background: ' . htmlspecialchars($p['heroGradient']) . ';display:flex;align-items:center;justify-content:center;font-size:2rem;' ?>">
        <?php if ($p['heroImg'] === ''): ?><img src="assets/logo.svg" alt="" style="width:36px;height:36px"><?php endif; ?>
      </div>
      <div>
        <div class="post-h-cat" style="color:<?= htmlspecialchars($p['catColor']) ?>"><?= htmlspecialchars($p['cat']) ?></div>
        <h3><?= htmlspecialchars($p['title']) ?></h3>
        <div class="post-meta"><span><img class="ico" src="assets/userlogo.svg" alt=""><?= htmlspecialchars($p['author']) ?></span><span>·</span><span><?= htmlspecialchars($p['date']) ?></span><span>·</span><span><?= (int) $p['time'] ?> min read</span></div>
      </div>
    </div>
    <?php endforeach; ?>
    <?php endif; ?>

    <div class="pagination reveal">
      <button class="page-btn active">1</button>
      <button class="page-btn">2</button>
      <button class="page-btn">3</button>
      <button class="page-btn">→</button>
    </div>

  </div>

  <!-- SIDEBAR -->
  <aside class="sidebar">

    <div class="sidebar-card nl-card">
      <div class="sidebar-head"><img class="ico" src="assets/Newsletter.svg" alt="">Newsletter</div>
      <p>New articles and exclusive deals every week. Joined by 8,400+ makers.</p>
      <input class="nl-input" type="email" placeholder="your@email.com">
      <button class="nl-btn">Subscribe Free</button>
    </div>

    <div class="sidebar-card">
      <div class="sidebar-head"><img class="ico" src="assets/topics.svg" alt="">Topics</div>
      <div class="topics-wrap" id="topicsWrap">
        <span class="topic" data-topic="Arduino">Arduino</span>
        <span class="topic" data-topic="ESP32">ESP32</span>
        <span class="topic" data-topic="Raspberry Pi">Raspberry Pi</span>
        <span class="topic" data-topic="IoT">IoT</span>
        <span class="topic" data-topic="Embedded C++">Embedded C++</span>
        <span class="topic extra" data-topic="MicroPython">MicroPython</span>
        <span class="topic extra" data-topic="PCB Design">PCB Design</span>
        <span class="topic extra" data-topic="Robotics">Robotics</span>
        <span class="topic extra" data-topic="AI & ML">AI &amp; ML</span>
        <span class="topic extra" data-topic="Power Electronics">Power Electronics</span>
        <span class="topic extra" data-topic="Sensors">Sensors</span>
      </div>
      <button class="show-more-topics" id="showMoreTopics">+ Show more topics</button>
    </div>

    <div class="sidebar-card">
      <div class="sidebar-head"><img class="ico" src="assets/popular.svg" alt="">Popular Posts</div>
      <div class="popular-item" onclick="goPost('top-arduino-projects')">
        <div class="pop-num">01</div>
        <div><div class="pop-title">Top 10 Arduino Projects for Beginners</div><div class="pop-meta">18,500 reads</div></div>
      </div>
      <div class="popular-item" onclick="goPost('soldering-101')">
        <div class="pop-num">02</div>
        <div><div class="pop-title">Basic Soldering Guide from A to Z</div><div class="pop-meta">12,300 reads</div></div>
      </div>
      <div class="popular-item" onclick="goPost('tflite-esp32')">
        <div class="pop-num">03</div>
        <div><div class="pop-title">TensorFlow Lite on ESP32</div><div class="pop-meta">21,000 reads</div></div>
      </div>
    </div>

    <div class="sidebar-card">
      <div class="sidebar-head"><img class="ico" src="assets/author.svg" alt="">Authors</div>
      <div class="author-row">
        <div class="author-ava"><img src="assets/userlogo.svg" alt=""></div>
        <div><div class="author-name">Yes King</div><div class="author-role">Hardware Engineer</div></div>
        <div class="author-count">14 posts</div>
      </div>
      <div class="author-row">
        <div class="author-ava"><img src="assets/userlogo.svg" alt=""></div>
        <div><div class="author-name">Sigma Boy</div><div class="author-role">Firmware Developer</div></div>
        <div class="author-count">11 posts</div>
      </div>
      <div class="author-row">
        <div class="author-ava"><img src="assets/userlogo.svg" alt=""></div>
        <div><div class="author-name">Hachimi Mambo</div><div class="author-role">IoT Specialist</div></div>
        <div class="author-count">9 posts</div>
      </div>
    </div>

  </aside>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>

<script>
function goPost(slug) {
  window.location.href = 'post.php?id=' + slug;
}

const pills       = document.querySelectorAll('.filter-pill');
const cards       = document.querySelectorAll('.post-card');
const horizontals = document.querySelectorAll('.post-horizontal');
const noResults   = document.getElementById('noResults');
const resultCount = document.getElementById('resultCount');
const featuredSection = document.getElementById('featuredSection');
const moreLabel   = document.getElementById('moreLabel');
const searchInput = document.getElementById('searchInput');
const sortSelect  = document.getElementById('sortSelect');
const postsGrid   = document.getElementById('postsGrid');

let activeCat = 'All';

function applyFilters() {
  const query = searchInput.value.toLowerCase().trim();
  let visible = 0;

  cards.forEach(card => {
    const catMatch   = activeCat === 'All' || card.dataset.cat === activeCat;
    const titleMatch = !query || card.dataset.title.toLowerCase().includes(query);
    if (catMatch && titleMatch) { card.classList.remove('hidden'); visible++; }
    else card.classList.add('hidden');
  });

  horizontals.forEach(h => {
    const catMatch   = activeCat === 'All' || h.dataset.cat === activeCat;
    const titleMatch = !query || h.dataset.title.toLowerCase().includes(query);
    if (catMatch && titleMatch) h.classList.remove('hidden');
    else h.classList.add('hidden');
  });

  const feat = document.querySelector('.featured-post');
  const featMatch = feat
    ? (activeCat === 'All' || feat.dataset.cat === activeCat) &&
      (!query || feat.dataset.title.toLowerCase().includes(query))
    : false;
  if (featuredSection) featuredSection.classList.toggle('hidden', !featMatch);
  if (moreLabel) moreLabel.style.display = (activeCat !== 'All' || query) ? 'none' : '';

  noResults.classList.toggle('show', visible === 0 && !featMatch);
  resultCount.textContent = (activeCat !== 'All' || query)
    ? `Showing ${visible} article${visible !== 1 ? 's' : ''}`
    : '';
}

pills.forEach(pill => {
  pill.addEventListener('click', () => {
    pills.forEach(p => p.classList.remove('active'));
    pill.classList.add('active');
    activeCat = pill.dataset.cat;
    applyFilters();
  });
});

searchInput.addEventListener('input', applyFilters);

sortSelect.addEventListener('change', () => {
  const val = sortSelect.value;
  const allCards = [...postsGrid.querySelectorAll('.post-card')];
  allCards.sort((a, b) => {
    if (val === 'newest')   return new Date(b.dataset.date) - new Date(a.dataset.date);
    if (val === 'oldest')   return new Date(a.dataset.date) - new Date(b.dataset.date);
    if (val === 'popular')  return +b.dataset.reads - +a.dataset.reads;
    if (val === 'shortest') return +a.dataset.time  - +b.dataset.time;
  });
  allCards.forEach(c => postsGrid.appendChild(c));
  postsGrid.appendChild(noResults);
});

document.querySelectorAll('.topic').forEach(t => {
  t.addEventListener('click', () => t.classList.toggle('active'));
});

const showMoreBtn = document.getElementById('showMoreTopics');
let expanded = false;
showMoreBtn.addEventListener('click', () => {
  expanded = !expanded;
  document.querySelectorAll('.topic.extra').forEach(t => t.classList.toggle('visible', expanded));
  showMoreBtn.textContent = expanded ? '− Show less' : '+ Show more topics';
});

document.querySelectorAll('.page-btn').forEach(btn => {
  btn.addEventListener('click', () => {
    document.querySelectorAll('.page-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
  });
});

const observer = new IntersectionObserver(entries => {
  entries.forEach(e => {
    if (e.isIntersecting) { e.target.classList.add('visible'); observer.unobserve(e.target); }
  });
}, { threshold: 0.06 });
document.querySelectorAll('.reveal').forEach(el => observer.observe(el));

applyFilters();
</script>
</body>
</html>
