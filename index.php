<?php

declare(strict_types=1);

require_once __DIR__ . '/layouts/head.php';
require_once __DIR__ . '/layouts/header.php';
?>

<!-- HERO -->
<section id="home" class="hero" aria-labelledby="hero-heading">
    <div class="hero-bg-text" aria-hidden="true">DEV</div>
    <div class="container">
        <div class="hero-content">
            <p class="hero-eyebrow reveal">
                <span class="eyebrow-line"></span>
                <?= htmlspecialchars($person['eyebrow']) ?>
            </p>
            <h1 class="hero-name reveal-delay-1" id="hero-heading">
                <?= htmlspecialchars($person['given_name']) ?><br><em><?= htmlspecialchars($person['family_name']) ?></em>
            </h1>
            <p class="hero-tagline reveal-delay-2">
                Transformo lógica em experiências<br>digitais que conectam marca, cliente e resultado.
            </p>
            <p class="hero-subtitle reveal-delay-3">Portfólio e currículo digital pensado para gerar impacto, clareza e confiança desde o primeiro clique.</p>
            <div class="hero-stack reveal-delay-4">
                <span>HTML</span>
                <span class="dot">·</span>
                <span>CSS</span>
                <span class="dot">·</span>
                <span>JavaScript</span>
                <span class="dot">·</span>
                <span>Python</span>
                <span class="dot">·</span>
                <span>IA</span>
            </div>
            <div class="hero-cta reveal-delay-5">
                <a href="#projects" class="btn btn-primary">Ver Projetos</a>
                <a href="<?= htmlspecialchars($person['cv_file']) ?>" download="<?= htmlspecialchars($person['cv_download_name']) ?>" class="btn btn-cv">Baixar CV</a>
                <a href="#contact" class="btn btn-ghost">Fale comigo</a>
            </div>
        </div>
        <div class="hero-index" aria-hidden="true">
            <span><?= htmlspecialchars($person['city']) ?></span>
            <span class="index-divider"></span>
            <span><?= $currentYear ?></span>
        </div>
    </div>
    <div class="hero-scroll-hint" aria-hidden="true">
        <span>scroll</span>
        <div class="scroll-line"></div>
    </div>
</section>

<!-- SOBRE -->
<section id="about" class="about" aria-labelledby="about-heading">
    <div class="container">
        <div class="section-header">
            <span class="section-num">01</span>
            <h2 class="section-title" id="about-heading">Sobre Mim</h2>
        </div>
        <div class="about-grid">
            <div class="about-portrait">
          <img src="Gemini_Generated_Image_5yq84a5yq84a5yq8.png" alt="Foto de perfil de Bianca Aline" class="portrait-img" width="280" height="280" loading="lazy">
                <div class="about-meta">
                    <div class="meta-item">
                        <span class="meta-label">Email</span>
                        <a href="mailto:<?= htmlspecialchars($person['email']) ?>" class="meta-value"><?= htmlspecialchars($person['email']) ?></a>
                    </div>
                    <div class="meta-item">
                        <span class="meta-label">Localização</span>
                        <span class="meta-value"><?= htmlspecialchars($person['city']) ?></span>
                    </div>
                    <div class="meta-item">
                        <span class="meta-label">Foco</span>
                        <span class="meta-value"><?= htmlspecialchars($person['focus']) ?></span>
                    </div>
                    <div class="meta-item meta-cta">
                        <a href="<?= htmlspecialchars($person['cv_file']) ?>" download="<?= htmlspecialchars($person['cv_download_name']) ?>" class="btn btn-cv btn-small">
                            <svg class="icon-cv" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                                <polyline points="14 2 14 8 20 8" />
                                <line x1="12" y1="18" x2="12" y2="12" />
                                <polyline points="9 15 12 18 15 15" />
                            </svg>
                            <span>Baixar CV</span>
                        </a>
                    </div>
                </div>
            </div>
            <div class="about-text">
                <p class="about-lead">
                    Apaixonada por tecnologia e resolução de problemas, uso lógica e criatividade para construir experiências web que unem design moderno e funcionalidade.
                </p>
                <p>
                    Atualmente aprofundando conhecimentos em desenvolvimento web, Php e banco de dados SQL, com foco em evolução constante e construção de projetos práticos que resolvem problemas reais.
                </p>
                <p>
                    Acredito que um bom código é aquele que resolve problemas de forma simples, eficiente e organizada — sem ruído, sem exagero.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- PROJETOS -->
