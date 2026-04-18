/**
 * Natura Mini Cart — vanilla JS drawer controller.
 *
 * Public API exposed on `window.NaturaMiniCart`:
 *   - open()       Force-opens the drawer.
 *   - close()      Force-closes the drawer.
 *   - refresh()    Fetches latest fragment without changing open state.
 *   - add({ product_id, qty, variation_id, variation })  Adds + opens drawer.
 */

(function () {
  var cfg = window.natura_mini_cart;
  if (!cfg) return;

  var drawer = document.getElementById('miniCartDrawer');
  var overlay = document.getElementById('miniCartOverlay');
  if (!drawer || !overlay) return;

  var body = drawer.querySelector('[data-mini-cart-body]');
  var closeBtn = drawer.querySelector('[data-mini-cart-close]');
  var inFlight = null;
  var pendingUpdates = Object.create(null);
  var updateTimers = Object.create(null);

  /* ---------------- Drawer state ---------------- */

  function open() {
    drawer.classList.add('is-open');
    overlay.classList.add('is-visible');
    drawer.setAttribute('aria-hidden', 'false');
    overlay.setAttribute('aria-hidden', 'false');
    document.body.classList.add('mini-cart-open');
    document.querySelectorAll('[data-mini-cart-trigger]').forEach(function (el) {
      el.setAttribute('aria-expanded', 'true');
    });
  }

  function close() {
    drawer.classList.remove('is-open');
    overlay.classList.remove('is-visible');
    drawer.setAttribute('aria-hidden', 'true');
    overlay.setAttribute('aria-hidden', 'true');
    document.body.classList.remove('mini-cart-open');
    document.querySelectorAll('[data-mini-cart-trigger]').forEach(function (el) {
      el.setAttribute('aria-expanded', 'false');
    });
  }

  /* ---------------- AJAX ---------------- */

  function request(op, extra) {
    if (inFlight && typeof inFlight.abort === 'function') {
      try { inFlight.abort(); } catch (_) {}
    }
    var controller = typeof AbortController !== 'undefined' ? new AbortController() : null;
    inFlight = controller;

    var form = new FormData();
    form.append('action', 'natura_mini_cart');
    form.append('nonce', cfg.nonce);
    form.append('op', op);
    if (extra) {
      Object.keys(extra).forEach(function (k) {
        var v = extra[k];
        if (v === undefined || v === null) return;
        if (typeof v === 'object') {
          Object.keys(v).forEach(function (sk) { form.append(k + '[' + sk + ']', v[sk]); });
        } else {
          form.append(k, v);
        }
      });
    }

    drawer.classList.add('is-loading');

    return fetch(cfg.ajax_url, {
      method: 'POST',
      credentials: 'same-origin',
      body: form,
      signal: controller ? controller.signal : undefined,
    })
      .then(function (r) { return r.json(); })
      .then(function (json) {
        if (!json || !json.success) {
          var err = new Error((json && json.data && json.data.message) || cfg.i18n.error);
          err.data = json && json.data;
          throw err;
        }
        render(json.data);
        // Passive `get` refetches (triggered by WC jQuery events like
        // `updated_cart_totals` / `wc_fragments_refreshed`) must NOT fire
        // this event — cart.js listens to it and would sync back, creating
        // a ping-pong that locks `.woocommerce-cart-form` with pointer-events:
        // none mid-interaction. Only real mutations should propagate.
        if (op !== 'get') {
          dispatch('natura:mini-cart:updated', json.data);
        }
        return json.data;
      })
      .catch(function (err) {
        if (err && err.name === 'AbortError') throw err;
        console.error('[mini-cart]', err);
        dispatch('natura:mini-cart:error', { message: err.message });
        throw err; // propagate so callers can surface a message to the user
      })
      .finally(function () {
        drawer.classList.remove('is-loading');
        inFlight = null;
      });
  }

  function render(payload) {
    if (!payload) return;

    if (typeof payload.html === 'string') {
      body.innerHTML = payload.html;
    }

    // Update all count badges on the page.
    document.querySelectorAll('[data-mini-cart-count]').forEach(function (el) {
      el.textContent = String(payload.count || 0);
      el.classList.toggle('is-empty', !payload.count);
    });
  }

  function dispatch(name, detail) {
    document.dispatchEvent(new CustomEvent(name, { detail: detail }));
  }

  /* ---------------- Quantity handling (debounced) ---------------- */

  function scheduleUpdate(key, qty) {
    pendingUpdates[key] = qty;
    clearTimeout(updateTimers[key]);
    updateTimers[key] = setTimeout(function () {
      var finalQty = pendingUpdates[key];
      delete pendingUpdates[key];
      delete updateTimers[key];
      request('update', { key: key, qty: finalQty }).catch(function () {});
    }, 350);
  }

  /* ---------------- Event delegation ---------------- */

  // Open trigger (cart icon in header).
  document.addEventListener('click', function (e) {
    var trigger = e.target.closest('[data-mini-cart-trigger]');
    if (trigger) {
      e.preventDefault();
      if (drawer.classList.contains('is-open')) close();
      else open();
      return;
    }

    if (e.target.closest('[data-mini-cart-close]') || e.target === overlay) {
      close();
    }
  });

  // Drawer body delegation: remove.
  drawer.addEventListener('click', function (e) {
    var removeBtn = e.target.closest('[data-mini-cart-remove]');
    if (!removeBtn) return;
    var item = removeBtn.closest('[data-cart-item-key]');
    if (!item) return;
    item.classList.add('is-removing');
    request('remove', { key: item.getAttribute('data-cart-item-key') }).catch(function () {});
  });

  // Quantity: react to `change` on the input. `.qty-stepper` dispatches
  // this event on +/- clicks, so this one listener covers both manual
  // typing and stepper buttons without double-firing.
  drawer.addEventListener('change', function (e) {
    var input = e.target.closest('[data-mini-cart-qty-input]');
    if (!input) return;
    var item = input.closest('[data-cart-item-key]');
    if (!item) return;
    var qty = Math.max(0, parseInt(input.value, 10) || 0);
    input.value = qty;
    scheduleUpdate(item.getAttribute('data-cart-item-key'), qty);
  });

  // ESC to close.
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' && drawer.classList.contains('is-open')) {
      close();
    }
  });

  /* ---------------- WooCommerce interop ---------------- */

  // WC core fires jQuery events on document.body after its own AJAX
  // add/remove operations. Listen via window.jQuery (loaded by WC) without
  // adding jQuery as a build dependency. Because this module is imported
  // dynamically, jQuery may not be ready yet on first run — retry briefly.
  function bindWcEvents() {
    if (!window.jQuery) return false;

    var $body = window.jQuery(document.body);

    // Refresh cart data silently — the add-to-cart confirmation modal
    // (cart-modal.js) handles the user-facing notification instead of
    // auto-opening the drawer.
    $body.on('added_to_cart', function () {
      request('get').catch(function () {});
    });

    $body.on('removed_from_cart updated_cart_totals wc_cart_emptied wc_fragments_refreshed', function () {
      request('get').catch(function () {});
    });

    return true;
  }

  if (!bindWcEvents()) {
    var tries = 0;
    var poll = setInterval(function () {
      tries++;
      if (bindWcEvents() || tries > 40) clearInterval(poll); // ~4s max
    }, 100);
  }

  /* ---------------- Public API ---------------- */

  window.NaturaMiniCart = {
    open: open,
    close: close,
    refresh: function () { return request('get').catch(function () {}); },
    add: function (args) {
      args = args || {};
      return request('add', {
        product_id: args.product_id,
        qty: args.qty || 1,
        variation_id: args.variation_id || 0,
        variation: args.variation || null,
      });
    },
  };
})();
