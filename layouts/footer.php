<?php

declare(strict_types=1); ?>

</main>

<footer class="footer" role="contentinfo">
    <div class="container">
        <div class="footer-inner">
            <span class="footer-name"><?= htmlspecialchars($person['full_name']) ?></span>
            <nav class="footer-links" aria-label="Navegação do rodapé">
                <?php foreach ($nav as $item): ?>
                    <a href="<?= htmlspecialchars($item['href']) ?>"><?= htmlspecialchars($item['label']) ?></a>
                <?php endforeach; ?>
            </nav>
            <span class="footer-copy">&copy; <?= $currentYear ?> — Todos os direitos reservados</span>
        </div>
    </div>
</footer>

<script src="assets/script.js"></script>
</body>

</html>