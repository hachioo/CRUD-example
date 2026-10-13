<?php $footerEmail = $footerEmail ?? 'support@voltcore.vn'; ?>
<footer>
  <div class="footer-top">
    <a href="main.html" class="footer-logo"><div class="logo-mark"><img src="assets/logo.svg" alt="VoltCore Logo"></div>Volt<span>Core</span></a>
    <div class="footer-links">
      <a href="main.html">Home</a>
      <a href="main.html#product-section">Products</a>
      <a href="project.html">Projects</a>
      <a href="blog.php">Blog</a>
      <a href="main.html#contact-section">Contact</a>
    </div>
  </div>
  <div class="footer-bottom">
    <p>© 2026 VoltCore. All rights reserved.</p>
    <p>Da Nang · <?= htmlspecialchars($footerEmail) ?></p>
  </div>
</footer>
