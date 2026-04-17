/**
 * Global toast notifier. Exposes `window.NaturaToast.show(message, opts)`
 * and auto-listens for `natura:mini-cart:error` so failed AJAX add-to-cart
 * / cart-update flows surface a visible message to the user.
 *
 * opts: { variant: 'info'|'error'|'success', duration: ms }
 */
(function () {
  var timer = null;
  var el = null;

  function ensureEl() {
    if (el && el.isConnected) return el;
    el = document.getElementById('natura-toast');
    if (!el) {
      el = document.createElement('div');
      el.id = 'natura-toast';
      el.className = 'natura-toast';
      el.setAttribute('role', 'status');
      el.setAttribute('aria-live', 'polite');
      document.body.appendChild(el);
    }
    return el;
  }

  function show(message, opts) {
    if (!message) return;
    opts = opts || {};
    var variant = opts.variant || 'info';
    var duration = typeof opts.duration === 'number' ? opts.duration : 3000;

    var node = ensureEl();
    node.className = 'natura-toast natura-toast--' + variant;
    node.textContent = message;

    // Force reflow so repeated shows retrigger the transition.
    void node.offsetWidth;
    node.classList.add('is-visible');

    clearTimeout(timer);
    timer = setTimeout(function () {
      node.classList.remove('is-visible');
    }, duration);
  }

  window.NaturaToast = { show: show };

  document.addEventListener('natura:mini-cart:error', function (e) {
    var msg = (e.detail && e.detail.message) || 'A apărut o eroare.';
    show(msg, { variant: 'error', duration: 4000 });
  });
})();
