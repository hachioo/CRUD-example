<?php
$activePage = $activePage ?? '';
$logoMark   = $logoMark   ?? '<img src="assets/logo.svg" alt="VoltCore Logo">';
?>
<nav>
  <a href="main.html" class="logo">
    <div class="logo-mark"><?= $logoMark ?></div>
    Volt<span>Core</span>
  </a>
  <ul class="nav-links">
    <li><a href="main.html"<?= $activePage === 'Home' ? ' class="active"' : '' ?>>Home</a></li>
    <li><a href="shop.html"<?= $activePage === 'Products' ? ' class="active"' : '' ?>>Products</a></li>
    <li><a href="project.html"<?= $activePage === 'Projects' ? ' class="active"' : '' ?>>Projects</a></li>
    <li><a href="blog.php"<?= $activePage === 'Blog' ? ' class="active"' : '' ?>>Blog</a></li>
    <li><a href="main.html#contact-section"<?= $activePage === 'Contact' ? ' class="active"' : '' ?>>Contact</a></li>
  </ul>
  <a href="shop.html" class="nav-cta">Shop Now →</a>
</nav>
