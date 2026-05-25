/* =============================================================
BIANCA ALINE — PORTFOLIO SCRIPT
============================================================= */

(function () {
  'use strict';

  /* =========================================================
  TEMA CLARO / ESCURO
  ========================================================= */
  const html = document.documentElement;
  const themeToggle = document.getElementById('themeToggle');
  const ICONS = { dark: '☀️', light: '🌙' };

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
  const hamburger = document.getElementById('hamburger');
  const mobileOverlay = document.getElementById('mobileOverlay');
  const mobileLinks = document.querySelectorAll('.mobile-link');

  function openMenu() {
    hamburger.classList.add('open');
    hamburger.setAttribute('aria-expanded', 'true');
    mobileOverlay.classList.add('open');
    document.body.style.overflow = 'hidden';
    hamburger.focus();
  }

  function closeMenu() {
    hamburger.classList.remove('open');
    hamburger.setAttribute('aria-expanded', 'false');
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

  window.addEventListener('resize', () => {
    if (window.innerWidth > 768) closeMenu();
  });

  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && mobileOverlay.classList.contains('open')) {
      closeMenu();
      hamburger.focus();
    }
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
    '.reveal, .reveal-delay-1, .reveal-delay-2, .reveal-delay-3, .reveal-delay-4, .reveal-delay-5'
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
          const row = entry.target;
          const level = row.getAttribute('data-level');
          const fill = row.querySelector('.skill-fill');
          if (fill) {
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
  const contactForm = document.getElementById('contactForm');
  const formFeedback = document.getElementById('formFeedback');

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

  if (contactForm) {
    contactForm.addEventListener('submit', function (e) {
      e.preventDefault();

      const name = document.getElementById('name').value.trim();
      const email = document.getElementById('email').value.trim();
      const message = document.getElementById('message').value.trim();

      if (!name || !email || !message) {
        showFeedback('Preencha todos os campos, por favor.', 'error');
        return;
      }

      if (!isValidEmail(email)) {
        showFeedback('E-mail inválido.', 'error');
        return;
      }

      const btn = contactForm.querySelector('button[type="submit"]');
      btn.disabled = true;
      btn.querySelector('.btn-text').textContent = 'Enviando…';

      const formData = new FormData(contactForm);

      fetch('contact.php', {
        method: 'POST',
        body: formData
      })
        .then(res => res.json())
        .then(data => {
          if (data.success) {
            showFeedback(data.message, 'success');
            contactForm.reset();
          } else {
            showFeedback(data.message, 'error');
          }
        })
        .catch(() => {
          showFeedback('Erro de conexão. Tente novamente.', 'error');
        })
        .finally(() => {
          btn.disabled = false;
          btn.querySelector('.btn-text').textContent = 'Enviar Mensagem';
        });
    });
  }


/* =========================================================
CARROSSEL DE PROJETOS
========================================================= */
const carousels = document.querySelectorAll('[data-carousel]');

carousels.forEach(carousel => {
  const track = carousel.querySelector('.carousel-track');
  const slides = carousel.querySelectorAll('[data-slide]');
  const prevBtn = carousel.querySelector('[data-carousel-prev]');
  const nextBtn = carousel.querySelector('[data-carousel-next]');
  const dotsContainer = carousel.querySelector('[data-carousel-dots]');

  if (!track || slides.length === 0) return;

  let current = 0;
  const total = slides.length;
  let autoplayTimer = null;
  let startX = 0;
  let isDragging = false;

  slides.forEach((_, i) => {
    const dot = document.createElement('button');
    dot.classList.add('carousel-dot');
    dot.setAttribute('aria-label', `Ir para projeto ${i + 1}`);
    if (i === 0) dot.classList.add('active');
    dot.addEventListener('click', () => goTo(i));
    dotsContainer.appendChild(dot);
  });

  const dots = dotsContainer.querySelectorAll('.carousel-dot');

  function goTo(index) {
    current = ((index % total) + total) % total;
    track.style.transform = `translateX(-${current * 100}%)`;
    dots.forEach((d, i) => d.classList.toggle('active', i === current));
  }

  function next() { goTo(current + 1); }
  function prev() { goTo(current - 1); }

  if (prevBtn) prevBtn.addEventListener('click', () => { prev(); resetAutoplay(); });
  if (nextBtn) nextBtn.addEventListener('click', () => { next(); resetAutoplay(); });

  function startAutoplay() {
    autoplayTimer = setInterval(next, 5000);
  }

  function resetAutoplay() {
    clearInterval(autoplayTimer);
    startAutoplay();
  }

  carousel.addEventListener('mouseenter', () => clearInterval(autoplayTimer));
  carousel.addEventListener('mouseleave', startAutoplay);

  track.addEventListener('touchstart', (e) => {
    startX = e.touches[0].clientX;
    isDragging = true;
  }, { passive: true });

  track.addEventListener('touchend', (e) => {
    if (!isDragging) return;
    isDragging = false;
    const diff = startX - e.changedTouches[0].clientX;
    if (Math.abs(diff) > 50) {
      diff > 0 ? next() : prev();
      resetAutoplay();
    }
  }, { passive: true });

  track.addEventListener('keydown', (e) => {
    if (e.key === 'ArrowLeft') { prev(); resetAutoplay(); }
    if (e.key === 'ArrowRight') { next(); resetAutoplay(); }
  });

  startAutoplay();
});


/* =========================================================
SMOOTH SCROLL — LINKS DE NAVEGAÇÃO
  ========================================================= */
  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
      const target = document.querySelector(this.getAttribute('href'));
      if (target) {
        e.preventDefault();
        const navH = navbar ? navbar.offsetHeight : 68;
        const top = target.getBoundingClientRect().top + window.scrollY - navH;
        window.scrollTo({ top, behavior: 'smooth' });
      }
    });
  });


  /* =========================================================
  ACTIVE LINK — HIGHLIGHT NA NAVBAR
  ========================================================= */
  const sections = document.querySelectorAll('section[id]');
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
