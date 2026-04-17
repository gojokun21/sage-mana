/**
 * About template — behaviors ported from legacy mana-naturii.
 *
 * Lazy-loaded from app.js when `.about-template` is on the page.
 *
 *   1. `.stats_grid` — Swiper slider with responsive breakpoints + pagination.
 *   2. `.counter` — animated number counter triggered on intersect.
 *   3. `[data-readmore]` — collapsible description on mobile (≤640px).
 */

import Swiper from 'swiper';
import { Navigation, Pagination } from 'swiper/modules';
import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';

/* ==================== STATS SWIPER ==================== */
(function () {
  const el = document.querySelector('.stats_grid');
  if (!el) return;

  new Swiper(el, {
    modules: [Pagination],
    effect: 'slide',
    loop: true,
    grabCursor: true,
    slidesPerView: 4,
    spaceBetween: 20,
    pagination: {
      el: '.stats-pagination',
      clickable: true,
    },
    breakpoints: {
      0: { slidesPerView: 1, spaceBetween: 12 },
      576: { slidesPerView: 2, spaceBetween: 14 },
      768: { slidesPerView: 2, spaceBetween: 16 },
      992: { slidesPerView: 3, spaceBetween: 18 },
      1200: { slidesPerView: 4, spaceBetween: 20 },
    },
  });
})();

/* ==================== COUNTERS ==================== */
(function () {
  const counters = document.querySelectorAll('.about-template .counter');
  if (!counters.length) return;

  const easeOutQuad = (t) => t * (2 - t);

  const animate = (counter) => {
    const target = +counter.getAttribute('data-target') || 0;
    const duration = 2000;
    const start = performance.now();

    const tick = (now) => {
      const elapsed = now - start;
      const progress = Math.min(elapsed / duration, 1);
      const value = Math.floor(easeOutQuad(progress) * target);
      counter.innerText = value + '+';

      if (progress < 1) {
        requestAnimationFrame(tick);
      } else {
        counter.innerText = target + '+';
      }
    };

    requestAnimationFrame(tick);
  };

  const io = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          animate(entry.target);
          io.unobserve(entry.target);
        }
      });
    },
    { threshold: 0.2 },
  );

  counters.forEach((c) => io.observe(c));
})();

/* ==================== READ-MORE (mobile only) ==================== */
(function () {
  const COLLAPSED_HEIGHT = 100;
  const MOBILE_BP = 640;

  const wrap = document.querySelector('[data-readmore]');
  const btn = document.querySelector('[data-readmore-toggle]');
  if (!wrap || !btn) return;

  const inner = wrap.querySelector('.about_description__inner');
  if (!inner) return;

  let expanded = false;

  function measure() {
    wrap.style.maxHeight = 'none';
    const full = inner.scrollHeight;
    wrap.style.maxHeight = '';
    return full;
  }

  function apply() {
    const isMobile = window.innerWidth <= MOBILE_BP;
    const fullH = measure();
    const needsClamp = isMobile && fullH > COLLAPSED_HEIGHT + 20;

    if (!needsClamp) {
      wrap.classList.remove('is-clamped', 'is-expanded');
      wrap.style.maxHeight = '';
      btn.hidden = true;
      btn.setAttribute('aria-expanded', 'false');
      expanded = false;
      return;
    }

    btn.hidden = false;

    if (expanded) {
      wrap.classList.remove('is-clamped');
      wrap.classList.add('is-expanded');
      wrap.style.maxHeight = fullH + 'px';
      btn.textContent = 'Mai puțin';
      btn.setAttribute('aria-expanded', 'true');
    } else {
      wrap.classList.add('is-clamped');
      wrap.classList.remove('is-expanded');
      wrap.style.maxHeight = COLLAPSED_HEIGHT + 'px';
      btn.textContent = 'Află mai mult';
      btn.setAttribute('aria-expanded', 'false');
    }
  }

  btn.addEventListener('click', () => {
    expanded = !expanded;
    apply();
  });

  let resizeTimer;
  window.addEventListener('resize', () => {
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(apply, 150);
  });

  apply();
})();

/* ==================== TESTIMONIALS + REVIEWS SWIPERS ==================== */
/*
 * The testimonials + reviews partials use `[data-home-swiper="..."]`, which is
 * only initialized on `.home-template` via home.js. Mirror the same behaviour
 * here so both sliders work on the about page too.
 */
(function () {
  const testimonialsEl = document.querySelector('[data-home-swiper="testimonials"]');
  const reviewsEl = document.querySelector('.home-reviews [data-home-swiper="reviews"]');

  if (!testimonialsEl && !reviewsEl) return;

  Promise.all([
    import('swiper/modules'),
    import('@fancyapps/ui'),
    import('@fancyapps/ui/dist/fancybox/fancybox.css'),
  ]).then(([modules, { Fancybox }]) => {
    const { Autoplay } = modules;

    if (testimonialsEl && !testimonialsEl.swiper) {
      const container = testimonialsEl.closest('.home-slider') || testimonialsEl.parentElement;
      new Swiper(testimonialsEl, {
        modules: [Navigation, Pagination],
        slidesPerView: 2,
        spaceBetween: 12,
        speed: 500,
        grabCursor: true,
        watchOverflow: true,
        centerInsufficientSlides: true,
        observer: true,
        observeParents: true,
        navigation: {
          prevEl: container.querySelector('[data-home-slider-prev="testimonials"]'),
          nextEl: container.querySelector('[data-home-slider-next="testimonials"]'),
        },
        pagination: {
          el: container.querySelector('[data-home-slider-pagination="testimonials"]'),
          clickable: true,
        },
        breakpoints: {
          640: { slidesPerView: 2, spaceBetween: 20 },
          900: { slidesPerView: 3, spaceBetween: 24 },
          1200: { slidesPerView: 4, spaceBetween: 24 },
        },
      });
    }

    if (reviewsEl && !reviewsEl.swiper) {
      new Swiper(reviewsEl, {
        modules: [Autoplay],
        slidesPerView: 1,
        spaceBetween: 24,
        grabCursor: true,
        watchOverflow: true,
        observer: true,
        observeParents: true,
        loop: reviewsEl.querySelectorAll('.swiper-slide').length > 1,
        autoplay: { delay: 5000, disableOnInteraction: false },
      });
    }

    // Fancybox for video testimonials (declarative via data-fancybox attrs).
    if (document.querySelector('[data-fancybox]')) {
      Fancybox.bind('[data-fancybox]', {
        Toolbar: {
          display: {
            left: ['infobar'],
            middle: [],
            right: ['close'],
          },
        },
        Thumbs: false,
      });
    }
  });
})();
