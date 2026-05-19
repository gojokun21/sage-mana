/**
 * Newsletter Landing Page — form handler.
 *
 * Lazy-loaded from resources/js/app.js when `#lpForm` is present in the DOM
 * (i.e. the visitor is on the LP using template-newsletter-lp).
 *
 * Submits to the same AJAX endpoint as the welcome popup
 * (`natura_popup_subscribe`, see app/newsletter-popup.php). Endpointul face
 * push subscriber catre TheMarketer, care livreaza codul -10% in emailul de
 * welcome — de aceea nu afisam codul inline aici.
 *
 * Config is provided inline by the template via `window.natura_newsletter_lp`.
 */

(function () {
  var form = document.getElementById('lpForm');
  if (!form) return;

  var cfg = window.natura_newsletter_lp;
  if (!cfg || !cfg.ajax_url) return;

  var nameEl    = document.getElementById('lpName');
  var emailEl   = document.getElementById('lpEmail');
  var hpEl      = document.getElementById('lpWebsite');
  var agreeEl   = document.getElementById('lpAgree');
  var submitBtn = form.querySelector('.lp-submit');
  var submitLbl = submitBtn ? submitBtn.querySelector('.lp-submit__label') : null;
  var msgEl     = document.getElementById('lpFormMsg');
  var toastEl   = document.getElementById('lpToast');
  var toastMsg  = toastEl ? toastEl.querySelector('.lp-toast__msg') : null;
  var successEl = document.getElementById('lpSuccess');

  // Cache the original submit label once.
  if (submitLbl) submitLbl.dataset.original = submitLbl.textContent;

  function isValidEmail(value) {
    // Same shape WC uses — good enough for client validation; server is
    // the source of truth via is_email().
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value);
  }

  function setMessage(text, tone) {
    if (!msgEl) return;
    msgEl.textContent = text || '';
    msgEl.className = 'lp-form__msg' + (tone === 'info' ? ' is-info' : '');
  }

  function setLoading(loading) {
    if (!submitBtn) return;
    submitBtn.disabled = loading;
    if (submitLbl) {
      submitLbl.textContent = loading
        ? cfg.i18n.working
        : submitLbl.dataset.original;
    }
  }

  function showToast(text) {
    if (!toastEl) return;
    if (toastMsg && text) toastMsg.textContent = text;
    toastEl.classList.add('on');
    setTimeout(function () { toastEl.classList.remove('on'); }, 3500);
  }

  function showSuccess() {
    // Hide the form, reveal the success block.
    form.style.display = 'none';
    if (successEl) successEl.hidden = false;
    showToast(cfg.i18n.toast_success);
  }

  form.addEventListener('submit', function (e) {
    e.preventDefault();
    setMessage('');

    var name  = (nameEl && nameEl.value || '').trim();
    var email = (emailEl && emailEl.value || '').trim();

    if (!name || !email) {
      setMessage(cfg.i18n.missing);
      return;
    }

    if (!isValidEmail(email)) {
      setMessage(cfg.i18n.invalid_email);
      if (emailEl) emailEl.focus();
      return;
    }

    if (agreeEl && !agreeEl.checked) {
      setMessage(cfg.i18n.consent);
      return;
    }

    setLoading(true);

    var body = new FormData();
    body.append('action', cfg.action || 'natura_popup_subscribe');
    body.append('nonce',  cfg.nonce);
    body.append('name',   name);
    body.append('email',  email);
    body.append('website', hpEl ? hpEl.value : '');

    fetch(cfg.ajax_url, { method: 'POST', credentials: 'same-origin', body: body })
      .then(function (r) { return r.json(); })
      .then(function (res) {
        setLoading(false);

        if (res && res.success) {
          showSuccess();
          return;
        }

        var serverMsg = res && res.data && res.data.message;
        setMessage(serverMsg || cfg.i18n.error);
      })
      .catch(function () {
        setLoading(false);
        setMessage(cfg.i18n.error);
      });
  });
})();

/**
 * Final-CTA inline email form — bridges to the main signup form.
 * Pre-fills the email field, scrolls into view and focuses the name input.
 */
(function () {
  var ctaForm = document.getElementById('lpCtaForm');
  if (!ctaForm) return;

  ctaForm.addEventListener('submit', function (e) {
    e.preventDefault();
    var emailInput = ctaForm.querySelector('input[type="email"]');
    var email = emailInput ? emailInput.value.trim() : '';

    var targetSel = ctaForm.getAttribute('data-signup') || '#lp-signup';
    var target = document.querySelector(targetSel);
    var mainEmail = document.getElementById('lpEmail');
    var mainName = document.getElementById('lpName');

    if (mainEmail && email) mainEmail.value = email;

    if (target && target.scrollIntoView) {
      target.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }

    window.setTimeout(function () {
      if (mainName) mainName.focus();
      else if (mainEmail) mainEmail.focus();
    }, 500);
  });
})();
