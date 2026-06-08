/* ============================================================================
 * Shop / catalog page (.catalog-page).
 * - Filtre + sortare prin AJAX (fără full reload) cu history.pushState.
 * - Vizual toggle pentru .opt / .chip checkboxes (sincronizat cu inputul ascuns).
 * - Price slider: 2× input[range] cu fill colorat între ele.
 * - View-toggle grid/list (in iteratia asta doar vizual).
 *
 * Endpoint: action=natura_shop_filter, config în window.natura_shop_filters
 * (localizat în app/shop-filters.php).
 * ============================================================================ */

(function () {
  var root = document.querySelector('.catalog-page');
  if (!root) return;

  var form = root.querySelector('[data-shop-filters]');
  var orderby = root.querySelector('[data-shop-orderby]');
  var results = root.querySelector('.catalog-results');
  var countEl = root.querySelector('.toolbar .count');
  var cfg = window.natura_shop_filters || null;

  /* -------------------------------------------------------------------------
   * Helpers
   * ------------------------------------------------------------------------ */
  function buildFormData(paged) {
    var data = new FormData();
    data.append('action', 'natura_shop_filter');
    data.append('nonce', cfg ? cfg.nonce : '');
    data.append('paged', paged || 1);
    data.append('base_url', cfg ? cfg.base_url : window.location.href.split('?')[0]);
    if (cfg && cfg.context) {
      data.append('context', cfg.context);
    }
    // Pe paginile de categorie: scope AJAX la termenul curent (shop_filter_ajax).
    if (cfg && cfg.taxonomy && cfg.term) {
      data.append('taxonomy', cfg.taxonomy);
      data.append('term', cfg.term);
    }

    if (form) {
      Array.from(form.elements).forEach(function (el) {
        if (!el.name) return;
        if (el.type === 'checkbox' && !el.checked) return;
        if (el.disabled) return;
        data.append(el.name, el.value);
      });
    }
    if (orderby) {
      data.append('orderby', orderby.value);
    }
    return data;
  }

  function buildQueryString(paged) {
    var params = new URLSearchParams();
    if (form) {
      Array.from(form.elements).forEach(function (el) {
        if (!el.name) return;
        if (el.type === 'checkbox' && !el.checked) return;
        if (el.disabled) return;
        params.append(el.name, el.value);
      });
    }
    if (orderby && orderby.value && orderby.value !== 'menu_order') {
      params.append('orderby', orderby.value);
    }
    if (paged && paged > 1) {
      params.append('paged', paged);
    }
    var qs = params.toString();
    return qs ? '?' + qs : '';
  }

  var inflight = null;

  function loadResults(paged, pushHistory) {
    if (!cfg || !results) return;
    paged = paged || 1;

    if (inflight) inflight.abort();
    var controller = new AbortController();
    inflight = controller;

    results.classList.add('is-loading');

    fetch(cfg.ajax_url, {
      method: 'POST',
      credentials: 'same-origin',
      body: buildFormData(paged),
      signal: controller.signal,
    })
      .then(function (r) { return r.json(); })
      .then(function (res) {
        if (!res || !res.success) throw new Error('bad response');
        results.innerHTML = res.data.html;
        if (countEl && res.data.count_html) {
          countEl.innerHTML = res.data.count_html;
        }
        if (pushHistory) {
          var url = (cfg.base_url || window.location.pathname) + buildQueryString(paged);
          history.pushState({ shopFilter: true, paged: paged }, '', url);
        }
        // Scroll back to top of results for paginated transitions.
        if (paged > 1 || pushHistory) {
          var top = results.getBoundingClientRect().top + window.pageYOffset - 100;
          if (window.pageYOffset > top) {
            window.scrollTo({ top: top, behavior: 'smooth' });
          }
        }
        results.dispatchEvent(new CustomEvent('shop:updated', { bubbles: true }));
      })
      .catch(function (err) {
        if (err.name === 'AbortError') return;
        // Fallback la submit clasic dacă AJAX eșuează.
        if (form) form.submit();
      })
      .finally(function () {
        results.classList.remove('is-loading');
        if (inflight === controller) inflight = null;
      });
  }

  /* -------------------------------------------------------------------------
   * 1. Sidebar form — debounced AJAX submit pe change
   * ------------------------------------------------------------------------ */
  if (form) {
    var submitTimer = null;
    var debouncedSubmit = function () {
      clearTimeout(submitTimer);
      submitTimer = setTimeout(function () {
        if (cfg) {
          loadResults(1, true);
        } else {
          // No AJAX config (e.g. WC not loaded) → fallback classic submit.
          Array.from(form.elements).forEach(function (el) {
            if (el.type === 'checkbox' && !el.checked) el.disabled = true;
          });
          form.submit();
        }
      }, 300);
    };

    form.addEventListener('change', function (e) {
      var input = e.target;
      if (input && input.type === 'checkbox') {
        var parent = input.closest('.opt, .chip');
        if (parent) {
          parent.classList.toggle(
            parent.classList.contains('chip') ? 'active' : 'checked',
            input.checked
          );
        }
      }
      debouncedSubmit();
    });

    // Click handler pe .opt / .chip — toggle inputul ascuns.
    form.querySelectorAll('.opt, .chip').forEach(function (label) {
      label.addEventListener('click', function (e) {
        if (e.target.tagName === 'INPUT') return;
        var input = label.querySelector('input[type="checkbox"]');
        if (!input) return;
        e.preventDefault();
        input.checked = !input.checked;
        input.dispatchEvent(new Event('change', { bubbles: true }));
      });
    });

    // Reset link — interceptăm să facem AJAX request gol în loc de navigare.
    var resetLink = form.parentElement.querySelector('.sb-head a');
    if (resetLink && cfg) {
      resetLink.addEventListener('click', function (e) {
        e.preventDefault();
        // Uncheck all + reset price slider.
        Array.from(form.elements).forEach(function (el) {
          if (el.type === 'checkbox') {
            el.checked = false;
            var parent = el.closest('.opt, .chip');
            if (parent) parent.classList.remove('checked', 'active');
          }
        });
        form.querySelectorAll('[data-price-slider]').forEach(function (slider) {
          var minI = slider.querySelector('input[data-handle="min"]');
          var maxI = slider.querySelector('input[data-handle="max"]');
          if (minI) minI.value = minI.min;
          if (maxI) maxI.value = maxI.max;
          minI && minI.dispatchEvent(new Event('input', { bubbles: true }));
        });
        if (orderby) orderby.value = 'menu_order';
        loadResults(1, true);
      });
    }
  }

  /* -------------------------------------------------------------------------
   * 2. Sort dropdown — declanșează același AJAX path ca filtrele
   * ------------------------------------------------------------------------ */
  if (orderby) {
    orderby.addEventListener('change', function () {
      if (cfg) {
        loadResults(1, true);
      } else if (form) {
        form.submit();
      }
    });
  }

  /* -------------------------------------------------------------------------
   * 3. Pagination — interceptează clickurile pe link-urile generate de
   *    paginate_links() și încarcă AJAX. Funcționează și după re-render
   *    (delegated event handler pe `results`).
   * ------------------------------------------------------------------------ */
  if (cfg && results) {
    results.addEventListener('click', function (e) {
      var link = e.target.closest('.page-nav a');
      if (!link) return;
      var href = link.getAttribute('href');
      if (!href) return;
      e.preventDefault();
      var match = href.match(/[\?&]paged=(\d+)/) || href.match(/\/page\/(\d+)/);
      var paged = match ? parseInt(match[1], 10) : 1;
      loadResults(paged, true);
    });
  }

  /* -------------------------------------------------------------------------
   * 4. History back/forward — re-sync form + reload din URL
   * ------------------------------------------------------------------------ */
  if (cfg) {
    window.addEventListener('popstate', function (e) {
      // Citește starea curentă din URL și aplic-o pe form + reload.
      var params = new URLSearchParams(window.location.search);
      if (form) {
        Array.from(form.elements).forEach(function (el) {
          if (el.type === 'checkbox') {
            var values = params.getAll(el.name);
            el.checked = values.indexOf(el.value) !== -1;
            var parent = el.closest('.opt, .chip');
            if (parent) {
              parent.classList.toggle(
                parent.classList.contains('chip') ? 'active' : 'checked',
                el.checked
              );
            }
          } else if (el.name === 'min_price' || el.name === 'max_price') {
            var v = params.get(el.name);
            if (v !== null) el.value = v;
          }
        });
        // Trigger slider repaint.
        form.querySelectorAll('[data-price-slider] input[data-handle="min"]').forEach(function (i) {
          i.dispatchEvent(new Event('input', { bubbles: true }));
        });
      }
      if (orderby) {
        orderby.value = params.get('orderby') || 'menu_order';
      }
      var paged = parseInt(params.get('paged'), 10) || 1;
      loadResults(paged, false);
    });
  }

  /* -------------------------------------------------------------------------
   * 5. Price range slider — 2× input[range] cu fill între ele
   * ------------------------------------------------------------------------ */
  var sliders = root.querySelectorAll('[data-price-slider]');
  sliders.forEach(function (slider) {
    var min = parseInt(slider.dataset.min, 10) || 0;
    var max = parseInt(slider.dataset.max, 10) || 1000;
    var range = Math.max(1, max - min);
    var minInput = slider.querySelector('input[data-handle="min"]');
    var maxInput = slider.querySelector('input[data-handle="max"]');
    var fill = slider.querySelector('.fill');
    var card = slider.closest('.block') || slider.parentElement;
    var minDisplay = card.querySelector('[data-price-display="min"]');
    var maxDisplay = card.querySelector('[data-price-display="max"]');

    if (!minInput || !maxInput || !fill) return;

    function clamp() {
      var lo = parseInt(minInput.value, 10);
      var hi = parseInt(maxInput.value, 10);
      if (lo > hi - 1) {
        if (document.activeElement === minInput) {
          minInput.value = hi - 1;
        } else {
          maxInput.value = lo + 1;
        }
      }
    }

    function update() {
      clamp();
      var lo = parseInt(minInput.value, 10);
      var hi = parseInt(maxInput.value, 10);
      var leftPct = ((lo - min) / range) * 100;
      var rightPct = 100 - ((hi - min) / range) * 100;
      fill.style.left = leftPct + '%';
      fill.style.right = rightPct + '%';
      if (minDisplay) minDisplay.value = lo + ' lei';
      if (maxDisplay) maxDisplay.value = hi + ' lei';
    }

    minInput.addEventListener('input', update);
    maxInput.addEventListener('input', update);
    update();
  });

  /* -------------------------------------------------------------------------
   * 6. View toggle (grid/list) — toggle activ vizual.
   * ------------------------------------------------------------------------ */
  var viewBtns = root.querySelectorAll('.view-toggle button[data-view]');
  viewBtns.forEach(function (btn) {
    btn.addEventListener('click', function () {
      viewBtns.forEach(function (b) {
        b.classList.remove('active');
        b.setAttribute('aria-pressed', 'false');
      });
      btn.classList.add('active');
      btn.setAttribute('aria-pressed', 'true');
      root.classList.toggle('view-list', btn.dataset.view === 'list');
    });
  });
})();
