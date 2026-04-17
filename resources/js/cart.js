/* ==================== CART ==================== */
/**
 * Vanilla, lazy-loaded from app.js when `.woocommerce-cart-form` is on the page.
 *
 * Handles:
 *   - Coupon apply/remove (AJAX → full page refresh to let WC rebuild totals).
 *   - Scroll-snap navigation for the recommended-products slider.
 *
 * Quantity changes still use WC's native "Update cart" submit.
 */

(function () {
  var cfg = window.natura_cart;
  if (!cfg || !cfg.ajax_url) return;

  /* ---------------- Coupon form ---------------- */

  var msgTimer = null;

  function getMsgEl() {
    return document.getElementById('mn-coupon-message');
  }

  function showMessage(text, kind) {
    var msgEl = getMsgEl();
    if (!msgEl) return;
    var cls = kind === 'success' ? 'woocommerce-message' : 'woocommerce-error';
    msgEl.innerHTML = '<div class="mn-coupon-notice ' + cls + '"><span>' + escapeHtml(text) + '</span></div>';

    // Force reflow so the new node picks up the is-visible transition.
    void msgEl.offsetWidth;
    msgEl.classList.add('is-visible');

    clearTimeout(msgTimer);
    msgTimer = setTimeout(function () {
      msgEl.classList.remove('is-visible');
      setTimeout(function () { if (msgEl) msgEl.innerHTML = ''; }, 250);
    }, kind === 'success' ? 3200 : 4200);
  }

  function swapCouponShell(html) {
    if (typeof html !== 'string' || !html) return;

    var current = document.querySelector('[data-coupon-shell]');
    if (!current) return;

    var tmp = document.createElement('div');
    tmp.innerHTML = html;
    var incoming = tmp.querySelector('[data-coupon-shell]');
    if (!incoming) return;

    // Skip when nothing changed — no flicker on qty updates with coupon
    // already applied/not applied.
    if (current.innerHTML.trim() === incoming.innerHTML.trim()) return;

    current.classList.add('is-swapping');
    setTimeout(function () {
      current.innerHTML = incoming.innerHTML;
      current.classList.remove('is-swapping');
      current.classList.add('is-entering');
      void current.offsetWidth;
      current.classList.remove('is-entering');
    }, 180);
  }

  function escapeHtml(str) {
    return String(str).replace(/[&<>"']/g, function (c) {
      return (
        { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c] || c
      );
    });
  }

  function postCart(body) {
    var form = new FormData();
    form.append('action', 'natura_cart');
    form.append('nonce', cfg.nonce);
    Object.keys(body).forEach(function (k) {
      form.append(k, body[k]);
    });

    return fetch(cfg.ajax_url, {
      method: 'POST',
      credentials: 'same-origin',
      body: form,
    }).then(function (r) {
      return r.json();
    });
  }

  // Delegated — form is re-rendered after each apply/remove via fragment swap.
  document.addEventListener('submit', function (e) {
    var form = e.target;
    if (!form || form.id !== 'mn-ajax-coupon-form') return;
    e.preventDefault();
    applyCoupon(form);
  });

  document.addEventListener('click', function (e) {
    var rm = e.target.closest && e.target.closest('.remove-coupon-btn');
    if (!rm) return;
    e.preventDefault();
    removeCoupon(rm);
  });

  function applyCoupon(form) {
    var input = form.querySelector('#mn_coupon_code');
    var btn = form.querySelector('#mn-apply-coupon-btn');
    var code = input ? input.value.trim() : '';

    if (!code) {
      shake(input);
      showMessage(cfg.i18n.empty_code, 'error');
      return;
    }

    setBtnLoading(btn, true);
    if (input) input.disabled = true;

    postCart({ op: 'apply_coupon', coupon_code: code })
      .then(function (res) {
        if (res && res.success) {
          applyFragments(res.data);
          showMessage(res.data.message, 'success');
        } else {
          shake(input);
          showMessage((res && res.data && res.data.message) || cfg.i18n.error, 'error');
          setBtnLoading(btn, false);
          if (input) input.disabled = false;
        }
      })
      .catch(function () {
        showMessage(cfg.i18n.error, 'error');
        setBtnLoading(btn, false);
        if (input) input.disabled = false;
      });
  }

  function removeCoupon(btn) {
    var code = btn.dataset.coupon;
    if (!code) return;

    btn.classList.add('is-loading');

    postCart({ op: 'remove_coupon', coupon_code: code })
      .then(function (res) {
        if (res && res.success) {
          applyFragments(res.data);
          showMessage(res.data.message, 'success');
        } else {
          btn.classList.remove('is-loading');
          showMessage((res && res.data && res.data.message) || cfg.i18n.error, 'error');
        }
      })
      .catch(function () {
        btn.classList.remove('is-loading');
        showMessage(cfg.i18n.error, 'error');
      });
  }

  // Shared helper: apply all fragments from an AJAX response (used by qty
  // update, coupon apply, coupon remove, remove item).
  function applyFragments(data) {
    if (!data) return;

    if (data.is_empty) { location.reload(); return; }

    swapFragments(data);
    if (typeof data.coupon_html === 'string') swapCouponShell(data.coupon_html);
  }

  function setBtnLoading(btn, on) {
    if (!btn) return;
    btn.classList.toggle('is-loading', !!on);
    btn.disabled = !!on;
    btn.setAttribute('aria-busy', on ? 'true' : 'false');
  }

  function shake(el) {
    if (!el) return;
    el.classList.remove('is-invalid');
    void el.offsetWidth;
    el.classList.add('is-invalid');
    setTimeout(function () { el.classList.remove('is-invalid'); }, 500);
  }

  /* ---------------- AJAX: qty change → update cart fragments ---------------- */

  var cartForm = document.querySelector('.woocommerce-cart-form');
  var qtyTimers = Object.create(null);
  var qtyLatest = Object.create(null);

  // Keep the cart page in sync with mini-cart changes (qty / remove).
  // Empty cart → full reload to let WC render the empty-cart template.
  // Otherwise → fetch fresh fragments and swap items/totals/shipping in-place.
  var syncTimer = null;
  var isSyncingFromMiniCart = false;
  // Counts cart-originated updates still waiting for their echo back through
  //   swapFragments → updated_cart_totals (jQuery) → mini-cart.js refetches →
  //   natura:mini-cart:updated
  // The counter is incremented inside swapFragments at the exact moment the
  // jQuery trigger fires, so it's 1:1 with expected echoes. Each echo decrements
  // and gets skipped, preventing a second spinner. The safety timer resets the
  // counter if the echo never arrives (e.g., no jQuery, mini-cart fetch fails).
  var pendingOwnEchoes = 0;
  var ownEchoSafetyTimer = null;

  document.addEventListener('natura:mini-cart:updated', function (e) {
    if (! cartForm) return;
    if (e && e.detail && e.detail.is_empty) {
      location.reload();
      return;
    }

    // Cart.js triggered this round-trip — fragments already applied locally.
    if (pendingOwnEchoes > 0) {
      pendingOwnEchoes--;
      if (pendingOwnEchoes === 0) clearTimeout(ownEchoSafetyTimer);
      return;
    }

    // Debounce: the drawer can fire several times during a qty drag.
    clearTimeout(syncTimer);
    syncTimer = setTimeout(function () {
      isSyncingFromMiniCart = true;
      cartForm.classList.add('is-updating');
      postCart({ op: 'get' })
        .then(function (res) {
          if (res && res.success) applyFragments(res.data);
        })
        .catch(function () {})
        .finally(function () {
          cartForm.classList.remove('is-updating');
          // Release the flag after the DOM settles so swapFragments can skip
          // re-triggering `updated_cart_totals` and avoid a ping-pong loop.
          setTimeout(function () { isSyncingFromMiniCart = false; }, 300);
        });
    }, 150);
  });


  /* ---------------- Bundle rows: collapsible child items ---------------- */

  var BUNDLE_ICON_INFO =
    '<svg class="icon-info" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>';
  var BUNDLE_ICON_CLOSE =
    '<svg class="icon-close" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>';

  function initBundleToggle() {
    var rows = document.querySelectorAll('.woocommerce-cart-form tr.bundle_table_item');
    rows.forEach(function (bundleRow) {
      var nameCell = bundleRow.querySelector('.product-name');
      if (!nameCell || nameCell.querySelector('.bundle-toggle-btn')) return;

      var next = bundleRow.nextElementSibling;
      var bundled = [];
      while (next && next.classList.contains('bundled_table_item')) {
        bundled.push(next);
        next = next.nextElementSibling;
      }

      if (!bundled.length) return;

      var link = nameCell.querySelector('a');
      if (!link) return;

      var label = document.createElement('span');
      label.className = 'bundle-label';
      label.textContent = 'PACHET';

      var btn = document.createElement('button');
      btn.type = 'button';
      btn.className = 'bundle-toggle-btn';
      btn.title = 'Vezi produsele din pachet';
      btn.setAttribute('aria-expanded', 'false');
      btn.innerHTML = BUNDLE_ICON_INFO + BUNDLE_ICON_CLOSE;

      var info = document.createElement('span');
      info.className = 'bundle-info-text';
      info.textContent = bundled.length + (bundled.length === 1 ? ' produs' : ' produse');

      link.insertAdjacentElement('afterend', info);
      link.insertAdjacentElement('afterend', btn);
      link.insertAdjacentElement('afterend', label);

      btn.addEventListener('click', function (e) {
        e.preventDefault();
        var open = !btn.classList.contains('is-open');
        btn.classList.toggle('is-open', open);
        btn.setAttribute('aria-expanded', open ? 'true' : 'false');
        bundled.forEach(function (row) { row.classList.toggle('is-visible', open); });
      });
    });
  }

  initBundleToggle();

  function swapFragments(data) {
    if (!data) return;

    if (data.is_empty) {
      // Cart emptied — full reload so the WC empty template takes over.
      location.reload();
      return;
    }

    var itemsTarget = document.querySelector('[data-cart-items]');
    if (itemsTarget && typeof data.items_html === 'string') {
      // Replace rendered item rows (preserve the .actions row at the end).
      itemsTarget.querySelectorAll('[data-cart-item-key]').forEach(function (el) {
        el.remove();
      });
      var actionsRow = itemsTarget.querySelector('tr:has(.actions)') || itemsTarget.querySelector('.actions');
      if (actionsRow && actionsRow.tagName !== 'TR') actionsRow = actionsRow.closest('tr');
      var wrap = document.createElement('tbody');
      wrap.innerHTML = data.items_html;
      Array.prototype.slice.call(wrap.children).forEach(function (node) {
        if (actionsRow) {
          itemsTarget.insertBefore(node, actionsRow);
        } else {
          itemsTarget.appendChild(node);
        }
      });
    }

    var totals = document.querySelector('.cart_totals');
    if (totals && typeof data.totals_html === 'string') {
      var newTotals = document.createElement('div');
      newTotals.innerHTML = data.totals_html.trim();
      var replacement = newTotals.firstElementChild;
      if (replacement) totals.replaceWith(replacement);
    }

    var ship = document.querySelector('.free-shipping-box');
    if (ship && typeof data.shipping_html === 'string') {
      var wrap2 = document.createElement('div');
      wrap2.innerHTML = data.shipping_html.trim();
      var newShip = wrap2.firstElementChild;
      if (newShip) ship.replaceWith(newShip);
    }

    // Update any mini-cart count badges on the page.
    document.querySelectorAll('[data-mini-cart-count]').forEach(function (el) {
      el.textContent = String(data.count || 0);
    });

    // Let other scripts (mini-cart, analytics) react just like WC does natively.
    // Skip when we're syncing FROM the mini-cart — otherwise we'd bounce back
    // into mini-cart.js which listens to `updated_cart_totals` and loops.
    if (!isSyncingFromMiniCart && window.jQuery) {
      // Increment here (not in scheduleQtyUpdate) so the counter is in sync
      // with the actual jQuery trigger — one echo per trigger.
      pendingOwnEchoes++;
      clearTimeout(ownEchoSafetyTimer);
      ownEchoSafetyTimer = setTimeout(function () { pendingOwnEchoes = 0; }, 5000);
      window.jQuery(document.body).trigger('updated_cart_totals');
    }

    // Re-init bundle toggles — the rows were re-rendered from scratch.
    initBundleToggle();
  }

  function scheduleQtyUpdate(key, qty) {
    qtyLatest[key] = qty;
    clearTimeout(qtyTimers[key]);
    qtyTimers[key] = setTimeout(function () {
      var finalQty = qtyLatest[key];
      delete qtyTimers[key];
      delete qtyLatest[key];

      if (cartForm) cartForm.classList.add('is-updating');

      postCart({ op: 'update_qty', key: key, qty: finalQty })
        .then(function (res) {
          if (res && res.success) {
            applyFragments(res.data);
          } else {
            showMessage((res && res.data && res.data.message) || cfg.i18n.error, 'error');
          }
        })
        .catch(function () {
          showMessage(cfg.i18n.error, 'error');
        })
        .finally(function () {
          if (cartForm) cartForm.classList.remove('is-updating');
        });
    }, 450);
  }

  if (cartForm) {
    // Qty change (both +/- via qty-stepper and direct edits).
    cartForm.addEventListener('change', function (e) {
      var input = e.target;
      if (!input.matches || !input.matches('.qty-stepper__input, input.qty')) return;

      var row = input.closest('[data-cart-item-key]');
      if (!row) return;

      var qty = parseInt(input.value, 10);
      if (isNaN(qty) || qty < 0) qty = 0;

      scheduleQtyUpdate(row.getAttribute('data-cart-item-key'), qty);
    });

    // Intercept remove links → AJAX.
    cartForm.addEventListener('click', function (e) {
      var link = e.target.closest && e.target.closest('[data-cart-item-remove]');
      if (!link) return;
      e.preventDefault();

      var key = link.getAttribute('data-cart-item-remove');
      if (!key) return;

      var row = link.closest('[data-cart-item-key]');
      if (row) row.classList.add('is-removing');

      postCart({ op: 'remove_item', key: key })
        .then(function (res) {
          if (res && res.success) {
            applyFragments(res.data);
          } else {
            if (row) row.classList.remove('is-removing');
            showMessage((res && res.data && res.data.message) || cfg.i18n.error, 'error');
          }
        })
        .catch(function () {
          if (row) row.classList.remove('is-removing');
          showMessage(cfg.i18n.error, 'error');
        });
    });
  }

  /* ---------------- WC added_to_cart (upsell) → reload ---------------- */
  // When an upsell product is added via WC's AJAX add-to-cart, reload the
  // page so the recommended slider and the "no-more-upsell-discount" state
  // rebuild. WC fires a jQuery event; bridge via window.jQuery.
  if (window.jQuery) {
    window.jQuery(document.body).on('added_to_cart', function (e, fragments, cart_hash, $button) {
      if ($button && $button.data && $button.data('upsell_discount') == 1) {
        location.reload();
      }
    });
  }

  /* ---------------- Recommended slider (scroll-snap nav) ---------------- */

  var track = document.querySelector('[data-cart-slider]');
  if (track) {
    var container = track.closest('.cart-recommended');
    var arrows = container ? container.querySelectorAll('.cart-recommended__arrow') : [];

    function scrollByItem(dir) {
      var item = track.querySelector('.cart-recommended__item');
      if (!item) return;
      var style = getComputedStyle(track);
      var gap = parseFloat(style.columnGap || style.gap || 0) || 0;
      var delta = item.getBoundingClientRect().width + gap;
      track.scrollBy({ left: dir === 'next' ? delta : -delta, behavior: 'smooth' });
    }

    function updateArrows() {
      if (!arrows.length) return;
      var max = track.scrollWidth - track.clientWidth - 1;
      var atStart = track.scrollLeft <= 1;
      var atEnd = track.scrollLeft >= max;

      arrows.forEach(function (a) {
        if (a.dataset.dir === 'prev') a.disabled = atStart;
        if (a.dataset.dir === 'next') a.disabled = atEnd;
      });
    }

    arrows.forEach(function (a) {
      a.addEventListener('click', function () {
        scrollByItem(a.dataset.dir);
      });
    });

    track.addEventListener('scroll', updateArrows, { passive: true });
    window.addEventListener('resize', updateArrows);
    updateArrows();
  }
})();
