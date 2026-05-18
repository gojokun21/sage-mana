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

  /* ---------------- Payment method change → refresh totals ----------------
   * WC core doesn't auto-trigger `update_checkout` when the payment radio
   * changes, so fees keyed off `chosen_payment_method` (e.g. Taxă ramburs
   * for COD) stay stale until something else refreshes the review. Bridging
   * through jQuery is required because WC's checkout handler listens on the
   * jQuery event bus. Delegated because the .wc_payment_methods list is
   * re-rendered on every `updated_checkout`. */

  document.addEventListener('change', function (e) {
    var t = e.target;
    if (!t || t.name !== 'payment_method') return;
    if (window.jQuery) window.jQuery(document.body).trigger('update_checkout');
  });

  /* ---------------- "Fără email" checkbox ----------------
   * When checked: clear billing_email value, drop its `required` attribute,
   * and trigger update_checkout so WC re-renders the payment block with the
   * server-side filter applied (only Ramburs remains). When unchecked: put
   * the required attribute back so the browser blocks submit on empty email.
   *
   * The state is also persisted server-side via the AJAX `post_data` round
   * trip — see woocommerce_available_payment_gateways in app/checkout.php. */

  function syncNoEmailState() {
    var box = document.getElementById('billing_no_email');
    var emailInput = document.getElementById('billing_email');
    var emailRow = document.getElementById('billing_email_field');
    if (!box || !emailInput || !emailRow) return;

    var createAccountBox = document.getElementById('createaccount');

    if (box.checked) {
      emailInput.value = '';
      emailInput.removeAttribute('required');
      emailInput.removeAttribute('aria-required');
      emailRow.classList.add('natura-email-skipped');
      emailRow.classList.remove('validate-required', 'woocommerce-invalid', 'woocommerce-invalid-required-field');
      document.body.classList.add('natura-no-email-active');
      // Untick "Creezi un cont?" — registration needs an email, doesn't make
      // sense in no-email mode (the server enforces this too, but unchecking
      // here keeps the form state honest if the user toggles back).
      if (createAccountBox && createAccountBox.checked) {
        createAccountBox.checked = false;
        createAccountBox.dispatchEvent(new Event('change', { bubbles: true }));
      }
    } else {
      emailInput.setAttribute('required', 'required');
      emailInput.setAttribute('aria-required', 'true');
      emailRow.classList.remove('natura-email-skipped');
      emailRow.classList.add('validate-required');
      document.body.classList.remove('natura-no-email-active');
    }
  }

  document.addEventListener('change', function (e) {
    if (e.target && e.target.id === 'billing_no_email') {
      syncNoEmailState();
      if (window.jQuery) window.jQuery(document.body).trigger('update_checkout');
    }
  });

  // Initial sync covers the reload-after-validation-error case where the
  // checkbox comes back checked from the server.
  syncNoEmailState();

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

  /* ---------------- Sector (București) ----------------
   * Visible only when billing_state === 'B'. CSS hides .natura-sector-row
   * unless body has `.natura-state-b` — set server-side initially, kept in
   * sync here. We also flip aria-required and clear the value when the
   * customer leaves Bucharest, so a stale sector never gets submitted. */

  function syncSectorVisibility() {
    var stateField = document.querySelector('select[name="billing_state"], input[name="billing_state"]');
    var sectorField = document.querySelector('select[name="billing_sector"]');
    var sectorRow = sectorField ? sectorField.closest('.form-row') : null;
    var sectorLabel = sectorRow ? sectorRow.querySelector('label') : null;

    if (!stateField || !sectorField || !sectorRow) return;

    var isBucuresti = stateField.value === 'B';
    document.body.classList.toggle('natura-state-b', isBucuresti);

    if (isBucuresti) {
      sectorField.setAttribute('required', 'required');
      sectorField.setAttribute('aria-required', 'true');
      sectorRow.classList.add('validate-required');
      if (sectorLabel && !sectorLabel.querySelector('.required')) {
        sectorLabel.insertAdjacentHTML('beforeend', ' <abbr class="required" title="obligatoriu">*</abbr>');
      }
    } else {
      sectorField.removeAttribute('required');
      sectorField.removeAttribute('aria-required');
      sectorRow.classList.remove('validate-required', 'woocommerce-invalid', 'woocommerce-invalid-required-field');
      sectorField.value = '';
      var existingAbbr = sectorLabel ? sectorLabel.querySelector('.required') : null;
      if (existingAbbr) existingAbbr.remove();
    }
  }

  // Initial sync — body class is set server-side when billing_state is
  // already 'B' (logged-in customer with a saved Bucharest address); for
  // guests this is a no-op since the state value is empty.
  syncSectorVisibility();

  // SelectWoo (WC's Select2 fork) updates billing_state via jQuery's
  // `.trigger('change')`, which does NOT fire native addEventListener
  // listeners. Bind via jQuery so we catch state changes regardless of
  // whether they originate from SelectWoo or from a direct DOM input.
  // `country_to_state_changed` covers the case where WC rebuilds the state
  // field after a country switch; `updated_checkout` covers the AJAX update
  // round-trip and is the safety net.
  function bindSectorListeners() {
    if (!window.jQuery) return false;

    var $ = window.jQuery;
    $(document).on('change', 'select[name="billing_state"], input[name="billing_state"]', syncSectorVisibility);
    $(document.body).on('updated_checkout country_to_state_changed', syncSectorVisibility);

    return true;
  }

  if (!bindSectorListeners()) {
    var sectorTries = 0;
    var sectorPoll = setInterval(function () {
      sectorTries++;
      if (bindSectorListeners() || sectorTries > 40) clearInterval(sectorPoll); // ~4s
    }, 100);
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

  /* ---------------- Coupon (apply on checkout via WC wc-ajax) ---------------- */

  (function bindCheckoutCoupon() {
    var wrap = document.querySelector('[data-checkout-coupon]');
    if (!wrap) return;

    var toggleBtn = wrap.querySelector('.checkout-coupon__toggle');
    var panel = wrap.querySelector('.checkout-coupon__panel');
    var input = wrap.querySelector('#checkout_coupon_code');
    var applyBtn = wrap.querySelector('.checkout-coupon__apply');
    var msgEl = wrap.querySelector('.checkout-coupon__message');

    if (toggleBtn && panel) {
      toggleBtn.addEventListener('click', function () {
        var open = toggleBtn.getAttribute('aria-expanded') === 'true';
        toggleBtn.setAttribute('aria-expanded', open ? 'false' : 'true');
        panel.hidden = open;
        wrap.classList.toggle('is-open', !open);
        if (!open && input) {
          setTimeout(function () { input.focus(); }, 60);
        }
      });
    }

    if (!applyBtn || !input) return;

    function setMessage(html, tone) {
      if (!msgEl) return;
      msgEl.className = 'checkout-coupon__message' + (tone ? ' is-' + tone : '');
      msgEl.innerHTML = html || '';
    }

    function apply() {
      var code = (input.value || '').trim();
      if (!code) {
        setMessage('Introdu un cod valid.', 'error');
        input.focus();
        return;
      }

      var params = window.wc_checkout_params;
      if (!params || !params.wc_ajax_url) {
        setMessage('Eroare de configurare. Reîncarcă pagina.', 'error');
        return;
      }

      var url = params.wc_ajax_url.toString().replace('%%endpoint%%', 'apply_coupon');

      applyBtn.disabled = true;
      applyBtn.classList.add('is-loading');
      input.disabled = true;
      setMessage('');

      var body = new URLSearchParams();
      body.append('security', params.apply_coupon_nonce || '');
      body.append('coupon_code', code);

      fetch(url, {
        method: 'POST',
        credentials: 'same-origin',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8' },
        body: body.toString(),
      })
        .then(function (r) { return r.text(); })
        .then(function (html) {
          // WC returns a notice fragment (success/error). Display as-is.
          setMessage(html, /woocommerce-error/.test(html) ? 'error' : 'success');
          input.value = '';
          input.disabled = false;
          applyBtn.disabled = false;
          applyBtn.classList.remove('is-loading');
          if (window.jQuery) {
            window.jQuery(document.body).trigger('update_checkout');
          }
        })
        .catch(function () {
          setMessage('Nu s-a putut aplica codul. Încearcă din nou.', 'error');
          input.disabled = false;
          applyBtn.disabled = false;
          applyBtn.classList.remove('is-loading');
        });
    }

    applyBtn.addEventListener('click', apply);
    input.addEventListener('keydown', function (e) {
      if (e.key === 'Enter') {
        e.preventDefault();
        apply();
      }
    });
  })();

  /* ---------------- Address cascade (judet → localitate → stradă → cod poștal)
   * Pure client-side: static JSON files served from public/data/postcodes/.
   * Bound to window.natura_address_data (set by app/checkout.php). Datasetul
   * este sharded — vezi app/Console/Commands/AddressImport.php. */

  (function bindAddressCascade() {
    var baseUrl = window.natura_address_data;
    if (!baseUrl) return;

    var stateEl = document.querySelector('select[name="billing_state"], input[name="billing_state"]');
    var cityEl = document.getElementById('billing_city');
    // Split UI: visible inputs the customer types into. The hidden combined
    // value lives in #billing_address_1 (WC's real field). `addrEl` below
    // remains the autocomplete target (= street name), so the cascade and
    // suggestions box continue to anchor on the same DOM node they did
    // before the split.
    var streetNameEl = document.getElementById('natura_street_name');
    var streetNumberEl = document.getElementById('natura_street_number');
    var combinedAddrEl = document.getElementById('billing_address_1');
    var addrEl = streetNameEl || combinedAddrEl;
    var zipEl = document.getElementById('billing_postcode');
    var dl = document.getElementById('natura-localitati');

    if (!cityEl || !addrEl || !zipEl) return;

    // Sync visible inputs → hidden combined billing_address_1. Trim so a
    // lone trailing space (number missing) doesn't satisfy WC's `required`
    // check server-side; HTML5 `required` on the visible inputs blocks
    // submit first in the normal case.
    function syncCombinedAddress() {
      if (!combinedAddrEl) return;
      var street = streetNameEl ? (streetNameEl.value || '').trim() : '';
      var number = streetNumberEl ? (streetNumberEl.value || '').trim() : '';
      var combined = (street + ' ' + number).trim();
      if (combinedAddrEl.value !== combined) {
        combinedAddrEl.value = combined;
        // Some WC integrations (and the i18n re-sort) listen for input/change
        // on billing_address_1. Forward the event so they see the new value.
        combinedAddrEl.dispatchEvent(new Event('input', { bubbles: true }));
        combinedAddrEl.dispatchEvent(new Event('change', { bubbles: true }));
      }
    }

    // Reverse: parse a pre-filled billing_address_1 (logged-in customer with
    // a saved address, or a checkout reload after validation error) back
    // into the visible inputs. Heuristic: last whitespace-separated token
    // that contains a digit is the number; everything before it is the
    // street. Falls back to "all street, no number" when no digit token is
    // present.
    function splitCombinedIntoVisible() {
      if (!combinedAddrEl || !streetNameEl || !streetNumberEl) return;
      var raw = (combinedAddrEl.value || '').trim();
      if (!raw) return;
      // If the visible inputs already have user-typed values, don't clobber.
      if (streetNameEl.value || streetNumberEl.value) return;

      var tokens = raw.split(/\s+/);
      var numberIdx = -1;
      for (var i = tokens.length - 1; i >= 0; i--) {
        if (/\d/.test(tokens[i])) { numberIdx = i; break; }
      }

      if (numberIdx === -1) {
        streetNameEl.value = raw;
        streetNumberEl.value = '';
      } else {
        streetNumberEl.value = tokens.slice(numberIdx).join(' ');
        streetNameEl.value = tokens.slice(0, numberIdx).join(' ');
      }
    }

    splitCombinedIntoVisible();
    if (streetNameEl) streetNameEl.addEventListener('input', syncCombinedAddress);
    if (streetNumberEl) streetNumberEl.addEventListener('input', syncCombinedAddress);
    // Initial sync in case parsing populated values (so combined is canonicalised).
    syncCombinedAddress();

    var manifest = null;
    var currentState = '';
    var currentCity = '';
    var streetIndex = []; // [{ display, lower, code }] sorted by lower
    var suggestionsBox = null;
    var visibleSuggestions = [];
    var activeIndex = -1;

    function fold(s) {
      return (s || '')
        .toString()
        .toLowerCase()
        .normalize('NFD')
        .replace(/[̀-ͯ]/g, '')
        .replace(/ş/g, 's')
        .replace(/ţ/g, 't');
    }

    function slugify(s) {
      return fold(s).replace(/[^a-z0-9]+/g, '-').replace(/^-+|-+$/g, '');
    }

    // Romanian street-type words that are noise for autocomplete: "Str. Lipscani",
    // "Strada Lipscani", "Lipscani strada" should all match the same entry.
    // Tokens are compared after fold(), so they're already diacritic-free.
    var STREET_TYPE_TOKENS = {
      'strada': 1, 'str': 1, 'stradela': 1, 'bulevardul': 1, 'bulevard': 1,
      'bd': 1, 'bdul': 1, 'b-dul': 1, 'calea': 1, 'piata': 1, 'splaiul': 1,
      'soseaua': 1, 'sos': 1, 'aleea': 1, 'intrarea': 1, 'fundatura': 1,
      'fund': 1, 'drumul': 1, 'drum': 1, 'cartier': 1, 'cart': 1, 'nr': 1
    };

    function tokenize(s) {
      var folded = fold(s);
      // Strip punctuation but keep hyphens so "b-dul" stays one token.
      var cleaned = folded.replace(/[.,;:!?()\/\\]+/g, ' ');
      var tokens = cleaned.split(/[\s ]+/).filter(Boolean);
      return tokens.filter(function (t) { return !STREET_TYPE_TOKENS[t]; });
    }

    function debounce(fn, ms) {
      var t;
      return function () {
        var args = arguments, ctx = this;
        clearTimeout(t);
        t = setTimeout(function () { fn.apply(ctx, args); }, ms);
      };
    }

    function fetchJson(url) {
      return fetch(url, { credentials: 'omit', cache: 'force-cache' })
        .then(function (r) {
          if (!r.ok) throw new Error('HTTP ' + r.status);
          return r.json();
        });
    }

    function loadManifest() {
      if (manifest) return Promise.resolve(manifest);
      return fetchJson(baseUrl + 'index.json').then(function (m) { manifest = m; return m; });
    }

    function loadLocalities(stateCode) {
      return fetchJson(baseUrl + 'localities/' + encodeURIComponent(stateCode) + '.json');
    }

    function loadStreetsFor(stateCode, locality) {
      return loadManifest().then(function (m) {
        var entry = m && m[stateCode];
        if (!entry || !entry.localities) return null;

        var foldedQ = fold(locality);
        var matchedKey = null;
        Object.keys(entry.localities).some(function (k) {
          if (fold(k) === foldedQ) { matchedKey = k; return true; }
          return false;
        });
        if (!matchedKey) return null;

        var shards = entry.localities[matchedKey] || [];

        // Locality known but has no street data in the dataset (e.g. Ilfov
        // communes). Resolve to empty so the cascade shows the "no streets"
        // hint instead of issuing 404 fetches.
        if (shards.length === 0) return {};

        var slug = slugify(matchedKey);
        var urls = shards.map(function (s) {
          var name = s ? slug + '-' + s : slug;
          return baseUrl + 'streets/' + encodeURIComponent(stateCode) + '/' + name + '.json';
        });

        return Promise.all(urls.map(function (u) {
          return fetchJson(u).catch(function () { return {}; });
        })).then(function (parts) {
          var combined = {};
          parts.forEach(function (p) { Object.assign(combined, p || {}); });
          return combined;
        });
      });
    }

    function populateLocalities(list) {
      if (!dl) return;
      dl.innerHTML = '';
      var frag = document.createDocumentFragment();
      (list || []).forEach(function (loc) {
        var opt = document.createElement('option');
        opt.value = loc;
        frag.appendChild(opt);
      });
      dl.appendChild(frag);
    }

    function resetCity() { cityEl.value = ''; if (dl) dl.innerHTML = ''; }
    function resetStreet() { addrEl.value = ''; closeSuggestions(); streetIndex = []; }
    function resetZip() { zipEl.value = ''; }

    function ensureSuggestionsBox() {
      if (suggestionsBox) return suggestionsBox;
      suggestionsBox = document.createElement('ul');
      suggestionsBox.className = 'natura-street-suggestions';
      suggestionsBox.setAttribute('role', 'listbox');
      suggestionsBox.hidden = true;
      var row = addrEl.closest('.form-row') || addrEl.parentElement;
      if (row) {
        var pos = window.getComputedStyle(row).position;
        if (pos === 'static') row.style.position = 'relative';
        row.appendChild(suggestionsBox);
      }
      return suggestionsBox;
    }

    function renderSuggestions(items, emptyHint) {
      var box = ensureSuggestionsBox();
      box.innerHTML = '';
      visibleSuggestions = items;
      activeIndex = -1;

      if (!items.length) {
        if (emptyHint) {
          var hint = document.createElement('li');
          hint.className = 'natura-street-suggestion is-empty';
          hint.setAttribute('aria-disabled', 'true');
          hint.textContent = emptyHint;
          box.appendChild(hint);
          box.hidden = false;
        } else {
          box.hidden = true;
        }
        return;
      }

      var frag = document.createDocumentFragment();
      items.forEach(function (item, idx) {
        var li = document.createElement('li');
        li.className = 'natura-street-suggestion';
        li.setAttribute('role', 'option');
        li.setAttribute('data-idx', String(idx));
        li.textContent = item.display;
        frag.appendChild(li);
      });
      box.appendChild(frag);
      box.hidden = false;
    }

    function closeSuggestions() {
      if (suggestionsBox) { suggestionsBox.hidden = true; suggestionsBox.innerHTML = ''; }
      visibleSuggestions = []; activeIndex = -1;
    }

    function pickSuggestion(item) {
      if (!item) return;
      addrEl.value = item.display;
      // Mirror the picked street name into the hidden combined billing_address_1.
      // The number input keeps whatever the customer already typed (if any).
      syncCombinedAddress();
      if (item.code) {
        zipEl.value = item.code;
        zipEl.dispatchEvent(new Event('input', { bubbles: true }));
        zipEl.dispatchEvent(new Event('change', { bubbles: true }));
        // Brief flash so the customer notices the auto-fill (CSS handles the fade).
        zipEl.classList.add('natura-zip-just-filled');
        setTimeout(function () { zipEl.classList.remove('natura-zip-just-filled'); }, 700);
      }
      closeSuggestions();
      // Send the customer straight to the number input — the most natural
      // next action after picking a street is to type the house number.
      if (streetNumberEl && !streetNumberEl.value) streetNumberEl.focus();
    }

    function highlightSuggestion(idx) {
      if (!suggestionsBox) return;
      Array.prototype.forEach.call(suggestionsBox.children, function (el, i) {
        el.classList.toggle('is-active', i === idx);
      });
      if (idx >= 0 && suggestionsBox.children[idx]) {
        suggestionsBox.children[idx].scrollIntoView({ block: 'nearest' });
      }
    }

    function searchStreets(q) {
      if (!q || q.length < 2) { closeSuggestions(); return; }

      // No street data loaded for the current judet+localitate. Tell the
      // customer instead of failing silently — they typed something so they
      // expect feedback.
      if (!streetIndex.length) {
        var hint;
        if (!currentState) {
          hint = 'Selectează mai întâi județul.';
        } else if (!currentCity) {
          hint = 'Selectează mai întâi localitatea.';
        } else {
          hint = 'Nu avem străzi pentru ' + currentCity + ' în baza de date. Poți completa manual codul poștal.';
        }
        renderSuggestions([], hint);
        return;
      }

      var queryTokens = tokenize(q);
      var qf = fold(q.trim());

      // Fallback when query is *only* a street-type word (e.g. "calea") — show
      // all entries containing that word as plain substring search.
      if (queryTokens.length === 0) {
        var fallback = [];
        for (var j = 0; j < streetIndex.length && fallback.length < 20; j++) {
          if (streetIndex[j].lower.indexOf(qf) > -1) fallback.push(streetIndex[j]);
        }
        renderSuggestions(fallback);
        return;
      }

      // Score each entry: every query token must appear (prefix or substring)
      // in some entry token. Prefix matches at token start score higher.
      var scored = [];
      for (var i = 0; i < streetIndex.length; i++) {
        var entry = streetIndex[i];
        var score = 0;
        var allMatched = true;

        for (var k = 0; k < queryTokens.length; k++) {
          var qt = queryTokens[k];
          var matched = 0;

          for (var m = 0; m < entry.tokens.length; m++) {
            var et = entry.tokens[m];
            if (et === qt) { matched = 3; break; }
            if (et.indexOf(qt) === 0) { matched = Math.max(matched, 2); }
            else if (et.indexOf(qt) > -1) { matched = Math.max(matched, 1); }
          }

          if (!matched) { allMatched = false; break; }
          score += matched;
        }

        if (allMatched) scored.push({ entry: entry, score: score });
      }

      scored.sort(function (a, b) {
        if (b.score !== a.score) return b.score - a.score;
        return a.entry.lower < b.entry.lower ? -1 : a.entry.lower > b.entry.lower ? 1 : 0;
      });

      var items = scored.slice(0, 20).map(function (s) { return s.entry; });
      renderSuggestions(items, items.length === 0 ? 'Nicio stradă găsită — verifică ortografia sau completează manual codul poștal.' : null);
    }

    function onStateChange() {
      var current = document.querySelector('select[name="billing_state"], input[name="billing_state"]');
      if (current && current !== stateEl) stateEl = current;
      var code = stateEl ? (stateEl.value || '').trim() : '';
      if (code === currentState) return;
      currentState = code;
      currentCity = '';
      resetCity();
      resetStreet();
      resetZip();
      if (!code) return;
      loadLocalities(code).then(populateLocalities).catch(function () {});
    }

    function onCityChange() {
      var v = (cityEl.value || '').trim();
      if (!v || v === currentCity) return;
      if (!currentState) return;
      currentCity = v;
      resetStreet();
      resetZip();
      loadStreetsFor(currentState, v).then(function (map) {
        if (!map) return;
        var arr = [];
        Object.keys(map).forEach(function (display) {
          arr.push({
            display: display,
            lower: fold(display),
            code: map[display],
            tokens: tokenize(display),
          });
        });
        arr.sort(function (a, b) { return a.lower < b.lower ? -1 : a.lower > b.lower ? 1 : 0; });
        streetIndex = arr;
      }).catch(function () {});
    }

    var onAddrInput = debounce(function () {
      searchStreets((addrEl.value || '').trim());
    }, 200);

    function onAddrKeydown(e) {
      if (!visibleSuggestions.length) return;
      if (e.key === 'ArrowDown') {
        e.preventDefault();
        activeIndex = Math.min(activeIndex + 1, visibleSuggestions.length - 1);
        highlightSuggestion(activeIndex);
      } else if (e.key === 'ArrowUp') {
        e.preventDefault();
        activeIndex = Math.max(activeIndex - 1, 0);
        highlightSuggestion(activeIndex);
      } else if (e.key === 'Enter' && activeIndex >= 0) {
        e.preventDefault();
        pickSuggestion(visibleSuggestions[activeIndex]);
      } else if (e.key === 'Escape') {
        closeSuggestions();
      }
    }

    document.addEventListener('click', function (e) {
      if (!suggestionsBox) return;
      var li = e.target.closest && e.target.closest('.natura-street-suggestion');
      if (li && suggestionsBox.contains(li)) {
        var idx = parseInt(li.getAttribute('data-idx'), 10);
        if (!isNaN(idx)) pickSuggestion(visibleSuggestions[idx]);
        return;
      }
      if (!suggestionsBox.contains(e.target) && e.target !== addrEl) closeSuggestions();
    });

    addrEl.addEventListener('input', onAddrInput);
    addrEl.addEventListener('keydown', onAddrKeydown);
    addrEl.addEventListener('focus', function () {
      var v = (addrEl.value || '').trim();
      if (v.length >= 2 && streetIndex.length) searchStreets(v);
    });

    cityEl.addEventListener('change', onCityChange);
    cityEl.addEventListener('input', debounce(onCityChange, 250));

    // SelectWoo dispatches change via jQuery only — same retry pattern as Sector.
    function bindAddressStateListeners() {
      if (stateEl) stateEl.addEventListener('change', onStateChange);
      if (!window.jQuery) return false;
      var $ = window.jQuery;
      $(document).on('change', 'select[name="billing_state"], input[name="billing_state"]', onStateChange);
      $(document.body).on('country_to_state_changed', onStateChange);
      return true;
    }

    if (!bindAddressStateListeners()) {
      var addrTries = 0;
      var addrPoll = setInterval(function () {
        addrTries++;
        if (bindAddressStateListeners() || addrTries > 40) clearInterval(addrPoll);
      }, 100);
    }

    // Initial sync — for logged-in customers with a saved address, kick the
    // cascade so the datalist + street index are warm before they edit.
    onStateChange();
    if (cityEl.value) onCityChange();
  })();
})();
