/**
 * Account → Comenzi: client-side status filter chips, search, sort, expandable
 * order details, and pagination over the filtered set. Progressive enhancement —
 * the page is fully rendered server-side; this just filters/paginates.
 */
(function () {
  'use strict';

  var root = document.querySelector('.orders-page');
  if (!root) return;

  var list = root.querySelector('#opList');
  if (!list) return;

  var chips = Array.prototype.slice.call(root.querySelectorAll('#opChips .op-chip'));
  var search = root.querySelector('#opSearch');
  var sortSel = root.querySelector('#opSort');
  var pagination = root.querySelector('#opPagination');
  var noResults = root.querySelector('#opNoResults');
  var cards = Array.prototype.slice.call(list.querySelectorAll('.op-card'));

  var PAGE_SIZE = 8;
  var activeStatus = 'all';
  var page = 1;

  function matches(card) {
    var q = (search && search.value ? search.value : '').trim().toLowerCase();
    var statusOk = activeStatus === 'all' || card.getAttribute('data-status') === activeStatus;
    var hay = (card.getAttribute('data-search') || '') + ' ' + card.textContent.toLowerCase();
    var searchOk = !q || hay.indexOf(q) > -1;
    return statusOk && searchOk;
  }

  function sortCards() {
    if (!sortSel) return;
    var mode = sortSel.value;
    var sorted = cards.slice().sort(function (a, b) {
      var da = parseInt(a.getAttribute('data-date') || '0', 10);
      var db = parseInt(b.getAttribute('data-date') || '0', 10);
      var aa = parseFloat(a.getAttribute('data-amount') || '0');
      var ab = parseFloat(b.getAttribute('data-amount') || '0');
      switch (mode) {
        case 'old': return da - db;
        case 'amount-asc': return aa - ab;
        case 'amount-desc': return ab - aa;
        default: return db - da; // recent
      }
    });
    sorted.forEach(function (c) { list.appendChild(c); });
  }

  function render() {
    var visible = cards.filter(matches);

    // Clamp page to the available range.
    var pages = Math.max(1, Math.ceil(visible.length / PAGE_SIZE));
    if (page > pages) page = pages;

    var start = (page - 1) * PAGE_SIZE;
    var end = start + PAGE_SIZE;

    cards.forEach(function (c) { c.classList.add('hidden'); });
    visible.slice(start, end).forEach(function (c) { c.classList.remove('hidden'); });

    if (noResults) noResults.hidden = visible.length !== 0;

    renderPagination(pages);
  }

  function renderPagination(pages) {
    if (!pagination) return;
    pagination.innerHTML = '';
    if (pages <= 1) return;

    var prev = button('‹ ' + 'Anterior', function () { if (page > 1) { page--; render(); scrollTop(); } });
    prev.className = 'op-pag-btn';
    if (page === 1) prev.setAttribute('disabled', 'disabled');
    pagination.appendChild(prev);

    for (var i = 1; i <= pages; i++) {
      (function (n) {
        var b = button(String(n), function () { page = n; render(); scrollTop(); });
        b.className = 'op-pag-num' + (n === page ? ' active' : '');
        pagination.appendChild(b);
      })(i);
    }

    var next = button('Următor ›', function () { if (page < pages) { page++; render(); scrollTop(); } });
    next.className = 'op-pag-btn';
    if (page === pages) next.setAttribute('disabled', 'disabled');
    pagination.appendChild(next);
  }

  function button(label, onClick) {
    var b = document.createElement('button');
    b.type = 'button';
    b.textContent = label;
    b.addEventListener('click', onClick);
    return b;
  }

  function scrollTop() {
    var top = list.getBoundingClientRect().top + window.scrollY - 100;
    window.scrollTo({ top: top, behavior: 'smooth' });
  }

  /* Filters */
  chips.forEach(function (chip) {
    chip.addEventListener('click', function () {
      chips.forEach(function (c) { c.classList.remove('active'); });
      chip.classList.add('active');
      activeStatus = chip.getAttribute('data-status');
      page = 1;
      render();
    });
  });

  if (search) {
    search.addEventListener('input', function () { page = 1; render(); });
  }

  if (sortSel) {
    sortSel.addEventListener('change', function () { sortCards(); page = 1; render(); });
  }

  /* Expand toggles */
  list.addEventListener('click', function (e) {
    var toggle = e.target.closest('[data-op-expand]');
    if (!toggle) return;
    e.preventDefault();
    var card = toggle.closest('.op-card');
    if (card) card.classList.toggle('expanded');
  });

  render();
})();
