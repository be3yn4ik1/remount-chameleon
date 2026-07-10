/* ============================================================
   РемонтПрофи — main.js (vanilla, no dependencies)
   ============================================================ */
(function () {
  'use strict';

  var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  /* ---------- Sticky header shadow ---------- */
  var header = document.getElementById('header');
  function onScroll() {
    if (window.scrollY > 8) header.classList.add('is-stuck');
    else header.classList.remove('is-stuck');
  }
  window.addEventListener('scroll', onScroll, { passive: true });
  onScroll();

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
    // collapse open submenus
    document.querySelectorAll('.has-mega.is-open').forEach(function (el) {
      el.classList.remove('is-open');
      var b = el.querySelector('.nav__link');
      if (b) b.setAttribute('aria-expanded', 'false');
    });
  }
  burger.addEventListener('click', function () {
    nav.classList.contains('is-open') ? closeMenu() : openMenu();
  });
  overlay.addEventListener('click', closeMenu);
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' && nav.classList.contains('is-open')) closeMenu();
  });

  /* ---------- Mega-menu toggles (accessible) ---------- */
  document.querySelectorAll('.has-mega > .nav__link').forEach(function (btn) {
    btn.addEventListener('click', function (e) {
      // On mobile: toggle accordion. On desktop hover handles it, but click also works.
      if (mqMobile.matches) {
        e.preventDefault();
        var parent = btn.parentElement;
        var isOpen = parent.classList.toggle('is-open');
        btn.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
      }
    });
  });

  // Close mobile menu when a real link is clicked
  nav.addEventListener('click', function (e) {
    var link = e.target.closest('a[href]');
    if (link && mqMobile.matches) closeMenu();
  });

  // Reset state when crossing breakpoint
  mqMobile.addEventListener('change', function () {
    if (!mqMobile.matches) closeMenu();
  });

  /* ---------- Scroll reveal ---------- */
  var revealEls = document.querySelectorAll('.reveal');
  if (reduceMotion || !('IntersectionObserver' in window)) {
    revealEls.forEach(function (el) { el.classList.add('is-visible'); });
  } else {
    var io = new IntersectionObserver(function (entries, obs) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          entry.target.classList.add('is-visible');
          obs.unobserve(entry.target);
        }
      });
    }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });

    // Stagger siblings within the same grid for a nicer effect
    revealEls.forEach(function (el) {
      var parent = el.parentElement;
      var sibs = Array.prototype.filter.call(parent.children, function (c) { return c.classList.contains('reveal'); });
      var idx = sibs.indexOf(el);
      if (idx > 0 && sibs.length > 1) el.style.transitionDelay = Math.min(idx * 60, 300) + 'ms';
      io.observe(el);
    });
  }

  /* ---------- Count-up stats ---------- */
  var counters = document.querySelectorAll('[data-count]');
  function animateCount(el) {
    var target = parseInt(el.getAttribute('data-count'), 10) || 0;
    if (reduceMotion) { el.textContent = target.toLocaleString('ru-RU'); return; }
    var duration = 1400, start = null;
    function frame(ts) {
      if (!start) start = ts;
      var p = Math.min((ts - start) / duration, 1);
      var eased = 1 - Math.pow(1 - p, 3); // easeOutCubic
      el.textContent = Math.round(target * eased).toLocaleString('ru-RU');
      if (p < 1) requestAnimationFrame(frame);
    }
    requestAnimationFrame(frame);
  }
  if ('IntersectionObserver' in window) {
    var cio = new IntersectionObserver(function (entries, obs) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) { animateCount(entry.target); obs.unobserve(entry.target); }
      });
    }, { threshold: 0.6 });
    counters.forEach(function (c) { cio.observe(c); });
  } else {
    counters.forEach(animateCount);
  }

  /* ---------- FAQ: close others (single-open accordion) ---------- */
  var faqItems = document.querySelectorAll('.faq__item');
  faqItems.forEach(function (item) {
    item.addEventListener('toggle', function () {
      if (item.open) {
        faqItems.forEach(function (other) { if (other !== item) other.open = false; });
      }
    });
  });

  /* ---------- Phone mask (light) ---------- */
  var phone = document.getElementById('phone');
  if (phone) {
    phone.addEventListener('input', function () {
      var d = phone.value.replace(/\D/g, '');
      if (d.startsWith('8')) d = '7' + d.slice(1);
      if (!d.startsWith('7')) d = '7' + d;
      d = d.slice(0, 11);
      var out = '+7';
      if (d.length > 1) out += ' (' + d.slice(1, 4);
      if (d.length >= 4) out += ') ' + d.slice(4, 7);
      if (d.length >= 7) out += '-' + d.slice(7, 9);
      if (d.length >= 9) out += '-' + d.slice(9, 11);
      phone.value = out;
    });
  }

  /* ---------- Lead form validation ---------- */
  var form = document.getElementById('leadForm');
  if (form) {
    var success = document.getElementById('formSuccess');

    function setError(name, msg) {
      var field = form.querySelector('[name="' + name + '"]').closest('.field');
      var err = form.querySelector('.field__error[data-for="' + name + '"]');
      if (msg) { field.classList.add('is-invalid'); if (err) err.textContent = msg; }
      else { field.classList.remove('is-invalid'); if (err) err.textContent = ''; }
    }

    form.addEventListener('submit', function (e) {
      e.preventDefault();
      var ok = true;
      var name = form.name.value.trim();
      var digits = form.phone.value.replace(/\D/g, '');

      if (name.length < 2) { setError('name', 'Пожалуйста, укажите имя'); ok = false; }
      else setError('name', '');

      if (digits.length < 11) { setError('phone', 'Введите корректный номер телефона'); ok = false; }
      else setError('phone', '');

      if (!ok) {
        var firstInvalid = form.querySelector('.field.is-invalid input');
        if (firstInvalid) firstInvalid.focus();
        return;
      }

      // Demo: no backend on a static presentation site.
      form.querySelectorAll('.field, button[type="submit"], .cta__policy').forEach(function (el) { el.style.display = 'none'; });
      success.hidden = false;
      success.setAttribute('role', 'status');
    });

    // Clear error on input
    ['name', 'phone'].forEach(function (n) {
      form[n].addEventListener('input', function () { setError(n, ''); });
    });
  }

  /* ---------- Smooth-scroll offset for sticky header ---------- */
  document.querySelectorAll('a[href^="#"]').forEach(function (a) {
    a.addEventListener('click', function (e) {
      var id = a.getAttribute('href');
      if (id.length < 2) return;
      var target = document.querySelector(id);
      if (!target) return;
      e.preventDefault();
      var top = target.getBoundingClientRect().top + window.scrollY - (header.offsetHeight + 12);
      window.scrollTo({ top: top, behavior: reduceMotion ? 'auto' : 'smooth' });
    });
  });
})();
