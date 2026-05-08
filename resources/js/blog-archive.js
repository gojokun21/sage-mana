/**
 * Blog archive — filtre cluster, search client-side, feedback newsletter.
 * Lazy-loaded din app.js cand `.blog-page` e prezent.
 *
 * FAQ-ul foloseste <details> nativ, nu necesita JS.
 */

import Swiper from 'swiper';
import { Pagination } from 'swiper/modules';
// Swiper CSS is shipped eagerly from app.js — see that file's header.

(function () {
  const root = document.querySelector('.blog-page');
  if (!root) return;

  // Cluster slider
  const clusterSliderEl = root.querySelector('[data-blog-cluster-slider] .blog-cluster-swiper');
  if (clusterSliderEl) {
    const slides = clusterSliderEl.querySelectorAll('.swiper-slide');
    const sliderRoot = clusterSliderEl.closest('[data-blog-cluster-slider]');

    new Swiper(clusterSliderEl, {
      modules: [Pagination],
      slidesPerView: 1,
      spaceBetween: 18,
      grabCursor: true,
      watchOverflow: true,
      loop: slides.length > 3,
      pagination: {
        el: sliderRoot ? sliderRoot.querySelector('.blog-cluster-slider__pagination') : null,
        clickable: true,
      },
      breakpoints: {
        576: { slidesPerView: 1.4, spaceBetween: 18 },
        768: { slidesPerView: 2, spaceBetween: 20 },
        992: { slidesPerView: 2.4, spaceBetween: 22 },
        1200: { slidesPerView: 3, spaceBetween: 24 },
      },
    });
  }

  const filtersWrap = root.querySelector('[data-blog-filters]');
  const grid = root.querySelector('[data-blog-grid]');
  const searchInput = root.querySelector('[data-blog-search]');
  const emptyState = root.querySelector('[data-blog-empty]');

  let currentFilter = 'all';
  let currentSearch = '';

  function applyFilters() {
    if (!grid) return;

    const cards = grid.querySelectorAll('.blog-card');
    const q = currentSearch.trim().toLowerCase();
    let visible = 0;

    cards.forEach(function (card) {
      const cat = card.getAttribute('data-cat') || '';
      const haystack = (card.getAttribute('data-search') || '').toLowerCase();

      const okCat = currentFilter === 'all' || cat === currentFilter;
      const okSearch = !q || haystack.indexOf(q) !== -1;

      if (okCat && okSearch) {
        card.style.display = '';
        visible++;
      } else {
        card.style.display = 'none';
      }
    });

    if (emptyState) {
      emptyState.hidden = visible !== 0;
    }
  }

  // Filter buttons
  if (filtersWrap) {
    filtersWrap.addEventListener('click', function (e) {
      const btn = e.target.closest('.blog-filter');
      if (!btn) return;

      filtersWrap.querySelectorAll('.blog-filter').forEach(function (b) {
        b.classList.remove('is-active');
        b.setAttribute('aria-selected', 'false');
      });
      btn.classList.add('is-active');
      btn.setAttribute('aria-selected', 'true');

      currentFilter = btn.getAttribute('data-filter') || 'all';
      applyFilters();
    });
  }

  // Search input
  if (searchInput) {
    let searchTimer = null;
    searchInput.addEventListener('input', function (e) {
      clearTimeout(searchTimer);
      searchTimer = setTimeout(function () {
        currentSearch = e.target.value || '';
        applyFilters();
      }, 120);
    });
  }

  // Cluster card click — preselecteaza filtrul si scroll la sectiunea recente
  // Click pe link-ul cardului normal navigheaza la arhiva categoriei (default).
  // Aici interceptam doar click-ul cu modifier `Alt` ca quick-filter pe pagina,
  // ca sa nu rupem comportamentul default (navigare).
  // — In schimb expunem un comportament mai simplu: butoanele de filtru raman
  // singura sursa de adevar pentru filtrarea pe pagina.

  // Newsletter feedback (vizual — fara backend deocamdata)
  const newsletterForm = root.querySelector('[data-blog-newsletter]');
  if (newsletterForm) {
    newsletterForm.addEventListener('submit', function (e) {
      e.preventDefault();
      const button = newsletterForm.querySelector('button');
      if (!button) return;
      const original = button.textContent;
      button.textContent = 'Te-ai abonat — verifică inbox';
      button.disabled = true;
      setTimeout(function () {
        button.textContent = original;
        button.disabled = false;
        newsletterForm.reset();
      }, 4000);
    });
  }
})();
