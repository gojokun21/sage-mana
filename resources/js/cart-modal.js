/**
 * Add-to-cart confirmation modal.
 *
 * Lazy-loaded from app.js when `#ml-cart-modal` is on the page.
 *
 * Listens for WC's jQuery `added_to_cart` event on `document.body`, pulls
 * last-product + free-shipping data from the button OR from the
 * `ml_cart_data` fragment (set server-side in app/cart-modal.php), and
 * opens the modal. Mini-cart drawer no longer auto-opens on add.
 */

(function () {
  var modal = document.getElementById('ml-cart-modal');
  if (!modal) return;

  var titleEl = modal.querySelector('#ml-modal-title');
  var imageEl = modal.querySelector('#ml-modal-image');
  var packagingEl = modal.querySelector('#ml-modal-packaging');
  var shippingEl = modal.querySelector('#ml-modal-shipping');
  var closeSelectors = '.modal-close, .popup_close';

  function fmt(n) {
    // Match the legacy "123,45 lei" display — 2 decimals, comma separator.
    return Number(n).toFixed(2).replace('.', ',');
  }

  function open() {
    modal.classList.add('show');
    modal.setAttribute('aria-hidden', 'false');
    document.body.classList.add('cart-modal-open');
  }

  function close() {
    modal.classList.remove('show');
    modal.setAttribute('aria-hidden', 'true');
    document.body.classList.remove('cart-modal-open');
  }

  // Close: buttons inside modal + click on overlay (target === modal itself).
  modal.addEventListener('click', function (e) {
    if (e.target === modal || e.target.closest(closeSelectors)) {
      close();
    }
  });

  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' && modal.classList.contains('show')) close();
  });

  function populate(product, shippingData) {
    if (product) {
      if (titleEl) {
        titleEl.textContent = product.name || '';
        titleEl.setAttribute('href', product.url || '#');
      }
      if (imageEl) {
        imageEl.setAttribute('src', product.image || '');
        imageEl.setAttribute('alt', product.name || '');
      }
      if (packagingEl) {
        // Plain text only — never render HTML in the short description.
        packagingEl.textContent = product.packaging || '';
      }
    }

    if (shippingEl && shippingData) {
      if (shippingData.missing > 0) {
        shippingEl.innerHTML =
          'Doriți livrare gratuită? Vă lipsesc: <strong>' +
          fmt(shippingData.missing) +
          ' lei</strong>';
      } else {
        shippingEl.innerHTML = '<strong>Felicitări!</strong> Aveți livrare gratuită!';
      }
    }
  }

  function bindWc() {
    if (!window.jQuery) return false;

    window.jQuery(document.body).on('added_to_cart', function (e, fragments, cart_hash, $button) {
      var data = null;
      if (fragments && fragments.ml_cart_data) {
        try { data = JSON.parse(fragments.ml_cart_data); } catch (_) {}
      }

      // Prefer button data-attrs (normal AJAX add-to-cart), fall back to
      // server-side last-product snapshot for bundles / form submits.
      var product = null;
      if ($button && $button.data && $button.data('product_name')) {
        product = {
          name: $button.data('product_name'),
          url: $button.data('product_url'),
          image: $button.data('product_img'),
          packaging: $button.data('product_packaging'),
        };
      } else if (data && data.last_product) {
        product = data.last_product;
      }

      populate(product, data);
      open();
    });

    return true;
  }

  if (!bindWc()) {
    var tries = 0;
    var poll = setInterval(function () {
      tries++;
      if (bindWc() || tries > 40) clearInterval(poll);
    }, 100);
  }

  // Public API — useful for custom add-to-cart flows (e.g. cart-modal triggered
  // manually after a non-WC-AJAX add).
  window.NaturaCartModal = { open: open, close: close, populate: populate };
})();
