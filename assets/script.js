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
FORMULÁRIO DE CONTATO — VALIDAÇÃO COMPLETA
========================================================= */
const contactForm = document.getElementById('contactForm');
const formFeedback = document.getElementById('formFeedback');

const LIMITS = {
  nameMin: 2,
  nameMax: 100,
  emailMax: 120,
  phoneMin: 10,
  phoneMax: 11,
  msgMin: 10,
  msgMax: 2000,
  rateSeconds: 10
};

function showFeedback(msg, type) {
  if (!formFeedback) return;
  formFeedback.textContent = msg;
  formFeedback.style.color = type === 'error' ? '#ff6b6b' : 'var(--accent)';
}

function showFieldError(id, msg) {
  const el = document.getElementById(id);
  if (!el) return;
  el.textContent = msg;
  el.style.opacity = msg ? '1' : '0';
}

function clearAllErrors() {
  showFieldError('nameError', '');
  showFieldError('emailError', '');
  showFieldError('phoneError', '');
  showFieldError('messageError', '');
  showFeedback('', '');
}

function sanitize(text) {
  const div = document.createElement('div');
  div.textContent = text;
  return div.innerHTML;
}

function hasXSS(text) {
  const dangerous = /<\s*\/?\s*(script|iframe|object|embed|form|input|link|meta|style|base|body|img|svg|on\w+)\b[^>]*>/i;
  const events = /\bon\w+\s*=/i;
  const jsProto = /javascript\s*:/i;
  return dangerous.test(text) || events.test(text) || jsProto.test(text);
}

function isValidEmail(email) {
  return /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(email) && email.length <= LIMITS.emailMax;
}

const phoneInput = document.getElementById('phone');
const messageInput = document.getElementById('message');
const charCounter = document.getElementById('charCounter');

function maskPhone(value) {
  const digits = value.replace(/\D/g, '').slice(0, 11);
  if (digits.length === 0) return '';
  if (digits.length <= 2) return '(' + digits;
  if (digits.length <= 7) return '(' + digits.slice(0, 2) + ') ' + digits.slice(2);
  return '(' + digits.slice(0, 2) + ') ' + digits.slice(2, 7) + '-' + digits.slice(7);
}

if (phoneInput) {
  phoneInput.addEventListener('input', function () {
    const cursorPos = this.selectionStart;
    const beforeLen = this.value.length;
    this.value = maskPhone(this.value);
    const afterLen = this.value.length;
    const newPos = cursorPos + (afterLen - beforeLen);
    this.setSelectionRange(newPos, newPos);
  });
}

if (messageInput && charCounter) {
  messageInput.addEventListener('input', function () {
    const len = this.value.length;
    charCounter.textContent = len + ' / ' + LIMITS.msgMax;
    if (len > LIMITS.msgMax) {
      charCounter.style.color = '#ff6b6b';
    } else if (len > LIMITS.msgMax * 0.9) {
      charCounter.style.color = '#f59e0b';
    } else {
      charCounter.style.color = 'var(--text-dim)';
    }
  });
}

function validateName(name) {
  if (!name) return 'Informe seu nome.';
  if (name.length < LIMITS.nameMin) return 'Nome deve ter pelo menos ' + LIMITS.nameMin + ' caracteres.';
  if (name.length > LIMITS.nameMax) return 'Nome deve ter no máximo ' + LIMITS.nameMax + ' caracteres.';
  if (hasXSS(name)) return 'Nome contém conteúdo não permitido.';
  if (!/^[a-zA-ZÀ-ÿ\s'.-]+$/.test(name)) return 'Nome deve conter apenas letras e espaços.';
  return '';
}

function validateEmail(email) {
  if (!email) return 'Informe seu e-mail.';
  if (email.length > LIMITS.emailMax) return 'E-mail muito longo.';
  if (!isValidEmail(email)) return 'E-mail inválido.';
  if (hasXSS(email)) return 'E-mail contém conteúdo não permitido.';
  return '';
}

function validatePhone(phone) {
  if (!phone) return '';
  const digits = phone.replace(/\D/g, '');
  if (digits.length < LIMITS.phoneMin) return 'Telefone deve ter DDD + número.';
  if (digits.length > LIMITS.phoneMax) return 'Telefone com muitos dígitos.';
  return '';
}

function validateMessage(message) {
  if (!message) return 'Escreva uma mensagem.';
  if (message.length < LIMITS.msgMin) return 'Mensagem deve ter pelo menos ' + LIMITS.msgMin + ' caracteres.';
  if (message.length > LIMITS.msgMax) return 'Mensagem deve ter no máximo ' + LIMITS.msgMax + ' caracteres.';
  if (hasXSS(message)) return 'Mensagem contém conteúdo não permitido.';
  return '';
}

function canSubmit() {
  const last = sessionStorage.getItem('lastContactSubmit');
  if (!last) return true;
  const elapsed = (Date.now() - parseInt(last, 10)) / 1000;
  return elapsed >= LIMITS.rateSeconds;
}

function rateWaitTime() {
  const last = sessionStorage.getItem('lastContactSubmit');
  if (!last) return 0;
  const elapsed = (Date.now() - parseInt(last, 10)) / 1000;
  return Math.max(0, Math.ceil(LIMITS.rateSeconds - elapsed));
}

if (contactForm) {
  const formFields = contactForm.querySelectorAll('input, textarea');
  formFields.forEach(function (field) {
    field.addEventListener('input', function () {
      this.classList.remove('field-invalid');
      const errorId = this.id + 'Error';
      showFieldError(errorId, '');
    });
  });

  contactForm.addEventListener('submit', function (e) {
    e.preventDefault();
    clearAllErrors();

    const nameEl = document.getElementById('name');
    const emailEl = document.getElementById('email');
    const phoneEl = document.getElementById('phone');
    const messageEl = document.getElementById('message');

    const name = nameEl.value.trim();
    const email = emailEl.value.trim();
    const phone = phoneEl ? phoneEl.value.trim() : '';
    const message = messageEl.value.trim();

    let hasError = false;

    const nameErr = validateName(name);
    if (nameErr) { showFieldError('nameError', nameErr); nameEl.classList.add('field-invalid'); hasError = true; }

    const emailErr = validateEmail(email);
    if (emailErr) { showFieldError('emailError', emailErr); emailEl.classList.add('field-invalid'); hasError = true; }

    const phoneErr = validatePhone(phone);
    if (phoneErr) { showFieldError('phoneError', phoneErr); phoneEl.classList.add('field-invalid'); hasError = true; }

    const msgErr = validateMessage(message);
    if (msgErr) { showFieldError('messageError', msgErr); messageEl.classList.add('field-invalid'); hasError = true; }

    if (hasError) {
      showFeedback('Corrija os campos destacados acima.', 'error');
      return;
    }

    if (!canSubmit()) {
      const wait = rateWaitTime();
      showFeedback('Aguarde ' + wait + ' segundo' + (wait > 1 ? 's' : '') + ' antes de enviar novamente.', 'error');
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
        sessionStorage.setItem('lastContactSubmit', Date.now().toString());
        if (charCounter) charCounter.textContent = '0 / ' + LIMITS.msgMax;
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
