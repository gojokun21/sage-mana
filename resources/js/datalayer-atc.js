/**
 * GA4 add_to_cart — AJAX paths.
 *
 * Pairs with app/datalayer.php which handles the non-AJAX reload via wp_head.
 *
 * Listens to WC's jQuery `added_to_cart` event on document.body via the
 * window.jQuery bridge (same pattern as resources/js/mini-cart.js — no jQuery
 * build dep). Three sources, in order of preference:
 *
 *   1. window.__mn_atc_just_pushed — server-side already pushed; bail.
 *   2. fragments.mn_atc_dl_payload — emitted by WC standard AJAX add_to_cart.
 *   3. button[data-product_*] + DOM-scraped price — for the custom mini-cart
 *      endpoint and form-POST fallback in product-atc.js, which trigger
 *      `added_to_cart` manually with fragments=null.
 */
(function () {
  function bind() {
    if (!window.jQuery) return false;
    var $ = window.jQuery;

    $(document.body).on('added_to_cart', function (event, fragments, cart_hash, $button) {
      if (window.__mn_atc_just_pushed) return;

      var payload = null;

      if (fragments && typeof fragments.mn_atc_dl_payload === 'string') {
        try {
          payload = JSON.parse(fragments.mn_atc_dl_payload);
        } catch (e) {
          payload = null;
        }
      }

      if (!payload && $button && $button.length) {
        payload = buildPayloadFromButton($button[0]);
      }

      if (!payload) return;

      window.dataLayer = window.dataLayer || [];
      window.dataLayer.push({ ecommerce: null });
      window.dataLayer.push(payload);
    });

    return true;
  }

  function buildPayloadFromButton(btn) {
    if (!btn) return null;

    var id =
      btn.getAttribute('data-product_sku') ||
      btn.getAttribute('data-product_id') ||
      '';
    if (!id) return null;

    var qty = Math.max(1, parseInt(btn.getAttribute('data-quantity'), 10) || 1);

    // Form-based adds (bundle/variable) read the qty from the form input the
    // user actually changed; fall back to that when the trigger button is the
    // submit, not an archive-style anchor.
    var form = btn.closest ? btn.closest('form.cart') : null;
    if (form) {
      var qtyInput = form.querySelector('input.qty, input[name="quantity"]');
      if (qtyInput) {
        qty = Math.max(1, parseInt(qtyInput.value, 10) || 1);
      }
    }

    var price = readPrice(btn);
    var name = btn.getAttribute('data-product_name') || (btn.textContent || '').trim();

    var cfg = window.mn_ga4 || {};
    var brand = btn.getAttribute('data-product_brand') || cfg.brand || 'Natura';
    var category = btn.getAttribute('data-product_category') || '';

    var item = {
      item_id: String(id),
      item_name: String(name),
      item_brand: brand,
      price: price,
      quantity: qty,
    };
    if (category) item.item_category = category;

    return {
      event: 'add_to_cart',
      ecommerce: {
        currency: cfg.currency || 'RON',
        value: parseFloat((price * qty).toFixed(2)),
        items: [item],
      },
    };
  }

  // Price resolution chain: button data-attr (set server-side by datalayer.php
  // for loop/bundle/simple buttons) → DOM scrape from the nearest product card
  // / single-product price block → schema.org meta tag emitted on PDPs. The
  // attr should win whenever present so bundle adds (no visible price near
  // the submit button) get the right value.
  function readPrice(btn) {
    if (btn && btn.getAttribute) {
      var attr = btn.getAttribute('data-product_price');
      if (attr) {
        var direct = parseFloat(attr);
        if (isFinite(direct) && direct > 0) return direct;
      }
    }

    var scraped = scrapePrice(btn);
    if (scraped > 0) return scraped;

    var meta = document.querySelector('meta[itemprop="price"]');
    if (meta) {
      var metaVal = parseFloat(meta.getAttribute('content'));
      if (isFinite(metaVal) && metaVal > 0) return metaVal;
    }

    return 0;
  }

  // Romanian/RON formatting: "1.234,56 lei" → 1234.56. Looks at the closest
  // product container first, falls back to the visible single-product price.
  function scrapePrice(btn) {
    var priceEl = null;
    var card = btn.closest && btn.closest('.product, li.product, .product-card');
    if (card) {
      priceEl =
        card.querySelector('.price ins .woocommerce-Price-amount') ||
        card.querySelector('.price .woocommerce-Price-amount');
    }
    if (!priceEl) {
      priceEl =
        document.querySelector('.product-main-info .price ins .woocommerce-Price-amount') ||
        document.querySelector('.product-main-info .price .woocommerce-Price-amount') ||
        document.querySelector('.summary .price ins .woocommerce-Price-amount') ||
        document.querySelector('.summary .price .woocommerce-Price-amount');
    }
    if (!priceEl) return 0;

    var raw = (priceEl.textContent || '').replace(/[^\d.,]/g, '');
    // Strip thousands separators (.) and convert decimal comma to dot.
    var num = raw.replace(/\./g, '').replace(',', '.');
    var parsed = parseFloat(num);
    return isFinite(parsed) ? parsed : 0;
  }

  if (!bind()) {
    var tries = 0;
    var poll = setInterval(function () {
      if (bind() || ++tries > 40) clearInterval(poll); // ~4s max
    }, 100);
  }
})();
