/**
 * Single product page: gallery Swiper, related products slider,
 * tabs/accordion, sticky price bar.
 *
 * Lazy-loaded from app.js only on pages where .product-main-swiper or
 * .related_products_slider or .custom-product-tabs or #sticky-price-container is present.
 */

import Swiper from 'swiper';
import { Thumbs, Navigation, Pagination, Autoplay } from 'swiper/modules';
// Swiper CSS is shipped eagerly from app.js — see that file's header.

/* ==================== GALLERY (SWIPER) ==================== */
(function () {
  const mainEl = document.querySelector('.product-main-swiper');
  if (!mainEl) return;

  const thumbsEl = document.querySelector('.product-thumbs-swiper');
  let thumbs = null;

  if (thumbsEl) {
    thumbs = new Swiper(thumbsEl, {
      slidesPerView: 'auto',
      spaceBetween: 10,
      direction: 'vertical',
      watchSlidesProgress: true,
      breakpoints: {
        0: { direction: 'horizontal', slidesPerView: 'auto' },
        769: { direction: 'vertical' },
      },
    });
  }

  new Swiper(mainEl, {
    modules: [Thumbs, Navigation],
    slidesPerView: 1,
    spaceBetween: 10,
    thumbs: thumbs ? { swiper: thumbs } : undefined,
  });
})();

/* ==================== RELATED PRODUCTS (SWIPER) ==================== */
(function () {
  const relatedEl = document.querySelector('.related_products_slider');
  if (!relatedEl) return;

  new Swiper(relatedEl, {
    modules: [Navigation, Pagination],
    slidesPerView: 2,
    spaceBetween: 12,
    navigation: {
      prevEl: '.related-prev',
      nextEl: '.related-next',
    },
    pagination: {
      el: '.related-pagination',
      clickable: true,
    },
    breakpoints: {
      640: { slidesPerView: 2, spaceBetween: 15 },
      1024: { slidesPerView: 3, spaceBetween: 20 },
      1280: { slidesPerView: 4, spaceBetween: 30 },
    },
  });
})();

/* ==================== UP-SELLS (SWIPER) ==================== */
(function () {
  const upsellsEl = document.querySelector('.upsells_products_slider');
  if (!upsellsEl) return;

  new Swiper(upsellsEl, {
    modules: [Navigation, Pagination],
    slidesPerView: 2,
    spaceBetween: 12,
    watchOverflow: true,
    navigation: {
      prevEl: '.upsells-prev',
      nextEl: '.upsells-next',
    },
    pagination: {
      el: '.upsells-pagination',
      clickable: true,
    },
    breakpoints: {
      640: { slidesPerView: 2, spaceBetween: 15 },
      1024: { slidesPerView: 3, spaceBetween: 20 },
      1280: { slidesPerView: 4, spaceBetween: 30 },
    },
  });
})();

/* ==================== REVIEWS SLIDER (shared with home) ==================== */
(function () {
  const reviewsEl = document.querySelector('.home-reviews [data-home-swiper="reviews"]');
  if (!reviewsEl) return;

  const init = () => {
    new Swiper(reviewsEl, {
      modules: [Autoplay],
      slidesPerView: 1,
      spaceBetween: 24,
      grabCursor: true,
      watchOverflow: true,
      observer: true,
      observeParents: true,
      observeSlideChildren: true,
      loop: reviewsEl.querySelectorAll('.swiper-slide').length > 1,
      autoplay: { delay: 5000, disableOnInteraction: false },
      on: {
        init() {
          requestAnimationFrame(() => reviewsEl.classList.add('is-ready'));
        },
      },
    });
  };

  // Wait for layout (ancestor grid/flex sometimes hasn't resolved yet)
  if (document.readyState === 'complete') {
    requestAnimationFrame(init);
  } else {
    window.addEventListener('load', () => requestAnimationFrame(init), { once: true });
  }
})();

