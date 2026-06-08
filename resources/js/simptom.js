/**
 * Pagina simptom (detaliu) — interacțiunea autotestului.
 * Lazy-loaded din app.js când `.simptom-detail` e prezent.
 *
 * Comportament:
 *  - fiecare întrebare (.auto-q) e un radiogroup: un singur răspuns activ;
 *  - click sau Enter/Space pe o opțiune o selectează și le deselectează pe
 *    celelalte din aceeași întrebare;
 *  - actualizăm aria-checked pentru accesibilitate.
 *
 * Acordeonul FAQ folosește componenta partajată faq-accordion.js (încărcată
 * separat din app.js când există `.faq .faq-item`), deci nu îl tratăm aici.
 */

const root = document.querySelector('.simptom-detail');

if (root) {
  root.querySelectorAll('.auto-q .auto-opts').forEach((group) => {
    const opts = Array.from(group.querySelectorAll('.auto-opt'));

    const select = (chosen) => {
      opts.forEach((opt) => {
        const active = opt === chosen;
        opt.classList.toggle('selected', active);
        opt.setAttribute('aria-checked', active ? 'true' : 'false');
      });
    };

    opts.forEach((opt) => {
      opt.addEventListener('click', () => select(opt));
      opt.addEventListener('keydown', (e) => {
        if (e.key === 'Enter' || e.key === ' ') {
          e.preventDefault();
          select(opt);
        }
      });
    });
  });
}