<section id="projects" class="projects" aria-labelledby="projects-heading">
    <div class="container">
        <div class="section-header">
            <span class="section-num">02</span>
            <h2 class="section-title" id="projects-heading">Projetos</h2>
        </div>
        <div class="projects-list">
            <?php foreach ($projects as $i => $project): ?>
                <article class="project-item">
                    <div class="project-number" aria-hidden="true"><?= str_pad((string)($i + 1), 3, '0', STR_PAD_LEFT) ?></div>
                    <div class="project-visual">
                        <div class="project-thumb <?= htmlspecialchars($project['thumb']) ?>"></div>
                    </div>
                    <div class="project-body">
                        <h3 class="project-name"><?= htmlspecialchars($project['name']) ?></h3>
                        <p class="project-desc"><?= htmlspecialchars($project['desc']) ?></p>
                        <div class="project-tags">
                            <?php foreach ($project['tags'] as $tag): ?>
                                <span><?= htmlspecialchars($tag) ?></span>
                            <?php endforeach; ?>
                        </div>
                        <?php if ($project['url'] || $project['github']): ?>
                            <div class="project-links">
                                <?php if ($project['url']): ?>
                                    <a href="<?= htmlspecialchars($project['url']) ?>" class="plink">Ver projeto →</a>
                                <?php endif; ?>
                                <?php if ($project['github']): ?>
                                    <a href="<?= htmlspecialchars($project['github']) ?>" class="plink">GitHub →</a>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- HABILIDADES -->
<section id="skills" class="skills" aria-labelledby="skills-heading">
    <div class="container">
        <div class="section-header">
            <span class="section-num">03</span>
            <h2 class="section-title" id="skills-heading">Habilidades</h2>
        </div>
        <div class="skills-grid">
            <?php foreach ($skills as $skill): ?>
                <div class="skill-row" data-level="<?= $skill['level'] ?>">
                    <div class="skill-info">
                        <span class="skill-name"><?= htmlspecialchars($skill['name']) ?></span>
                        <span class="skill-pct"><?= $skill['level'] ?>%</span>
                    </div>
                    <div class="skill-track">
                        <div class="skill-fill"></div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- CONTATO -->
<section id="contact" class="contact" aria-labelledby="contact-heading">
    <div class="container">
        <div class="section-header">
            <span class="section-num">04</span>
            <h2 class="section-title" id="contact-heading">Vamos Conversar</h2>
        </div>
        <div class="contact-grid">
            <div class="contact-intro">
                <p class="contact-headline">Tem um projeto em mente?</p>
                <p>Estou aberta a conversas sobre desenvolvimento, parcerias ou só para trocar uma ideia sobre tecnologia.</p>
                <div class="contact-socials">
                    <?php foreach ($social as $item): ?>
                        <a href="<?= htmlspecialchars($item['url']) ?>" <?php if (str_starts_with($item['url'], 'http')): ?> target="_blank" rel="noopener noreferrer" <?php endif; ?> class="social-item" aria-label="<?= htmlspecialchars($item['aria']) ?>">
                            <span class="social-label"><?= htmlspecialchars($item['label']) ?></span>
                            <span class="social-arrow" aria-hidden="true">↗</span>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
            <form id="contactForm" class="contact-form" action="contact.php" method="POST" novalidate>
                <div class="form-field">
                    <label for="name">Nome</label>
                    <input type="text" id="name" name="name" placeholder="Como posso te chamar?" required autocomplete="name">
                </div>
                <div class="form-field">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="seu@email.com" required autocomplete="email">
                </div>
                <div class="form-field">
                    <label for="message">Mensagem</label>
                    <textarea id="message" name="message" rows="5" placeholder="Sobre o que você quer conversar?" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary btn-full">
                    <span class="btn-text">Enviar Mensagem</span>
                    <span class="btn-icon">→</span>
                </button>
                <p class="form-feedback" id="formFeedback" aria-live="polite"></p>
            </form>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/layouts/footer.php'; ?>