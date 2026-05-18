/* =============================================================
   BIANCA ALINE — PORTFOLIO SCRIPT
   ============================================================= */

(function () {
    'use strict';

    /* =========================================================
       TEMA CLARO / ESCURO
       ========================================================= */
    const html        = document.documentElement;
    const themeToggle = document.getElementById('themeToggle');
    const ICONS       = { dark: '☀️', light: '🌙' };

    function getTheme() {
        return localStorage.getItem('theme') || 'dark';
    }

    function applyTheme(theme) {
        html.setAttribute('data-theme', theme);
        if (themeToggle) {
            themeToggle.querySelector('.theme-icon').textContent =
                theme === 'dark' ? ICONS.dark : ICONS.light;
        }
        localStorage.setItem('theme', theme);
    }

    applyTheme(getTheme());

    if (themeToggle) {
        themeToggle.addEventListener('click', () => {
            const next = getTheme() === 'dark' ? 'light' : 'dark';
            applyTheme(next);
        });
    }


    /* =========================================================
       MENU MOBILE
       ========================================================= */
    const hamburger    = document.getElementById('hamburger');
    const mobileOverlay = document.getElementById('mobileOverlay');
    const mobileLinks  = document.querySelectorAll('.mobile-link');

    function openMenu() {
        hamburger.classList.add('open');
        mobileOverlay.classList.add('open');
        document.body.style.overflow = 'hidden';
    }

    function closeMenu() {
        hamburger.classList.remove('open');
        mobileOverlay.classList.remove('open');
        document.body.style.overflow = '';
    }

    if (hamburger) {
        hamburger.addEventListener('click', () => {
            hamburger.classList.contains('open') ? closeMenu() : openMenu();
        });
    }

    mobileLinks.forEach(link => {
        link.addEventListener('click', closeMenu);
    });

    // Fecha ao redimensionar para desktop
    window.addEventListener('resize', () => {
        if (window.innerWidth > 768) closeMenu();
    });


    /* =========================================================
       NAVBAR — SCROLL EFEITO
       ========================================================= */
    const navbar = document.getElementById('navbar');

    function handleNavScroll() {
        if (window.scrollY > 30) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
    }

    window.addEventListener('scroll', handleNavScroll, { passive: true });
    handleNavScroll();


    /* =========================================================
       REVEAL AO SCROLL (Intersection Observer)
       ========================================================= */
    const revealEls = document.querySelectorAll(
        '.reveal, .reveal-delay-1, .reveal-delay-2, .reveal-delay-3, .reveal-delay-4'
    );

    const revealObserver = new IntersectionObserver(
        (entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    revealObserver.unobserve(entry.target);
                }
            });
        },
        { threshold: 0.1, rootMargin: '0px 0px -40px 0px' }
    );

    revealEls.forEach(el => revealObserver.observe(el));

    // Hero é visível imediatamente
    document.querySelectorAll('.hero .reveal, .hero .reveal-delay-1, .hero .reveal-delay-2, .hero .reveal-delay-3, .hero .reveal-delay-4')
        .forEach((el, i) => {
            setTimeout(() => el.classList.add('visible'), i * 120 + 80);
        });


    /* =========================================================
       SKILL BARS — ANIMAR AO APARECER
       ========================================================= */
    const skillRows = document.querySelectorAll('.skill-row');

    const skillObserver = new IntersectionObserver(
        (entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const row   = entry.target;
                    const level = row.getAttribute('data-level');
                    const fill  = row.querySelector('.skill-fill');
                    if (fill) {
                        // pequeno delay para suavidade visual
                        setTimeout(() => {
                            fill.style.width = level + '%';
                        }, 150);
                    }
                    skillObserver.unobserve(row);
                }
            });
        },
        { threshold: 0.3 }
    );

    skillRows.forEach(row => skillObserver.observe(row));


    /* =========================================================
       FORMULÁRIO DE CONTATO
       ========================================================= */
    const contactForm   = document.getElementById('contactForm');
    const formFeedback  = document.getElementById('formFeedback');

    if (contactForm) {
        contactForm.addEventListener('submit', function (e) {
            e.preventDefault();

            const name    = document.getElementById('name').value.trim();
            const email   = document.getElementById('email').value.trim();
            const message = document.getElementById('message').value.trim();

            // Validação simples
            if (!name || !email || !message) {
                showFeedback('Preencha todos os campos, por favor.', 'error');
                return;
            }

            if (!isValidEmail(email)) {
                showFeedback('E-mail inválido.', 'error');
                return;
            }

            // Simula envio (substitua pela integração real: EmailJS, FormSubmit, etc.)
            const btn = contactForm.querySelector('button[type="submit"]');
            btn.disabled = true;
            btn.querySelector('.btn-text').textContent = 'Enviando…';

            setTimeout(() => {
                showFeedback('Mensagem enviada! Responderei em breve.', 'success');
                contactForm.reset();
                btn.disabled = false;
                btn.querySelector('.btn-text').textContent = 'Enviar Mensagem';
            }, 1400);
        });
    }

    function showFeedback(msg, type) {
        if (!formFeedback) return;
        formFeedback.textContent = msg;
        formFeedback.style.color = type === 'error'
            ? '#ff6b6b'
            : 'var(--accent)';
    }

    function isValidEmail(email) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    }


    /* =========================================================
       SMOOTH SCROLL — LINKS DE NAVEGAÇÃO
       ========================================================= */
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                e.preventDefault();
                const navH   = navbar ? navbar.offsetHeight : 68;
                const top    = target.getBoundingClientRect().top + window.scrollY - navH;
                window.scrollTo({ top, behavior: 'smooth' });
            }
        });
    });


    /* =========================================================
       ACTIVE LINK — HIGHLIGHT NA NAVBAR
       ========================================================= */
    const sections  = document.querySelectorAll('section[id]');
    const navAnchors = document.querySelectorAll('.nav-link');

    function updateActiveLink() {
        let current = '';
        sections.forEach(section => {
            const sectionTop = section.offsetTop - (navbar ? navbar.offsetHeight + 40 : 100);
            if (window.scrollY >= sectionTop) {
                current = section.getAttribute('id');
            }
        });

        navAnchors.forEach(a => {
            a.style.color = '';
            if (a.getAttribute('href') === '#' + current) {
                a.style.color = 'var(--text)';
            }
        });
    }

    window.addEventListener('scroll', updateActiveLink, { passive: true });

})();