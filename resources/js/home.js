/**
 * Home template — lazy-loaded orchestrator.
 * Each section gets its own init when its root element is present.
 */

import Swiper from 'swiper';
import { Navigation, Pagination, Autoplay } from 'swiper/modules';
// Swiper core/navigation/pagination CSS is shipped eagerly from app.js —
// see that file's header for context. Don't re-import here or the styles
// end up in this lazy chunk and arrive too late for LCP.

import { Fancybox } from '@fancyapps/ui';
import '@fancyapps/ui/dist/fancybox/fancybox.css';

/* ---------------- Hero slider ---------------- */

document.querySelectorAll('[data-hero-swiper]').forEach(function (el) {
  var slides = el.querySelectorAll('.swiper-slide');
  var multiple = slides.length > 1;

  new Swiper(el, {
    modules: [Navigation, Pagination, Autoplay],
    loop: multiple,
    speed: 600,
    autoplay: multiple
      ? { delay: 5500, disableOnInteraction: false, pauseOnMouseEnter: true }
      : false,
    navigation: {
      prevEl: el.querySelector('[data-hero-prev]'),
      nextEl: el.querySelector('[data-hero-next]'),
    },
    pagination: {
      el: el.querySelector('[data-hero-pagination]'),
      clickable: true,
    },
    a11y: {
      prevSlideMessage: 'Slide anterior',
      nextSlideMessage: 'Slide următor',
    },
  });
});

/* ---------------- Generic home sliders (categories, products, etc.) ----------
 *
 * Each slider root:
 *   <div class="swiper" data-home-swiper="<name>">...</div>
 *
 * Scoped nav/pagination via matching data attrs:
 *   data-home-slider-prev="<name>"
 *   data-home-slider-next="<name>"
 *   data-home-slider-pagination="<name>"
 *
 * Per-slider Swiper options (breakpoints, spacing) are set in HOME_SLIDER_OPTIONS below.
 */

/**
 * Common Swiper options for every product slider on the home page.
 * Mirrors the legacy mana-naturii breakpoints.
 */
var HOME_PRODUCT_OPTIONS = {
  slidesPerView: 2,
  spaceBetween: 12,
  breakpoints: {
    576: { slidesPerView: 2, spaceBetween: 14 },
    768: { slidesPerView: 3, spaceBetween: 16 },
    992: { slidesPerView: 3, spaceBetween: 18 },
    1200: { slidesPerView: 4, spaceBetween: 20 },
    1400: { slidesPerView: 4, spaceBetween: 24 },
  },
};

var HOME_SLIDER_OPTIONS = {
  categories: {
    slidesPerView: 3,
    spaceBetween: 12,
    breakpoints: {
      640: { slidesPerView: 3, spaceBetween: 20 },
      900: { slidesPerView: 4, spaceBetween: 24 },
      1200: { slidesPerView: 5, spaceBetween: 24 },
      1400: { slidesPerView: 6, spaceBetween: 24 },
    },
  },
  benefits: {
    slidesPerView: 1,
    spaceBetween: 16,
    breakpoints: {
      640: { slidesPerView: 2, spaceBetween: 20 },
      1024: { slidesPerView: 3, spaceBetween: 24 },
    },
  },
  testimonials: {
    slidesPerView: 2,
    spaceBetween: 12,
    centerInsufficientSlides: true,
    breakpoints: {
      640: { slidesPerView: 2, spaceBetween: 20 },
      900: { slidesPerView: 3, spaceBetween: 24 },
      1200: { slidesPerView: 4, spaceBetween: 24 },
    },
  },
  reviews: {
    slidesPerView: 1,
    spaceBetween: 24,
  },
  'popular-packages': HOME_PRODUCT_OPTIONS,
  'new-products': HOME_PRODUCT_OPTIONS,
  'promo-products': HOME_PRODUCT_OPTIONS,
};

/**
 * Init each slider when it enters (or is near) the viewport. Spreads init
 * cost across scrolling instead of a synchronous pile-up on load, and masks
 * the layout "settling" with a CSS fade-in (see `.is-ready` in home.css).
 */
function initHomeSlider(el) {
  var name = el.getAttribute('data-home-swiper');
  var opts = HOME_SLIDER_OPTIONS[name];
  if (!opts) return;

  var container = el.closest('.home-slider') || el.parentElement;

  new Swiper(
    el,
    Object.assign(
      {
        modules: [Navigation, Pagination],
        speed: 500,
        // Hide nav + pagination automatically when all slides already fit
        // (e.g. testimonials section with only 3 videos on a 4-per-view breakpoint).
        watchOverflow: true,
        navigation: {
          prevEl: container.querySelector('[data-home-slider-prev="' + name + '"]'),
          nextEl: container.querySelector('[data-home-slider-next="' + name + '"]'),
        },
        pagination: {
          el: container.querySelector('[data-home-slider-pagination="' + name + '"]'),
          clickable: true,
        },
        on: {
          init: function () {
            // Defer one frame so Swiper's layout settles before the fade-in.
            requestAnimationFrame(function () {
              el.classList.add('is-ready');
            });
          },
        },
      },
      opts
    )
  );
}

var homeSliders = document.querySelectorAll('[data-home-swiper]');

if ('IntersectionObserver' in window && homeSliders.length) {
  var io = new IntersectionObserver(
    function (entries, observer) {
      entries.forEach(function (entry) {
        if (!entry.isIntersecting) return;
        observer.unobserve(entry.target);
        initHomeSlider(entry.target);
      });
    },
    { rootMargin: '300px 0px' }
  );

  homeSliders.forEach(function (el) {
    io.observe(el);
  });
} else {
  // Fallback (very old browsers): init everything up front.
  homeSliders.forEach(initHomeSlider);
}

/* ---------------- Fancybox video (testimonials) ----------------
 * Fancybox binds declaratively via `data-fancybox` attributes.
 * Bound globally — one init handles all galleries on the page.
 */

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
