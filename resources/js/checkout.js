/**
 * Checkout behavior — vanilla, lazy-loaded when .woocommerce-checkout exists.
 *
 * Handles:
 *   - Guest/Login tab switcher
 *   - AJAX login (natura_checkout_login endpoint)
 *   - Shipping "ship to different address" accordion
 *   - CUI toggle — Persoană Fizică vs Persoană Juridică (FGO fields)
 *   - Double-submit prevention via WC's jQuery checkout events
 */

(function () {
  var cfg = window.natura_checkout;

  /* ---------------- Tabs (guest / login) ---------------- */

  document.querySelectorAll('[data-checkout-tab]').forEach(function (btn) {
    btn.addEventListener('click', function (e) {
      e.preventDefault();
      var target = btn.getAttribute('data-checkout-tab');

      document.querySelectorAll('[data-checkout-tab]').forEach(function (b) {
        var active = b === btn;
        b.classList.toggle('active', active);
        b.setAttribute('aria-selected', active ? 'true' : 'false');
      });
      document.querySelectorAll('[data-checkout-panel]').forEach(function (p) {
        var active = p.getAttribute('data-checkout-panel') === target;
        p.classList.toggle('active', active);
        p.setAttribute('aria-hidden', active ? 'false' : 'true');
      });
    });
  });

  /* ---------------- AJAX login ---------------- */

  var loginBtn = document.getElementById('checkout_login_btn');
  if (loginBtn && cfg && cfg.ajax_url) {
    loginBtn.addEventListener('click', function (e) {
      e.preventDefault();

      var usernameEl = document.getElementById('checkout_username');
      var passwordEl = document.getElementById('checkout_password');
      var rememberEl = document.getElementById('checkout_rememberme');
      var msgEl = document.querySelector('.checkout-login-message');

      var username = usernameEl ? usernameEl.value.trim() : '';
      var password = passwordEl ? passwordEl.value : '';
      var remember = rememberEl ? rememberEl.checked : false;

      if (!username || !password) {
        if (msgEl) msgEl.innerHTML = '<div class="woocommerce-error">' + cfg.i18n.missing_fields + '</div>';
        return;
      }

      loginBtn.disabled = true;
      var originalText = loginBtn.textContent;
      loginBtn.textContent = cfg.i18n.working;
      if (msgEl) msgEl.innerHTML = '';

      var form = new FormData();
      form.append('action', 'natura_checkout_login');
      form.append('nonce', cfg.nonce);
      form.append('username', username);
      form.append('password', password);
      form.append('remember', remember ? 'true' : 'false');

      fetch(cfg.ajax_url, { method: 'POST', credentials: 'same-origin', body: form })
        .then(function (r) { return r.json(); })
        .then(function (res) {
          if (res && res.success) {
            if (msgEl) msgEl.innerHTML = '<div class="woocommerce-message">' + res.data.message + '</div>';
            setTimeout(function () { location.reload(); }, 900);
          } else {
            var m = (res && res.data && res.data.message) || cfg.i18n.invalid;
            if (msgEl) msgEl.innerHTML = '<div class="woocommerce-error">' + m + '</div>';
            loginBtn.disabled = false;
            loginBtn.textContent = originalText;
          }
        })
        .catch(function () {
          if (msgEl) msgEl.innerHTML = '<div class="woocommerce-error">' + cfg.i18n.error + '</div>';
          loginBtn.disabled = false;
          loginBtn.textContent = originalText;
        });
    });
  }

  /* ---------------- Shipping accordion ---------------- */

  document.querySelectorAll('[data-shipping-toggle]').forEach(function (header) {
    header.addEventListener('click', function () {
      var accordion = header.closest('[data-shipping-accordion]');
      if (!accordion) return;
      var panel = accordion.querySelector('[data-shipping-panel]');
      var icon = header.querySelector('.shipping-accordion__icon');
      var checkbox = accordion.querySelector('#ship-to-different-address-checkbox');

      if (!panel) return;

      var isOpen = panel.classList.toggle('open');
      header.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
      if (icon) icon.classList.toggle('active', isOpen);
      if (checkbox) checkbox.value = isOpen ? '1' : '0';

      if (window.jQuery) window.jQuery(document.body).trigger('update_checkout');
    });
  });

  /* ---------------- FGO fields (Tip Facturare / CUI / CNP) ----------------
   * FGO keeps a single `billing_cui` input that it relabels dynamically
   * ("Cod Unic" for PJ / "CNP" for PF). On this store we only collect a
   * CUI from business customers, so we HIDE the field for PF (tip=2)
   * and show it for PJ (tip=1).
   *
   * Visibility is driven by a body class (`natura-tip-pj` / `natura-tip-pf`)
   * set server-side — see app/checkout.php + checkout.css. That way the
   * field is hidden by CSS as soon as <body> is parsed, with no FOUC waiting
   * for this lazy-loaded module to run. Here we only keep the class in sync
   * with the current select value and manage the `required` attribute. */

  var CUI_LABEL_PJ = 'CUI (Cod Unic de Înregistrare)';

  function syncCuiVisibility() {
    var tipSelect = document.querySelector('#wc_order_billing_tip_facturare_fgo_client, select[name="billing_tip_facturare"]');
    var cuiInput = document.querySelector('#wc_order_billing_cui_fgo_client, input[name="billing_cui"]');
    var cuiRow = cuiInput ? cuiInput.closest('.form-row') : null;
    var cuiLabel = cuiRow ? cuiRow.querySelector('label') : null;

    if (!tipSelect || !cuiInput || !cuiRow) return;

    var isPJ = tipSelect.value === '1';
    document.body.classList.toggle('natura-tip-pj', isPJ);
    document.body.classList.toggle('natura-tip-pf', !isPJ);

    if (isPJ) {
      cuiInput.setAttribute('required', 'required');
      cuiInput.setAttribute('aria-required', 'true');
      cuiRow.classList.add('validate-required');
      cuiInput.setAttribute('placeholder', 'Introdu CUI-ul firmei');
      // Overwrite FGO's "Cod Unic" label with the full version requested.
      if (cuiLabel) {
        cuiLabel.innerHTML = CUI_LABEL_PJ + ' <abbr class="required" title="obligatoriu">*</abbr>';
      }
    } else {
      cuiInput.removeAttribute('required');
      cuiInput.removeAttribute('aria-required');
      cuiRow.classList.remove('validate-required', 'woocommerce-invalid', 'woocommerce-invalid-required-field');
      cuiInput.value = '';
    }
  }

  document.addEventListener('change', function (e) {
    if (e.target.matches && e.target.matches('#wc_order_billing_tip_facturare_fgo_client, select[name="billing_tip_facturare"]')) {
      syncCuiVisibility();
    }
  });

  syncCuiVisibility();

  if (window.jQuery) {
    window.jQuery(document.body).on('updated_checkout', syncCuiVisibility);
  }

  /* ---------------- Double-submit prevention ---------------- */
  // jQuery is loaded by WC but may arrive AFTER this lazy-imported module.
  // Same retry pattern as mini-cart: poll for a few seconds.

  function bindDoubleSubmitGuard() {
    if (!window.jQuery) return false;

    var $ = window.jQuery;

    $('form.checkout').on('checkout_place_order', function () {
      $('#place_order').prop('disabled', true).addClass('processing');
    });

    $(document.body).on('checkout_error', function () {
      $('#place_order').prop('disabled', false).removeClass('processing');
    });

    return true;
  }

  if (!bindDoubleSubmitGuard()) {
    var tries = 0;
    var poll = setInterval(function () {
      tries++;
      if (bindDoubleSubmitGuard() || tries > 40) clearInterval(poll); // ~4s
    }, 100);
  }
})();
