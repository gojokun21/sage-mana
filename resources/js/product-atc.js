/**
 * Product Add-to-Cart — routes all single-product adds through
 * NaturaMiniCart so the drawer opens with the correct quantity.
 *
 * Two code paths:
 *
 *   1. Simple product (custom `simple.php` template):
 *      `<a class="mn-atc-btn" data-product_id="…">` — not inside a form.
 *      We call NaturaMiniCart.add() directly.
 *
 *   2. Form-based product (bundle, variable, grouped):
 *      `<button type="submit" class="mn-atc-btn">` inside `<form class="cart">`.
 *      Bundles/variable plugins attach their own validation on the submit
 *      event. We listen in the bubble phase and bail if `e.defaultPrevented`
 *      — if validation passed, we POST the whole form to its action URL
 *      (preserves bundled_item_*, attribute_*, variation_id, nonces, etc.)
 *      then refresh the mini-cart.
 */

(function () {
  /* ---------------- Path 1: simple <a> .mn-atc-btn ---------------- */

  document.addEventListener('click', function (e) {
    var btn = e.target.closest('a.mn-atc-btn');
    if (!btn) return;
    if (!ready()) return;
    if (btn.classList.contains('loading')) { e.preventDefault(); return; }

    e.preventDefault();
    e.stopPropagation();
    if (typeof e.stopImmediatePropagation === 'function') e.stopImmediatePropagation();

    var productId = parseInt(btn.getAttribute('data-product_id') || '0', 10);
    if (!productId) return;

    var scope = btn.closest('.woocommerce-ajax-add-to-cart') || document;
    var qtyInput = scope.querySelector('input.qty, input[name="quantity"]');
    var qty = qtyInput ? Math.max(1, parseInt(qtyInput.value, 10) || 1) : 1;

    setLoading(btn, true);
    window.NaturaMiniCart.add({ product_id: productId, qty: qty })
      .then(function (data) {
        if (data && !data.is_empty) {
          flashAdded(btn);
          triggerAddedToCart(btn);
        }
      })
      .catch(function (err) {
        showAddError(err && err.message ? err.message : 'Nu s-a putut adăuga produsul.');
      })
      .finally(function () { setLoading(btn, false); });
  }, true); // capture — beats WC core's jQuery `.ajax_add_to_cart` handler

  /* ---------------- Path 2: form.cart submit ---------------- */

  document.addEventListener('submit', function (e) {
    var form = e.target;
    if (!form || !form.matches || !form.matches('form.cart')) return;
    if (e.defaultPrevented) return; // some validator (e.g. Bundles) bailed
    if (!ready()) return;

    // Scope to forms that actually contain our styled button. Avoids
    // hijacking third-party carts or admin forms that happen to use `.cart`.
    var btn = form.querySelector('.mn-atc-btn[type="submit"], .mn-atc-btn.single_add_to_cart_button');
    if (!btn) return;

    e.preventDefault();
    e.stopPropagation();

    submitFormAdd(form, btn);
  });

  /* ---------------- Helpers ---------------- */

  function ready() {
    return !!(window.NaturaMiniCart && typeof window.NaturaMiniCart.add === 'function');
  }

  function setLoading(btn, on) {
    if (!btn) return;
    btn.classList.toggle('loading', !!on);
    if (on) btn.setAttribute('aria-busy', 'true');
    else btn.removeAttribute('aria-busy');
  }

  function flashAdded(btn) {
    if (!btn) return;

    // Remember the original text so we can restore it.
    if (!btn.hasAttribute('data-label')) {
      btn.setAttribute('data-label', btn.textContent.trim());
    }

    btn.classList.add('added');
    btn.textContent = '\u2713 ' + (btn.getAttribute('data-added-label') || 'Adăugat');

    clearTimeout(btn._addedTimer);
    btn._addedTimer = setTimeout(function () {
      btn.classList.remove('added');
      btn.textContent = btn.getAttribute('data-label') || 'Adaugă în coș';
    }, 1400);
  }

  /* ---------------- Error toast ---------------- */

  var toastEl = null;
  var toastTimer = null;

  function showAddError(message) {
    if (!toastEl) {
      toastEl = document.createElement('div');
      toastEl.className = 'mn-atc-toast';
      toastEl.setAttribute('role', 'alert');
      toastEl.setAttribute('aria-live', 'assertive');
      document.body.appendChild(toastEl);
    }
    toastEl.textContent = message;
    toastEl.classList.add('is-visible');

    clearTimeout(toastTimer);
    toastTimer = setTimeout(function () {
      toastEl.classList.remove('is-visible');
    }, 4000);
  }

  // Dispatch WC's `added_to_cart` jQuery event so cart-modal.js can open
  // the confirmation popup. Our custom endpoint doesn't populate fragments,
  // so the modal falls back to button data-attrs for the product line.
  function triggerAddedToCart(btn) {
    if (!window.jQuery) return;
    var $btn = btn ? window.jQuery(btn) : window.jQuery();
    window.jQuery(document.body).trigger('added_to_cart', [null, '', $btn]);
  }

  function getCurrentCount() {
    var el = document.querySelector('[data-mini-cart-count]');
    return el ? parseInt(el.textContent, 10) || 0 : 0;
  }

  function submitFormAdd(form, btn) {
    var formData = new FormData(form);

    // `<button name="add-to-cart" value="ID">` only contributes when the
    // button is the submitter. FormData(form) misses it unless passed
    // explicitly — do that manually for legacy browsers and safety.
    if (btn && btn.name && btn.value && !formData.has(btn.name)) {
      formData.set(btn.name, btn.value);
    }

    var prevCount = getCurrentCount();
    setLoading(btn, true);

    fetch(form.action || window.location.href, {
      method: (form.method || 'POST').toUpperCase(),
      credentials: 'same-origin',
      redirect: 'follow',
      body: formData,
      headers: { 'X-Requested-With': 'XMLHttpRequest' },
    })
      .then(function () { return window.NaturaMiniCart.refresh(); })
      .then(function (data) {
        if (data && data.count > prevCount) {
          flashAdded(btn);
          triggerAddedToCart(btn);
        } else if (data) {
          // Request went through but cart count didn't change — likely a
          // validation block (stock, min/max, variation requirement, etc.).
          showAddError('Produsul nu a putut fi adăugat.');
        }
      })
      .catch(function (err) {
        showAddError(err && err.message ? err.message : 'A apărut o eroare. Încearcă din nou.');
      })
      .finally(function () { setLoading(btn, false); });
  }
})();