/* ==================== PRODUCT TABS (DESKTOP) + ACCORDION (MOBILE) ==================== */
(function () {
  const tabsRoot = document.querySelector('.custom-product-tabs');
  if (!tabsRoot) return;

  const tabButtons = tabsRoot.querySelectorAll('.custom-tab-btn');
  const accordionButtons = tabsRoot.querySelectorAll('.custom-accordion-btn');
  const panels = tabsRoot.querySelectorAll('.custom-tab-panel');

  const isMobile = () => window.innerWidth <= 768;

  function activateTab(key) {
    tabButtons.forEach((btn) => btn.classList.toggle('active', btn.dataset.tab === key));
    panels.forEach((panel) => panel.classList.toggle('active', panel.id === 'tab-' + key));
  }

  tabButtons.forEach((btn) => {
    btn.addEventListener('click', () => activateTab(btn.dataset.tab));
  });

  accordionButtons.forEach((btn) => {
    btn.addEventListener('click', function () {
      if (!isMobile()) return;

      const item = this.closest('.custom-tab-accordion-item');
      const isOpen = item.classList.contains('accordion-open');
      const btnTop = this.getBoundingClientRect().top;

      tabsRoot
        .querySelectorAll('.custom-tab-accordion-item')
        .forEach((el) => el.classList.remove('accordion-open'));

      if (!isOpen) item.classList.add('accordion-open');

      const drift = this.getBoundingClientRect().top - btnTop;
      if (Math.abs(drift) > 1) window.scrollBy(0, drift);
    });
  });

  function initView() {
    if (!isMobile()) {
      tabsRoot
        .querySelectorAll('.custom-tab-accordion-item')
        .forEach((el) => el.classList.remove('accordion-open'));
      if (tabButtons.length > 0) activateTab(tabButtons[0].dataset.tab);
    } else {
      tabButtons.forEach((btn) => btn.classList.remove('active'));
      panels.forEach((panel) => panel.classList.remove('active'));
    }
  }

  initView();
  window.addEventListener('resize', initView);
})();

/* ==================== STICKY PRICE BAR ==================== */
(function () {
  const container = document.getElementById('sticky-price-container');
  if (!container) return;

  const mainAddToCart = document.querySelector('.single_add_to_cart_button');
  const mainQtyInput = document.querySelector('form.cart input.qty');
  const trigger = mainAddToCart || document.querySelector('.summary.entry-summary');

  function updateVisibility() {
    if (!trigger) {
      container.classList.add('is-visible');
      return;
    }
    const rect = trigger.getBoundingClientRect();
    container.classList.toggle('is-visible', rect.bottom < 0);
  }

  let ticking = false;
  window.addEventListener(
    'scroll',
    function () {
      if (ticking) return;
      ticking = true;
      requestAnimationFrame(function () {
        updateVisibility();
        ticking = false;
      });
    },
    { passive: true },
  );
  updateVisibility();

  const qtyInput = container.querySelector('.sticky-qty-input');

  // .qty-stepper handles +/- clicks and fires `change` on the input.
  // Mirror the sticky input value into the main product form's qty.
  if (qtyInput) {
    qtyInput.addEventListener('change', () => {
      if (mainQtyInput) {
        mainQtyInput.value = qtyInput.value;
        mainQtyInput.dispatchEvent(new Event('change', { bubbles: true }));
      }
    });
  }

  // Call the mini-cart add endpoint directly. Independent of mini-cart.js
  // load state to avoid a race when the user clicks the sticky bar before
  // the lazy-loaded drawer script is ready.
  function addViaEndpoint(productId, qty) {
    var cfg = window.natura_mini_cart;
    if (!cfg) return Promise.reject(new Error('Configurare indisponibilă. Reîncarcă pagina.'));

    var form = new FormData();
    form.append('action', 'natura_mini_cart');
    form.append('op', 'add');
    form.append('nonce', cfg.nonce);
    form.append('product_id', productId);
    form.append('qty', qty);

    return fetch(cfg.ajax_url, {
      method: 'POST',
      credentials: 'same-origin',
      body: form,
    })
      .then(function (r) { return r.json(); })
      .then(function (json) {
        if (!json || !json.success) {
          throw new Error((json && json.data && json.data.message) || cfg.i18n.error);
        }
        return json.data;
      });
  }

  container.querySelectorAll('.sticky-add-to-cart, .sticky-add-to-cart-mobile').forEach(function (btn) {
    if (btn.tagName !== 'BUTTON') return;
    btn.addEventListener('click', function (e) {
      e.preventDefault();

      const productId = parseInt(
        btn.getAttribute('data-product-id') || btn.getAttribute('data-product_id') || '0',
        10,
      );
      const qty = qtyInput ? Math.max(1, parseInt(qtyInput.value, 10) || 1) : 1;

      if (!productId) return;

      if (mainQtyInput && qtyInput) {
        mainQtyInput.value = qtyInput.value;
        mainQtyInput.dispatchEvent(new Event('change', { bubbles: true }));
      }

      btn.classList.add('is-loading');
      btn.disabled = true;

      const done = () => {
        btn.classList.remove('is-loading');
        btn.disabled = false;
      };

      const fireAddedToCart = () => {
        if (!window.jQuery) return;
        window.jQuery(document.body).trigger('added_to_cart', [null, '', window.jQuery(btn)]);
      };

      addViaEndpoint(productId, qty)
        .then((data) => {
          if (window.NaturaMiniCart && typeof window.NaturaMiniCart.refresh === 'function') {
            window.NaturaMiniCart.refresh();
          }
          if (data && !data.is_empty) fireAddedToCart();
          done();
        })
        .catch((err) => {
          if (window.NaturaToast && typeof window.NaturaToast.show === 'function') {
            window.NaturaToast.show(err.message, { variant: 'error', duration: 4000 });
          }
          done();
        });
    });
  });
})();
