/**
 * Single blog post — related posts Swiper.
 * Lazy-loaded from app.js when `.blog_slider` is present.
 *
 * Breakpoints ported 1:1 from mana-naturii/assets/js/app.js:419-455.
 */

import Swiper from 'swiper';
import { Navigation, Pagination } from 'swiper/modules';
// Swiper CSS is shipped eagerly from app.js — see that file's header.

(function () {
  const el = document.querySelector('.blog_slider');
  if (!el) return;

  const slides = el.querySelectorAll('.swiper-slide');

  new Swiper(el, {
    modules: [Navigation, Pagination],
    effect: 'slide',
    loop: slides.length > 4,
    grabCursor: true,
    slidesPerView: 4,
    spaceBetween: 24,
    navigation: {
      nextEl: '.related-next',
      prevEl: '.related-prev',
    },
    pagination: {
      el: '.blog-pagination',
      clickable: true,
    },
    breakpoints: {
      0:    { slidesPerView: 1, spaceBetween: 12 },
      576:  { slidesPerView: 1, spaceBetween: 14 },
      768:  { slidesPerView: 2, spaceBetween: 16 },
      992:  { slidesPerView: 2, spaceBetween: 18 },
      1200: { slidesPerView: 3, spaceBetween: 24 },
    },
  });
})();
