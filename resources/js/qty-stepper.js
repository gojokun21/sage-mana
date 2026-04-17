/**
 * .qty-stepper — canonical +/- quantity control.
 *
 * A single delegated click listener on the document handles every
 * `[data-qty-ctrl]` inside a `.qty-stepper`. Consumers (mini-cart,
 * cart page, sticky bar…) should listen to the `change` event on
 * the input, NOT to clicks on the buttons, to avoid double-firing.
 *
 * Respects min / max / step and dispatches both `input` and `change`
 * events so WooCommerce + framework listeners react correctly.
 */

(function () {
  function clamp(val, min, max) {
    if (val < min) return min;
    if (val > max) return max;
    return val;
  }

  function step(input, direction) {
    if (!input || input.disabled) return;

    var min = input.min !== '' ? parseFloat(input.min) : -Infinity;
    var max = input.max !== '' ? parseFloat(input.max) : Infinity;
    var stepVal = parseFloat(input.step) || 1;
    var current = parseFloat(input.value);
    if (isNaN(current)) current = isFinite(min) ? min : 0;

    var requested = direction === 'inc' ? current + stepVal : current - stepVal;
    var next = clamp(requested, isFinite(min) ? min : 0, max);

    if (next === current) {
      // Hit a boundary — surface a toast so the user knows why the
      // stepper "did nothing". Only for the upper bound (stock limit).
      if (direction === 'inc' && requested > max
          && window.NaturaToast && typeof window.NaturaToast.show === 'function') {
        window.NaturaToast.show(
          'Ai atins cantitatea maximă disponibilă.',
          { variant: 'error', duration: 3000 }
        );
      }
      return;
    }

    input.value = next;
    input.dispatchEvent(new Event('input', { bubbles: true }));
    input.dispatchEvent(new Event('change', { bubbles: true }));
  }

  document.addEventListener('click', function (e) {
    var btn = e.target.closest && e.target.closest('[data-qty-ctrl]');
    if (!btn || btn.disabled) return;

    var stepper = btn.closest('.qty-stepper');
    if (!stepper) return;

    var input = stepper.querySelector('input[type="number"], input.qty');
    if (!input) return;

    e.preventDefault();
    step(input, btn.getAttribute('data-qty-ctrl'));
  });
})();
