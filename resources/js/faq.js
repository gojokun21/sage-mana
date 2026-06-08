/**
 * Pagina FAQ — căutare/filtrare live peste întrebări.
 * Lazy-load din app.js când există `.faq-page` în DOM.
 * Portat din mockup-ul `preferinte/Pagina FAQ.html`.
 */

// Normalizează diacriticele pentru căutare case-insensitive ASCII.
function norm(s) {
  return (s || '')
    .toLowerCase()
    .normalize('NFD')
    .replace(/[̀-ͯ]/g, '')
    .replace(/[ăâ]/g, 'a')
    .replace(/[î]/g, 'i')
    .replace(/[șş]/g, 's')
    .replace(/[țţ]/g, 't');
}

function escapeReg(s) {
  return s.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
}

const box = document.getElementById('searchBox');
const input = document.getElementById('faqSearch');
const clearBtn = document.getElementById('clearBtn');
const info = document.getElementById('searchInfo');
const matchEl = document.getElementById('matchCount');
const noRes = document.getElementById('noResults');
const items = Array.from(document.querySelectorAll('.faq-page .faq-item'));
const sections = Array.from(document.querySelectorAll('.faq-page .faq-section'));

if (input && items.length) {
  // Cache HTML-ul original al fiecărui q-text ca să-l restaurăm între căutări.
  items.forEach((it) => {
    const qt = it.querySelector('.q-text');
    if (qt) qt.dataset.original = qt.innerHTML;
  });

  const applyHighlight = (qt, raw) => {
    const orig = qt.dataset.original;
    if (!raw) {
      qt.innerHTML = orig;
      return;
    }
    const rgx = new RegExp('(' + escapeReg(raw) + ')', 'gi');
    qt.innerHTML = orig.replace(rgx, '<mark>$1</mark>');
  };

  const clearHighlights = () => {
    items.forEach((it) => {
      const qt = it.querySelector('.q-text');
      if (qt) qt.innerHTML = qt.dataset.original;
    });
  };

  const filter = () => {
    const raw = input.value.trim();
    const q = norm(raw);

    if (!q) {
      items.forEach((it) => {
        it.classList.remove('hidden');
        it.open = false;
      });
      sections.forEach((s) => {
        s.style.display = '';
      });
      clearHighlights();
      box.classList.remove('has-text');
      info.classList.remove('show');
      noRes.classList.remove('show');
      return;
    }

    box.classList.add('has-text');
    let match = 0;

    items.forEach((it) => {
      const qt = it.querySelector('.q-text');
      const ans = it.querySelector('.answer');
      const hay = norm(
        (it.dataset.q || '') +
          ' ' +
          (qt ? qt.dataset.original : '') +
          ' ' +
          (ans ? ans.textContent : '')
      );

      if (hay.indexOf(q) > -1) {
        it.classList.remove('hidden');
        it.open = true;
        if (qt) applyHighlight(qt, raw);
        match++;
      } else {
        it.classList.add('hidden');
        it.open = false;
        if (qt) qt.innerHTML = qt.dataset.original;
      }
    });

    // Ascunde secțiunea dacă toate întrebările din ea sunt ascunse.
    sections.forEach((s) => {
      const visible = s.querySelectorAll('.faq-item:not(.hidden)').length;
      s.style.display = visible ? '' : 'none';
    });

    matchEl.textContent = match;
    info.classList.add('show');
    noRes.classList.toggle('show', match === 0);
  };

  input.addEventListener('input', filter);
  if (clearBtn) {
    clearBtn.addEventListener('click', () => {
      input.value = '';
      filter();
      input.focus();
    });
  }
}
