/* Cont · Date personale (.dp-page). Pur vizual: comută editarea inline per câmp,
 * deschide/închide modalul de parolă și animă indicatorul de putere. Salvarea e
 * POST nativ WooCommerce (butoanele type="submit" trimit formularul edit-account). */

(function () {
  var page = document.querySelector('.dp-page');
  if (!page) return;

  // ---- Inline edit toggle ----
  page.querySelectorAll('[data-edit]').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var row = btn.closest('.field-row');
      if (!row) return;
      row.classList.add('editing');
      var input = row.querySelector('.val-edit input, .val-edit select');
      if (input) input.focus();
    });
  });

  page.querySelectorAll('[data-cancel]').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var row = btn.closest('.field-row');
      if (!row) return;
      // Restore each control to its original value so a cancel discards edits.
      row.querySelectorAll('.val-edit input, .val-edit select').forEach(function (el) {
        if (typeof el.dataset.original !== 'undefined') el.value = el.dataset.original;
      });
      row.classList.remove('editing');
    });
  });

  // ---- Password modal ----
  var modal = document.getElementById('dpModalPw');

  function openModal() {
    if (modal) modal.classList.add('open');
  }
  function closeModal() {
    if (modal) modal.classList.remove('open');
  }

  page.querySelectorAll('[data-modal-open]').forEach(function (btn) {
    btn.addEventListener('click', openModal);
  });
  page.querySelectorAll('[data-modal-close]').forEach(function (btn) {
    btn.addEventListener('click', closeModal);
  });
  if (modal) {
    modal.addEventListener('click', function (e) {
      if (e.target === modal) closeModal();
    });
  }
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') closeModal();
  });

  // ---- Password strength meter ----
  var pwNew = document.getElementById('password_1');
  var meter = document.getElementById('dpPwMeter');
  if (pwNew && meter) {
    pwNew.addEventListener('input', function () {
      meter.classList.remove('weak', 'medium', 'strong');
      var v = pwNew.value;
      if (!v) return;
      if (v.length < 8) {
        meter.classList.add('weak');
        return;
      }
      var hasLetter = /[a-zA-ZăâîșțĂÂÎȘȚ]/.test(v);
      var hasDigit = /\d/.test(v);
      if (hasLetter && hasDigit) meter.classList.add('strong');
      else if (hasLetter || hasDigit) meter.classList.add('medium');
      else meter.classList.add('weak');
    });
  }
})();
