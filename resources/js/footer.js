/* ==================== FOOTER ACCORDION (mobile only) ==================== */

(function () {
  var MOBILE_MAX = 767;

  document.addEventListener('click', function (e) {
    if (window.innerWidth > MOBILE_MAX) return;

    var header = e.target.closest && e.target.closest('[data-footer-accordion-toggle]');
    if (!header) return;

    var accordion = header.closest('[data-footer-accordion]');
    if (!accordion) return;

    e.preventDefault();
    accordion.classList.toggle('is-open');
  });
})();
