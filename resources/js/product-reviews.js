/**
 * „Recenziile clienților noștri" — carusel (single product + home).
 * Lazy-importat din app.js când `.product-reviews__swiper` e în DOM, ca să
 * funcționeze pe orice pagină unde apare blocul (nu doar single product).
 */
import Swiper from 'swiper';
import { Navigation, Pagination } from 'swiper/modules';

document.querySelectorAll('.product-reviews__swiper').forEach((el) => {
  const slider = el.closest('.product-reviews__slider') || el.parentElement;

  new Swiper(el, {
    modules: [Navigation, Pagination],
    slidesPerView: 1,
    spaceBetween: 20,
    grabCursor: true,
    watchOverflow: true,
    navigation: {
      prevEl: slider.querySelector('.product-reviews__nav--prev'),
      nextEl: slider.querySelector('.product-reviews__nav--next'),
    },
    pagination: {
      el: slider.querySelector('.product-reviews__pagination'),
      clickable: true,
    },
    breakpoints: {
      768: { slidesPerView: 2, spaceBetween: 24 },
    },
  });
});
