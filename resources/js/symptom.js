/**
 * Hub „După simptom" — filtrare live a cardurilor după textul din hero.
 * Lazy-loaded din app.js când `.symptom-page` e prezent.
 *
 * Comportament:
 *  - tastând în #symptomSearch, ascundem cardurile care nu se potrivesc;
 *  - dacă o grupă rămâne fără carduri vizibile, ascundem toată secțiunea;
 *  - dacă nimic nu se potrivește, arătăm mesajul „niciun rezultat";
 *  - căutarea ignoră diacriticele și majusculele.
 */

const input = document.getElementById('symptomSearch');
if (input) {
  const root = document.querySelector('.symptom-page');
  const cards = Array.from(root.querySelectorAll('.grp-card'));
  const blocks = Array.from(root.querySelectorAll('.grp-block'));
  const noResult = root.querySelector('[data-symptom-noresult]');
  const queryOut = root.querySelector('[data-symptom-query]');
  const footLine = root.querySelector('.grp-foot-line');

  // lowercase + fără diacritice (ă→a, î→i, ș→s, ț→t etc.).
  const normalize = (str) =>
    (str || '')
      .toLowerCase()
      .normalize('NFD')
      .replace(/[̀-ͯ]/g, '');

  // Pre-calculăm textul de căutat o singură dată per card.
  const haystacks = cards.map((card) =>
    normalize(`${card.dataset.symptom} ${card.dataset.desc}`)
  );

  const apply = () => {
    const q = normalize(input.value.trim());
    let anyVisible = false;

    cards.forEach((card, i) => {
      const match = q === '' || haystacks[i].includes(q);
      card.classList.toggle('is-hidden', !match);
      if (match) anyVisible = true;
    });

    // Ascunde grupele complet filtrate.
    blocks.forEach((block) => {
      const hasVisible = block.querySelector('.grp-card:not(.is-hidden)') !== null;
      block.classList.toggle('is-hidden', !hasVisible);
    });

    // Foot-line („mai sunt 12 simptome…") doar când nu filtrăm.
    if (footLine) footLine.style.display = q === '' ? '' : 'none';

    // Mesaj „niciun rezultat".
    if (noResult) {
      noResult.classList.toggle('is-visible', q !== '' && !anyVisible);
      if (queryOut) queryOut.textContent = input.value.trim();
    }
  };

  input.addEventListener('input', apply);
  // Esc golește căutarea.
  input.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
      input.value = '';
      apply();
    }
  });
}
