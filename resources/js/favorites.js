/* ==================== FAVORITES ==================== */
/**
 * Heart-toggle UX. Lazy-loaded from app.js.
 *
 * Backend: `app/favorites.php` (WP AJAX action: `natura_favorites`).
 * Config:  global `natura_favorites` { ajax_url, i18n } — `ids` + `nonce`
 *          are fetched on init to avoid page-cache poisoning on live.
 */

(function () {
  var cfg = window.natura_favorites;
  if (!cfg || !cfg.ajax_url) return;

  cfg.ids = [];
  cfg.nonce = null;

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

  function reflectAllFromCfg() {
    document.querySelectorAll('.natura-fav-btn').forEach(function (btn) {
      var pid = parseInt(btn.dataset.productId, 10);
      if (!pid) return;
      reflectState(pid, cfg.ids.indexOf(pid) !== -1);
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

  // Hydration: fetch fresh ids + nonce from a non-cached endpoint.
  // admin-ajax.php is never cached, so this gives us the real per-user state
  // even when the surrounding HTML came from a full-page cache.
  var hydrated;

  function hydrate() {
    hydrated = fetch(cfg.ajax_url + '?action=natura_favorites&op=get', {
      method: 'GET',
      credentials: 'same-origin',
      headers: { 'X-Requested-With': 'XMLHttpRequest' },
    })
      .then(function (r) { return r.json(); })
      .then(function (res) {
        if (!res || !res.success) throw new Error('hydration');
        var data = res.data || {};
        cfg.ids = Array.isArray(data.ids) ? data.ids.map(function (n) { return parseInt(n, 10); }) : [];
        cfg.nonce = data.nonce || null;
        reflectAllFromCfg();
        if (typeof data.count === 'number') updateBadge(data.count);
      })
      .catch(function () {
        // Silent: the first click will re-await this promise and surface an
        // error toast if the nonce is still missing.
      });
    return hydrated;
  }

  hydrate();

  // bfcache restore (Safari/Firefox Back): JS modules aren't re-executed, so
  // cfg.ids is frozen at the moment of navigation away. Re-hydrate so the
  // user sees the real state in case another tab changed it meanwhile, or
  // their last toggle finished after navigation via keepalive.
  window.addEventListener('pageshow', function (e) {
    if (e.persisted) hydrate();
  });

  // Incremental merge helpers — operate on the LIVE cfg.ids at call time so
  // concurrent toggles don't step on each other. Never assign a snapshot of
  // cfg.ids back in, or a stale response overwrites a newer optimistic state.
  function ensureId(id) {
    if (cfg.ids.indexOf(id) === -1) cfg.ids.push(id);
  }
  function removeId(id) {
    cfg.ids = cfg.ids.filter(function (x) { return x !== id; });
  }

  function doToggle(btn) {
    var productId = parseInt(btn.dataset.productId, 10);
    if (!productId || inflight.has(productId)) return;

    // Optimistic update — flip this product's state in-place. No snapshot of
    // cfg.ids is taken, so other in-flight toggles keep their own optimistic
    // flips and neither race can clobber the other.
    var wasActive = btn.classList.contains('is-active');
    var willBeActive = !wasActive;
    reflectState(productId, willBeActive);

    if (willBeActive) {
      ensureId(productId);
    } else {
      removeId(productId);
    }
    updateBadge(cfg.ids.length);
    showToast(willBeActive ? cfg.i18n.added : cfg.i18n.removed);

    inflight.add(productId);
    btn.classList.add('is-loading');

    var form = new FormData();
    form.append('action', 'natura_favorites');
    // Idempotent: send the desired final state, not a toggle. A retry or a
    // duplicated request just re-asserts the same state server-side.
    form.append('op', 'set');
    form.append('desired', willBeActive ? '1' : '0');
    form.append('nonce', cfg.nonce);
    form.append('product_id', String(productId));

    fetch(cfg.ajax_url, {
      method: 'POST',
      credentials: 'same-origin',
      // Survive page unload — if the user clicks a heart and immediately
      // navigates, the request continues to the server instead of getting
      // aborted, which used to cause the "count dropped by one" bug.
      keepalive: true,
      headers: { 'X-Requested-With': 'XMLHttpRequest' },
      body: form,
    })
      .then(function (r) { return r.json(); })
      .then(function (res) {
        if (!res || !res.success) {
          throw new Error((res && res.data && res.data.message) || cfg.i18n.error);
        }
        var data = res.data;
        // Merge ONLY this product's state with what the server confirmed.
        // Don't replace cfg.ids with data.ids — that's a point-in-time server
        // snapshot that may predate a concurrent toggle whose response hasn't
        // landed yet, and applying it here would roll back that toggle's
        // optimistic state.
        reflectState(data.product_id, !!data.in);
        if (data.in) {
          ensureId(data.product_id);
        } else {
          removeId(data.product_id);
        }
        updateBadge(cfg.ids.length);
        if (data.nonce) cfg.nonce = data.nonce;

        if (!data.in) {
          removeCardFromList(data.product_id);
        }
      })
      .catch(function (err) {
        // Revert only this product's optimistic flip. Other concurrent toggles
        // keep their state untouched.
        reflectState(productId, wasActive);
        if (wasActive) {
          ensureId(productId);
        } else {
          removeId(productId);
        }
        updateBadge(cfg.ids.length);
        showToast((err && err.message) || cfg.i18n.error);
      })
      .finally(function () {
        btn.classList.remove('is-loading');
        inflight.delete(productId);
      });
  }

  function toggle(btn) {
    if (cfg.nonce) {
      doToggle(btn);
      return;
    }
    // Hydration pending or failed — wait, then retry once.
    hydrated.finally(function () {
      if (!cfg.nonce) {
        showToast(cfg.i18n.error);
        return;
      }
      doToggle(btn);
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
