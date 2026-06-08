/**
 * My Account · Dashboard — „Comandă din nou" add-to-cart.
 * Lazy-loaded din app.js când `.account-dash` e prezent.
 *
 * Folosește endpoint-ul WooCommerce `?wc-ajax=add_to_cart`, apoi declanșează
 * evenimentul jQuery `added_to_cart` (pe care îl ascultă mini-cart-ul temei,
 * vezi resources/js/mini-cart.js) și un toast prin window.NaturaToast.
 */

(function () {
  var root = document.querySelector('.account-dash');
  if (!root) return;

  var buttons = root.querySelectorAll('[data-add-to-cart]');
  if (!buttons.length) return;

  var PLUS = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><path d="M12 5v14M5 12h14"/></svg>';
  var CHECK = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><path d="M20 6 9 17l-5-5"/></svg>';

  function ajaxUrl() {
    if (window.wc_add_to_cart_params && window.wc_add_to_cart_params.wc_ajax_url) {
      return window.wc_add_to_cart_params.wc_ajax_url.replace('%%endpoint%%', 'add_to_cart');
    }
    return '/?wc-ajax=add_to_cart';
  }

  function toast(msg, variant) {
    if (window.NaturaToast && typeof window.NaturaToast.show === 'function') {
      window.NaturaToast.show(msg, { variant: variant || 'success' });
    }
  }

  buttons.forEach(function (btn) {
    btn.addEventListener('click', function () {
      if (btn.disabled) return;
      var id = btn.getAttribute('data-add-to-cart');
      var name = btn.getAttribute('data-product-name') || 'Produs';
      if (!id) return;

      btn.disabled = true;

      var body = new URLSearchParams();
      body.append('product_id', id);
      body.append('quantity', '1');

      fetch(ajaxUrl(), {
        method: 'POST',
        credentials: 'same-origin',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: body.toString(),
      })
        .then(function (r) { return r.json(); })
        .then(function (res) {
          if (res && res.error && res.product_url) {
            // produs cu opțiuni — trimitem la pagina lui
            window.location = res.product_url;
            return;
          }

          // Vizual: bifă + revenire
          btn.classList.add('added');
          btn.innerHTML = CHECK;
          setTimeout(function () {
            btn.classList.remove('added');
            btn.innerHTML = PLUS;
            btn.disabled = false;
          }, 1400);

          // Refresh mini-cart prin evenimentul WooCommerce
          if (window.jQuery && res && res.fragments) {
            window.jQuery(document.body).trigger('added_to_cart', [res.fragments, res.cart_hash, window.jQuery(btn)]);
          }

          toast(name + ' ' + 'adăugat în coș', 'success');
        })
        .catch(function () {
          btn.disabled = false;
          toast('Nu am putut adăuga produsul. Încearcă din nou.', 'error');
        });
    });
  });
})();
