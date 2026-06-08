/**
 * Pagina „Confirmare comandă" (order-received).
 * Lazy-loaded din app.js când există `.or-confirm` în DOM.
 *
 * Singura interacțiune: toggle vizual pe opt-in-ul de suport pe parcursul curei
 * (cele 3 emailuri). E o intenție cosmetică din design — nu există backend; la
 * „Activează" doar marcăm vizual starea. FAQ-ul folosește <details> nativ.
 */

document.querySelectorAll('.or-confirm [data-reminder]').forEach(function (row) {
  var cbRow = row.querySelector('.cb-row');
  var activate = row.querySelector('.activate');

  function toggle() {
    row.classList.toggle('checked');
  }

  if (cbRow) {
    cbRow.addEventListener('click', toggle);
  }
  if (activate) {
    activate.addEventListener('click', function () {
      row.classList.add('checked');
      activate.textContent = activate.dataset.done || 'Activat ✓';
    });
  }
});
