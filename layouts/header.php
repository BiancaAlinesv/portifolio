<?php declare(strict_types=1); ?>

<body>

<a href="#home" class="skip-link">Ir para o conteúdo principal</a>

<header>
<nav class="navbar" id="navbar" aria-label="Navegação principal">
<div class="nav-inner">
<a href="#home" class="nav-logo">
<span class="logo-bracket">{</span>
<span class="logo-name">ba</span>
<span class="logo-bracket">}</span>
</a>
<ul class="nav-links" id="navLinks">
<?php foreach ($nav as $item): ?>
<li><a href="<?= htmlspecialchars($item['href']) ?>" class="nav-link" data-num="<?= htmlspecialchars($item['num']) ?>"><?= htmlspecialchars($item['label']) ?></a></li>
<?php endforeach; ?>
</ul>
<div class="nav-actions">
<button id="themeToggle" class="theme-btn" aria-label="Alternar tema claro e escuro">
<span class="theme-icon"></span>
</button>
<button class="hamburger" id="hamburger" aria-label="Abrir menu de navegação" aria-expanded="false" aria-controls="mobileOverlay">
<span></span><span></span>
</button>
</div>
</div>
</nav>

<div class="mobile-overlay" id="mobileOverlay" role="dialog" aria-modal="true" aria-label="Menu de navegação mobile">
<ul class="mobile-nav-links">
<?php foreach ($nav as $item): ?>
<li><a href="<?= htmlspecialchars($item['href']) ?>" class="mobile-link"><?= htmlspecialchars($item['label']) ?></a></li>
<?php endforeach; ?>
</ul>
</div>
</header>

<main>
