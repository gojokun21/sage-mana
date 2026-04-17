/* ==================== FAVORITES ==================== */
/**
 * Heart-toggle UX. Lazy-loaded from app.js.
 *
 * Backend: `app/favorites.php` (WP AJAX action: `natura_favorites`).
 * Config: global `natura_favorites` { ajax_url, nonce, ids[], i18n }.
 */

(function () {
  var cfg = window.natura_favorites;
  if (!cfg || !cfg.ajax_url) return;

  var inflight = new Set();
  var toastTimer = null;

  function updateBadge(count) {
    document.querySelectorAll('[data-favorites-count]').forEach(function (el) {
      el.textContent = String(count);
      el.classList.remove('is-bumping');
      void el.offsetWidth;
      el.classList.add('is-bumping');
    });
  }

  function reflectState(productId, isIn) {
    document.querySelectorAll('.natura-fav-btn[data-product-id="' + productId + '"]').forEach(function (btn) {
      btn.classList.toggle('is-active', isIn);
      btn.setAttribute('aria-pressed', isIn ? 'true' : 'false');
      btn.setAttribute(
        'aria-label',
        isIn
          ? btn.dataset.labelRemove || 'Elimină din favorite'
          : btn.dataset.labelAdd || 'Adaugă la favorite'
      );
    });
  }

  // On the Favorites list page, un-favoriting a product should remove the
  // card from the grid. Find any card inside `.natura-favorites-list` that
  // contains a button for this product and fade it out.
  function removeCardFromList(productId) {
    var list = document.querySelector('.natura-favorites-list');
    if (!list) return;

    var btn = list.querySelector('.natura-fav-btn[data-product-id="' + productId + '"]');
    var card = btn ? btn.closest('li.product, .product-card') : null;
    if (!card) return;

    card.classList.add('is-removing');
    card.addEventListener(
      'transitionend',
      function handler(e) {
        if (e.target !== card || e.propertyName !== 'opacity') return;
        card.removeEventListener('transitionend', handler);
        card.remove();
        if (!list.querySelector('li.product, .product-card')) {
          // Last one — reload so the server-rendered empty state takes over.
          location.reload();
        }
      }
    );

    // Safety net if transitionend doesn't fire (e.g. display:none ancestor).
    setTimeout(function () {
      if (card.isConnected) {
        card.remove();
        if (!list.querySelector('li.product, .product-card')) location.reload();
      }
    }, 500);
  }

  function showToast(message) {
    var toast = document.getElementById('natura-fav-toast');
    if (!toast) {
      toast = document.createElement('div');
      toast.id = 'natura-fav-toast';
      toast.className = 'natura-fav-toast';
      toast.setAttribute('role', 'status');
      toast.setAttribute('aria-live', 'polite');
      document.body.appendChild(toast);
    }
    toast.textContent = message;
    toast.classList.add('is-visible');
    clearTimeout(toastTimer);
    toastTimer = setTimeout(function () {
      toast.classList.remove('is-visible');
    }, 1800);
  }

  function toggle(btn) {
    var productId = parseInt(btn.dataset.productId, 10);
    if (!productId || inflight.has(productId)) return;

    // Optimistic update — flip the heart immediately, revert on failure.
    var wasActive = btn.classList.contains('is-active');
    var willBeActive = !wasActive;
    reflectState(productId, willBeActive);

    var currentIds = Array.isArray(cfg.ids) ? cfg.ids.slice() : [];
    var optimisticIds = willBeActive
      ? currentIds.indexOf(productId) === -1 ? currentIds.concat(productId) : currentIds
      : currentIds.filter(function (id) { return id !== productId; });
    updateBadge(optimisticIds.length);
    cfg.ids = optimisticIds;
    showToast(willBeActive ? cfg.i18n.added : cfg.i18n.removed);

    inflight.add(productId);

    var form = new FormData();
    form.append('action', 'natura_favorites');
    form.append('op', 'toggle');
    form.append('nonce', cfg.nonce);
    form.append('product_id', String(productId));

    fetch(cfg.ajax_url, { method: 'POST', credentials: 'same-origin', body: form })
      .then(function (r) {
        return r.json();
      })
      .then(function (res) {
        if (!res || !res.success) {
          throw new Error((res && res.data && res.data.message) || cfg.i18n.error);
        }
        var data = res.data;
        // Reconcile with server truth (handles race conditions).
        reflectState(data.product_id, !!data.in);
        updateBadge(data.count);
        cfg.ids = data.ids || [];

        if (!data.in) {
          removeCardFromList(data.product_id);
        }
      })
      .catch(function (err) {
        // Revert optimistic change.
        reflectState(productId, wasActive);
        updateBadge(currentIds.length);
        cfg.ids = currentIds;
        showToast((err && err.message) || cfg.i18n.error);
      })
      .finally(function () {
        inflight.delete(productId);
      });
  }

  document.addEventListener('click', function (e) {
    var btn = e.target.closest && e.target.closest('.natura-fav-btn');
    if (!btn) return;
    e.preventDefault();
    e.stopPropagation();
    toggle(btn);
  });
})();
