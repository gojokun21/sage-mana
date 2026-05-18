/**
 * Newsletter welcome popup — lazy-loaded when #mnOverlay exists.
 *
 * Flow:
 *   1. Wait 7s after DOMContentLoaded (or page is already ready) before
 *      considering whether to show. Bail out early if the user has dismissed
 *      or subscribed within the last 30 days (localStorage flag).
 *   2. Submit handler POSTs name + email to natura_popup_subscribe.
 *   3. On success, flip the right column to the success state and render
 *      the WC coupon code returned by the server. Clicking the code copies
 *      it to clipboard.
 *
 * Markup: resources/views/partials/newsletter-popup.blade.php
 * Server: app/newsletter-popup.php
 */

(function () {
  var overlay = document.getElementById('mnOverlay');
  if (!overlay) return;

  var cfg = window.natura_newsletter_popup;
  if (!cfg || !cfg.ajax_url) return;

  var STORAGE_KEY = 'natura_popup_state_v1';
  var STORAGE_TTL_DAYS = 30;
  var SHOW_DELAY_MS = 7000;

  function readStoredState() {
    try {
      var raw = localStorage.getItem(STORAGE_KEY);
      if (!raw) return null;
      var parsed = JSON.parse(raw);
      if (!parsed || !parsed.at) return null;
      var ageDays = (Date.now() - parsed.at) / (1000 * 60 * 60 * 24);
      if (ageDays > STORAGE_TTL_DAYS) return null;
      return parsed;
    } catch (e) {
      return null;
    }
  }

  function writeStoredState(status, extra) {
    try {
      var obj = Object.assign({ status: status, at: Date.now() }, extra || {});
      localStorage.setItem(STORAGE_KEY, JSON.stringify(obj));
    } catch (e) { /* private mode / quota — ignore */ }
  }

  // Bail out early if the user dismissed or subscribed recently.
  if (readStoredState()) return;

  var form = document.getElementById('mnPopForm');
  var nameEl = document.getElementById('mnPopName');
  var emailEl = document.getElementById('mnPopEmail');
  var hpEl = document.getElementById('mnPopWebsite');
  var msgEl = document.getElementById('mnPopMsg');
  var submitBtn = form ? form.querySelector('.mn-pop-submit') : null;
  var submitLabel = submitBtn ? submitBtn.querySelector('.mn-pop-submit-label') : null;
  var closeBtn = document.getElementById('mnCloseBtn');
  var declineBtn = document.getElementById('mnDeclineBtn');
  var rightCol = document.getElementById('mnPopR');
  var codeEl = document.getElementById('mnPopCode');
  var codeBtn = document.getElementById('mnPopCodeBtn');
  var copiedTag = document.getElementById('mnPopCopied');

  var shown = false;
  var lastFocus = null;

  function show() {
    if (shown) return;
    shown = true;
    lastFocus = document.activeElement;
    overlay.classList.add('on');
    overlay.setAttribute('aria-hidden', 'false');
    // Defer focus until the open transition is past its first frame so screen
    // readers announce the dialog title, not the underlying page.
    requestAnimationFrame(function () {
      if (nameEl) nameEl.focus();
    });
    document.addEventListener('keydown', onKeyDown);
  }

  function close(reason) {
    if (!shown) return;
    overlay.classList.remove('on');
    overlay.setAttribute('aria-hidden', 'true');
    document.removeEventListener('keydown', onKeyDown);
    if (lastFocus && typeof lastFocus.focus === 'function') {
      try { lastFocus.focus(); } catch (e) { /* element gone */ }
    }
    if (reason === 'dismiss' || reason === 'decline') {
      writeStoredState('dismissed');
    }
    shown = false;
  }

  function onKeyDown(e) {
    if (e.key === 'Escape') {
      close('dismiss');
    }
  }

  function setMessage(text, tone) {
    if (!msgEl) return;
    msgEl.textContent = text || '';
    msgEl.className = 'mn-pop-msg' + (tone === 'info' ? ' is-info' : '');
  }

  function setLoading(loading) {
    if (!submitBtn) return;
    submitBtn.disabled = loading;
    if (submitLabel) {
      submitLabel.textContent = loading
        ? cfg.i18n.working
        : (submitLabel.dataset.original || submitLabel.textContent);
      if (!submitLabel.dataset.original) {
        submitLabel.dataset.original = loading
          ? submitLabel.dataset.original || ''
          : submitLabel.textContent;
      }
    }
  }

  // Cache the original submit label once, before the first loading flip.
  if (submitLabel) submitLabel.dataset.original = submitLabel.textContent;

  function isValidEmail(value) {
    // Same shape WC uses — good enough for client validation, server is the
    // source of truth via is_email().
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value);
  }

  function showSuccess(code) {
    if (rightCol) rightCol.classList.add('is-success');
    var successEl = document.getElementById('mnPopSuccess');
    if (successEl) successEl.setAttribute('aria-hidden', 'false');
    if (codeEl) codeEl.textContent = code;
  }

  function copyCode() {
    if (!codeEl || !codeEl.textContent) return;
    var code = codeEl.textContent;
    var done = function () {
      if (!copiedTag) return;
      copiedTag.classList.add('show');
      setTimeout(function () { copiedTag.classList.remove('show'); }, 1500);
    };

    if (navigator.clipboard && navigator.clipboard.writeText) {
      navigator.clipboard.writeText(code).then(done).catch(function () {
        legacyCopy(code, done);
      });
    } else {
      legacyCopy(code, done);
    }
  }

  function legacyCopy(text, done) {
    try {
      var ta = document.createElement('textarea');
      ta.value = text;
      ta.style.position = 'fixed';
      ta.style.opacity = '0';
      document.body.appendChild(ta);
      ta.select();
      document.execCommand('copy');
      document.body.removeChild(ta);
      done();
    } catch (e) { /* clipboard unavailable */ }
  }

  /* ---------------- Event bindings ---------------- */

  if (closeBtn) closeBtn.addEventListener('click', function () { close('dismiss'); });
  if (declineBtn) declineBtn.addEventListener('click', function () { close('decline'); });

  // Backdrop click closes; click inside the popup card does not.
  overlay.addEventListener('click', function (e) {
    if (e.target === overlay) close('dismiss');
  });

  if (codeBtn) codeBtn.addEventListener('click', copyCode);

  if (form) {
    form.addEventListener('submit', function (e) {
      e.preventDefault();
      setMessage('');

      var name = (nameEl && nameEl.value || '').trim();
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

      setLoading(true);

      var body = new FormData();
      body.append('action', 'natura_popup_subscribe');
      body.append('nonce', cfg.nonce);
      body.append('name', name);
      body.append('email', email);
      body.append('website', hpEl ? hpEl.value : '');

      fetch(cfg.ajax_url, { method: 'POST', credentials: 'same-origin', body: body })
        .then(function (r) { return r.json(); })
        .then(function (res) {
          setLoading(false);
          if (res && res.success && res.data && res.data.code) {
            writeStoredState('subscribed', { code: res.data.code });
            showSuccess(res.data.code);
            return;
          }
          var message = (res && res.data && res.data.message) || cfg.i18n.error;
          setMessage(message);
          // If the email was already used, persist as dismissed so we don't
          // re-show the popup to the same person on next visit.
          if (res && res.data && res.data.already) {
            writeStoredState('dismissed');
          }
        })
        .catch(function () {
          setLoading(false);
          setMessage(cfg.i18n.error);
        });
    });
  }

  /* ---------------- Trigger ----------------
   * Delay starts after the page is interactive. We don't use 'load' because
   * that waits for every image, which is way later than the user needs the
   * popup. DOMContentLoaded (or readyState past 'loading') is the right hook. */

  function arm() {
    setTimeout(show, SHOW_DELAY_MS);
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', arm, { once: true });
  } else {
    arm();
  }
})();
