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

    var navClose = document.getElementById('navClose');
    if (navClose) navClose.addEventListener('click', closeMenu);
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
})();
