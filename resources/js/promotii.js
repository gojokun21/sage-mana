/* ============================================================================
 * Pagina Promoții (.promo-page) — filtrare + sortare client-side a cardurilor
 * de ofertă deja randate (set finit de produse la reducere). Fără AJAX.
 *   - chip-uri categorie  [data-filter-cat]   (data-cat pe card)
 *   - chip-uri reducere   [data-filter-disc]  (data-disc pe card, prag minim)
 *   - sortare             [data-promo-sort]   (preț/discount/recomandate)
 * Lazy-loaded din app.js când `.promo-page` e prezent.
 * ========================================================================== */

(function () {
  var root = document.querySelector('.promo-page');
  if (!root) return;

  var grid = root.querySelector('[data-promo-grid]');
  if (!grid) return;

  var cards = Array.prototype.slice.call(grid.querySelectorAll('.offer-card'));
  var originalOrder = cards.slice();
  var countEl = root.querySelector('[data-promo-count]');
  var emptyEl = root.querySelector('[data-promo-empty]');
  var catChips = Array.prototype.slice.call(root.querySelectorAll('[data-filter-cat]'));
  var discChips = Array.prototype.slice.call(root.querySelectorAll('[data-filter-disc]'));
  var sortSel = root.querySelector('[data-promo-sort]');

  var state = { cat: 'all', disc: 0, sort: 'recommended' };

  function price(c) { return parseFloat(c.getAttribute('data-price')) || 0; }
  function disc(c) { return parseInt(c.getAttribute('data-disc'), 10) || 0; }

  function apply() {
    // Sortare (reordonăm DOM-ul) — apoi filtrare (hidden).
    var sorted = originalOrder.slice();
    if (state.sort === 'price-asc') {
      sorted.sort(function (a, b) { return price(a) - price(b); });
    } else if (state.sort === 'price-desc') {
      sorted.sort(function (a, b) { return price(b) - price(a); });
    } else if (state.sort === 'discount') {
      sorted.sort(function (a, b) { return disc(b) - disc(a); });
    }
    sorted.forEach(function (card) { grid.appendChild(card); });

    var visible = 0;
    cards.forEach(function (card) {
      var c = card.getAttribute('data-cat') || '';
      var show = (state.cat === 'all' || c === state.cat) && disc(card) >= state.disc;
      card.hidden = !show;
      if (show) visible++;
    });

    if (countEl) {
      countEl.textContent = visible + ' ' + (visible === 1 ? 'produs' : 'produse');
    }
    if (emptyEl) {
      emptyEl.hidden = visible !== 0;
    }
  }

  function bindChips(chips, key, parse) {
    chips.forEach(function (chip) {
      chip.addEventListener('click', function () {
        chips.forEach(function (x) { x.classList.remove('active'); });
        chip.classList.add('active');
        state[key] = parse(chip);
        apply();
      });
    });
  }

  bindChips(catChips, 'cat', function (chip) { return chip.getAttribute('data-filter-cat'); });
  bindChips(discChips, 'disc', function (chip) { return parseInt(chip.getAttribute('data-filter-disc'), 10) || 0; });

  if (sortSel) {
    sortSel.addEventListener('change', function () {
      state.sort = sortSel.value;
      apply();
    });
  }
})();
