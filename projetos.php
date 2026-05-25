<?php

declare(strict_types=1);

require_once __DIR__ . '/layouts/head.php';
require_once __DIR__ . '/layouts/header.php';

$perPage = 6;
$page = max(1, intval($_GET['page'] ?? 1));
$total = count($projects);
$totalPages = max(1, (int) ceil($total / $perPage));
$page = min($page, $totalPages);
$offset = ($page - 1) * $perPage;
$pageProjects = array_slice($projects, $offset, $perPage);
?>

<section class="projects-page" aria-labelledby="projects-heading">
<div class="container">
<div class="section-header">
<a href="index.php#projects" class="back-link">← Voltar</a>
<span class="section-num">02</span>
<h2 class="section-title" id="projects-heading">Todos os Projetos</h2>
<p class="section-subtitle"><?= $total ?> projeto<?= $total !== 1 ? 's' : '' ?> · Página <?= $page ?> de <?= $totalPages ?></p>
</div>
<div class="carousel" data-carousel data-per-page="2">
<div class="carousel-track">
<?php
$chunks = array_chunk($pageProjects, 2);
foreach ($chunks as $pageIdx => $pair): ?>
<div class="carousel-page" data-slide="<?= $pageIdx ?>">
<?php foreach ($pair as $i => $project): ?>
<div class="carousel-card">
<div class="project-number" aria-hidden="true"><?= str_pad((string)($offset + $pageIdx * 2 + $i + 1), 3, '0', STR_PAD_LEFT) ?></div>
<div class="project-thumb <?= htmlspecialchars($project['thumb']) ?>"></div>
<div class="project-body">
<h3 class="project-name"><?= htmlspecialchars($project['name']) ?></h3>
<p class="project-desc"><?= htmlspecialchars($project['desc']) ?></p>
<div class="project-tags">
<?php foreach ($project['tags'] as $tag): ?>
<span><?= htmlspecialchars($tag) ?></span>
<?php endforeach; ?>
</div>
<?php if ($project['github']): ?>
<div class="project-links">
<a href="<?= htmlspecialchars($project['github']) ?>" class="plink" target="_blank" rel="noopener noreferrer">GitHub →</a>
</div>
<?php endif; ?>
</div>
</div>
<?php endforeach; ?>
</div>
<?php endforeach; ?>
</div>
<button class="carousel-btn carousel-prev" aria-label="Projeto anterior" data-carousel-prev>←</button>
<button class="carousel-btn carousel-next" aria-label="Próximo projeto" data-carousel-next>→</button>
<div class="carousel-dots" data-carousel-dots></div>
</div>

<?php if ($totalPages > 1): ?>
<nav class="pagination" aria-label="Paginação de projetos">
<?php if ($page > 1): ?>
<a href="?page=<?= $page - 1 ?>" class="pagination-link pagination-prev" rel="prev">← Anterior</a>
<?php endif; ?>

<div class="pagination-numbers">
<?php for ($p = 1; $p <= $totalPages; $p++): ?>
<?php if ($p === $page): ?>
<span class="pagination-num active" aria-current="page"><?= $p ?></span>
<?php else: ?>
<a href="?page=<?= $p ?>" class="pagination-num"><?= $p ?></a>
<?php endif; ?>
<?php endfor; ?>
</div>

<?php if ($page < $totalPages): ?>
<a href="?page=<?= $page + 1 ?>" class="pagination-link pagination-next" rel="next">Próximo →</a>
<?php endif; ?>
</nav>
<?php endif; ?>
</div>
</section>

<?php require_once __DIR__ . '/layouts/footer.php'; ?>
