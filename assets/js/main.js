/* ============================================================
   РемонтПрофи — main.js (vanilla, no dependencies)
   ============================================================ */
(function () {
  'use strict';

  var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  var hasIO = 'IntersectionObserver' in window;

  /* ---------- Lazy background images ----------
     Any element with data-bg gets its background-image applied only when it
     scrolls into view. Put a real URL in data-bg (e.g. data-bg="assets/img/hero.jpg").
     The value "placeholder" is a stand-in that keeps the CSS placeholder look. */
  function loadBg(el) {
    var src = el.getAttribute('data-bg');
    if (!src || src === 'placeholder') return;      // no real image yet — keep placeholder
    var img = new Image();
    img.onload = function () {
      el.style.backgroundImage = 'url("' + src + '")';
      el.classList.add('is-loaded');
    };
    img.src = src;
  }
  var bgEls = document.querySelectorAll('[data-bg]');
  if (!hasIO) {
    bgEls.forEach(loadBg);
  } else {
    var bgIO = new IntersectionObserver(function (entries, obs) {
      entries.forEach(function (e) {
        if (e.isIntersecting) { loadBg(e.target); obs.unobserve(e.target); }
      });
    }, { rootMargin: '200px 0px' });
    bgEls.forEach(function (el) { bgIO.observe(el); });
  }

  /* ---------- Client logos: fall back to the dashed placeholder box
     until real files are dropped into img/brandlogo/ ---------- */
  document.querySelectorAll('.clients__logo img').forEach(function (img) {
    img.addEventListener('load', function () { img.closest('.clients__logo').classList.add('has-img'); });
    img.addEventListener('error', function () { img.remove(); });
  });

  /* ---------- Mobile menu ---------- */
  var burger = document.getElementById('burger');
  var nav = document.getElementById('nav');
  var overlay = document.getElementById('navOverlay');
  var mqMobile = window.matchMedia('(max-width: 900px)');

  function openMenu() {
    nav.classList.add('is-open');
    burger.classList.add('is-open');
    burger.setAttribute('aria-expanded', 'true');
    overlay.hidden = false;
    requestAnimationFrame(function () { overlay.classList.add('is-visible'); });
    document.body.style.overflow = 'hidden';
  }
  function closeMenu() {
    nav.classList.remove('is-open');
    burger.classList.remove('is-open');
    burger.setAttribute('aria-expanded', 'false');
    overlay.classList.remove('is-visible');
    document.body.style.overflow = '';
    setTimeout(function () { overlay.hidden = true; }, 300);
  }
  if (burger) {
    burger.addEventListener('click', function () {
      nav.classList.contains('is-open') ? closeMenu() : openMenu();
    });
    overlay.addEventListener('click', closeMenu);
    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape' && nav.classList.contains('is-open')) closeMenu();
    });
    nav.addEventListener('click', function (e) {
      if (e.target.closest('a[href]') && mqMobile.matches) closeMenu();
    });
    mqMobile.addEventListener('change', function () {
      if (!mqMobile.matches) {
        closeMenu();
        // collapse mobile accordions when returning to desktop
        document.querySelectorAll('.has-drop.is-open').forEach(function (el) {
          el.classList.remove('is-open');
          var t = el.querySelector('.nav__toggle');
          if (t) t.setAttribute('aria-expanded', 'false');
        });
      }
    });
  }

  /* ---------- Category dropdowns: accordion on mobile ---------- */
  document.querySelectorAll('.nav__toggle').forEach(function (btn) {
    btn.addEventListener('click', function (e) {
      if (!mqMobile.matches) return;              // desktop uses hover
      e.preventDefault();
      var item = btn.parentElement;
      var open = item.classList.toggle('is-open');
      btn.setAttribute('aria-expanded', open ? 'true' : 'false');
    });
  });

  /* ---------- Hero typing effect ---------- */
  var typedEl = document.getElementById('typed');
  var caretEl = document.getElementById('caret');
  if (typedEl && !reduceMotion) {
    var phrases = ['Дизайнерский ремонт', 'Ремонт под ключ', 'Капитальный ремонт', 'Ремонт в новостройке'];
    var pi = 0, ci = 0, deleting = false;
    // start from the phrase already in the DOM
    ci = phrases[0].length;
    function tick() {
      var word = phrases[pi];
      if (!deleting) {
        ci++;
        typedEl.textContent = word.slice(0, ci);
        if (ci >= word.length) { deleting = true; return setTimeout(tick, 1600); }
      } else {
        ci--;
        typedEl.textContent = word.slice(0, ci);
        if (ci <= 0) { deleting = false; pi = (pi + 1) % phrases.length; return setTimeout(tick, 260); }
      }
      setTimeout(tick, deleting ? 45 : 85);
    }
    setTimeout(tick, 1800);
  }

  /* ---------- Scroll reveal (with stagger) ---------- */
  var revealEls = document.querySelectorAll('.reveal');
  if (reduceMotion || !hasIO) {
    revealEls.forEach(function (el) { el.classList.add('is-visible'); });
  } else {
    var io = new IntersectionObserver(function (entries, obs) {
      entries.forEach(function (e) {
        if (e.isIntersecting) { e.target.classList.add('is-visible'); obs.unobserve(e.target); }
      });
    }, { threshold: 0.1, rootMargin: '0px 0px -40px 0px' });
    revealEls.forEach(function (el) {
      var sibs = Array.prototype.filter.call(el.parentElement.children, function (c) { return c.classList.contains('reveal'); });
      var idx = sibs.indexOf(el);
      if (idx > 0 && sibs.length > 1) el.style.transitionDelay = Math.min(idx * 55, 300) + 'ms';
      io.observe(el);
    });
  }

  /* ---------- FAQ single-open ---------- */
  var faqItems = document.querySelectorAll('.faq2__item');
  faqItems.forEach(function (item) {
    item.addEventListener('toggle', function () {
      if (item.open) faqItems.forEach(function (o) { if (o !== item) o.open = false; });
    });
  });

  /* ---------- Scroll-spy: highlight active nav link ---------- */
  var navLinks = Array.prototype.slice.call(document.querySelectorAll('.nav__link'));
  var sections = navLinks
    .map(function (a) { var id = a.getAttribute('href'); return id && id.charAt(0) === '#' ? document.querySelector(id) : null; })
    .filter(Boolean);
  if (hasIO && sections.length) {
    var spy = new IntersectionObserver(function (entries) {
      entries.forEach(function (e) {
        if (e.isIntersecting) {
          navLinks.forEach(function (a) { a.classList.toggle('is-active', a.getAttribute('href') === '#' + e.target.id); });
        }
      });
    }, { rootMargin: '-45% 0px -50% 0px' });
    sections.forEach(function (s) { spy.observe(s); });
  }

  /* ---------- Smooth scroll with sticky-header offset ---------- */
  var hdr = document.getElementById('hdr');
  document.querySelectorAll('a[href^="#"]').forEach(function (a) {
    a.addEventListener('click', function (e) {
      var id = a.getAttribute('href');
      if (id.length < 2) return;
      var target = document.querySelector(id);
      if (!target) return;
      e.preventDefault();
      var offset = (hdr ? hdr.offsetHeight : 0) + 12;
      var top = target.getBoundingClientRect().top + window.scrollY - offset;
      window.scrollTo({ top: top, behavior: reduceMotion ? 'auto' : 'smooth' });
    });
  });

  /* ---------- Quiz calculator ----------
     Multi-step: тип ремонта → комнаты → площадь → состояние → контакты → результат.
     Price is only revealed after the contact step is submitted. */
  var quizForm = document.getElementById('quizForm');
  if (quizForm) {
    var quizSteps = Array.prototype.slice.call(quizForm.querySelectorAll('.quiz__step'));
    var quizProgress = document.getElementById('quizProgress');
    var quizBack = document.getElementById('quizBack');
    var quizAnswers = {};
    var quizStepIndex = 0; // index into quizSteps

    function quizShow(idx) {
      quizSteps.forEach(function (s, i) { s.classList.toggle('is-active', i === idx); });
      quizStepIndex = idx;
      var pct = Math.min(((idx + 1) / quizSteps.length) * 100, 100);
      quizProgress.style.width = pct + '%';
      quizBack.hidden = idx === 0 || quizSteps[idx].dataset.step === 'result';
    }

    quizForm.querySelectorAll('.quiz__options').forEach(function (group) {
      group.addEventListener('click', function (e) {
        var btn = e.target.closest('.quiz__option');
        if (!btn) return;
        var field = group.getAttribute('data-field');
        quizAnswers[field] = btn.getAttribute('data-value');
        Array.prototype.forEach.call(group.children, function (o) { o.classList.remove('is-selected'); });
        btn.classList.add('is-selected');
        setTimeout(function () {
          if (quizStepIndex < quizSteps.length - 1) quizShow(quizStepIndex + 1);
        }, 220);
      });
    });

    quizBack.addEventListener('click', function () {
      if (quizStepIndex > 0) quizShow(quizStepIndex - 1);
    });

    function quizCalc(a) {
      var rates = {
        cosmetic: [4900, 6500], capital: [7500, 10000], turnkey: [6500, 9500], design: [15000, 22000]
      };
      var areaMap = { '30': 25, '50': 40, '70': 60, '100': 85, '120': 110 };
      var conditionMult = { shell: 0.9, old: 1.15, good: 1 };
      var r = rates[a.type] || rates.turnkey;
      var area = areaMap[a.area] || 40;
      var mult = conditionMult[a.condition] || 1;
      var min = Math.round((r[0] * area * mult) / 1000) * 1000;
      var max = Math.round((r[1] * area * mult) / 1000) * 1000;
      return { min: min, max: max };
    }

    function fmtRub(n) { return n.toLocaleString('ru-RU') + ' ₽'; }

    quizForm.addEventListener('submit', function (e) {
      e.preventDefault();
      var nameEl = document.getElementById('quizName');
      var phoneEl = document.getElementById('quizPhone');
      if (!nameEl.value.trim() || !phoneEl.value.trim()) {
        (nameEl.value.trim() ? phoneEl : nameEl).focus();
        return;
      }
      quizAnswers.name = nameEl.value.trim();
      quizAnswers.phone = phoneEl.value.trim();

      var price = quizCalc(quizAnswers);
      document.getElementById('quizResultPrice').textContent = fmtRub(price.min) + ' — ' + fmtRub(price.max);
      var nameSpan = document.getElementById('quizResultName');
      nameSpan.textContent = quizAnswers.name ? ', ' + quizAnswers.name : '';

      var resultIdx = quizSteps.findIndex(function (s) { return s.dataset.step === 'result'; });
      quizShow(resultIdx);
    });

    quizShow(0);
  }
})();
