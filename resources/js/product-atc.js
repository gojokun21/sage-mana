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
  // Tracks in-flight adds by product_id so the same product can't be added
  // twice by tapping two different buttons (same product appears in multiple
  // sliders on the home page — "Populare" + "Promoție" — each with its own
  // `.loading` class, so per-button guards aren't enough).
  var pendingProductIds = new Set();

  /* ---------------- Path 1: simple <a> .mn-atc-btn ---------------- */

  document.addEventListener('click', function (e) {
    var btn = e.target.closest('a.mn-atc-btn');
    if (!btn) return;
    // Variable and grouped strictly require per-variation/per-child input
    // from the product page — let the browser follow the link.
    if (btn.classList.contains('product_type_variable')) return;
    if (btn.classList.contains('product_type_grouped')) return;

    // Claim the event unconditionally — BEFORE any `ready()` check. The
    // button ships with both `ajax_add_to_cart` (WC jQuery handler target)
    // and a real `href` that would add-via-URL on navigation. If our module
    // hasn't finished loading when the user taps, bailing early lets either
    // of those fire, and the user then taps again — producing two adds.
    // Always stop propagation + default so we're the only add path.
    e.preventDefault();
    e.stopPropagation();
    if (typeof e.stopImmediatePropagation === 'function') e.stopImmediatePropagation();

    if (btn.classList.contains('loading')) return;
    if (!ready()) return;

    var productId = parseInt(btn.getAttribute('data-product_id') || '0', 10);
    if (!productId) return;
    if (pendingProductIds.has(productId)) return;

    // Qty resolution:
    //   - Single product page: button sits inside `.woocommerce-ajax-add-to-cart`
    //     alongside the qty stepper input — read the user's selected quantity.
    //   - Archive / loop: no such wrapper; use the button's `data-quantity`
    //     attribute (emitted at `1` in content-product.blade.php).
    // We intentionally do NOT fall back to `document.querySelector('input.qty')`
    // because the mini-cart drawer also renders qty inputs — if a product is
    // in the drawer at qty=3, any archive add-to-cart would inherit that 3.
    var scope = btn.closest('.woocommerce-ajax-add-to-cart');
    var qtyInput = scope ? scope.querySelector('input.qty, input[name="quantity"]') : null;
    var qty = qtyInput
      ? Math.max(1, parseInt(qtyInput.value, 10) || 1)
      : Math.max(1, parseInt(btn.getAttribute('data-quantity') || '1', 10) || 1);

    pendingProductIds.add(productId);
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
      .finally(function () {
        setLoading(btn, false);
        pendingProductIds.delete(productId);
      });
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

  function showAddError(message) {
    if (window.NaturaToast && typeof window.NaturaToast.show === 'function') {
      window.NaturaToast.show(message, { variant: 'error', duration: 4000 });
    }
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
          showAddError('Ai atins cantitatea maximă disponibilă.');
        }
      })
      .catch(function (err) {
        showAddError(err && err.message ? err.message : 'A apărut o eroare. Încearcă din nou.');
      })
      .finally(function () { setLoading(btn, false); });
  }
})();
