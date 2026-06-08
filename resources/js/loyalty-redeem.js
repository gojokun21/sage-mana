/**
 * „Plată cu puncte Constant" — apply/remove pe cart + checkout.
 * Lazy-load din app.js pe `[data-mn-loyalty-redeem]`.
 *
 * Aplică/elimină puncte în sesiunea WC (AJAX), apoi reîmprospătează totalurile:
 *  - checkout → trigger `update_checkout` (jQuery, încărcat oricum de WC);
 *  - cart     → reload (totalurile se recalculează la încărcare).
 */

const cfg = window.mn_loyalty_redeem;

function post(op, points) {
  const body = new URLSearchParams({
    action: 'mn_loyalty_redeem',
    nonce: cfg.nonce,
    op,
    points: String(points || 0),
  });

  return fetch(cfg.ajax_url, {
    method: 'POST',
    credentials: 'same-origin',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body,
  }).then((r) => r.json());
}

function refresh(context) {
  if (context === 'checkout' && window.jQuery) {
    window.jQuery(document.body).trigger('update_checkout');
  } else {
    window.location.reload();
  }
}

if (cfg) {
  document.addEventListener('click', (e) => {
    const panel = e.target.closest('[data-mn-loyalty-redeem]');
    if (!panel) return;

    const context = panel.getAttribute('data-context') || 'cart';

    if (e.target.closest('.mlr-apply')) {
      e.preventDefault();
      const input = panel.querySelector('.mlr-input');
      const points = input ? parseInt(input.value, 10) || 0 : 0;
      panel.classList.add('is-busy');
      post('apply', points)
        .then((res) => {
          if (res && res.success) {
            refresh(context);
          } else {
            panel.classList.remove('is-busy');
            if (res && res.data && res.data.message) {
              window.alert(res.data.message);
            }
          }
        })
        .catch(() => panel.classList.remove('is-busy'));
    }

    if (e.target.closest('.mlr-remove')) {
      e.preventDefault();
      panel.classList.add('is-busy');
      post('remove', 0)
        .then(() => refresh(context))
        .catch(() => panel.classList.remove('is-busy'));
    }
  });
}
