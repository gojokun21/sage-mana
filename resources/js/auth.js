/* ==================== AUTH (login modal + standalone login page) ====================
 *
 *  1. Header login modal (#natura-login-modal) — opens via `.open-login-modal`,
 *     submits through AJAX (endpoint `natura_login`).
 *
 *  2. Standalone My Account login/register page — WC-native POST submit.
 *
 *  Shared behavior (tabs, password visibility, strength meter) is scoped to any
 *  `[data-natura-auth-group]` wrapper. Modal-specific behavior runs only when
 *  the modal element is present.
 */

(function () {
  /* ---------------- Shared behavior (every auth group) ---------------- */

  document.querySelectorAll('[data-natura-auth-group]').forEach(function (group) {
    bindTabs(group);
    bindPasswordToggles(group);
    bindStrengthMeter(group);
    bindPasswordMatch(group);
  });

  function bindTabs(group) {
    var tabs = group.querySelectorAll('[data-natura-auth-tab]');
    var panels = group.querySelectorAll('.natura-auth-panel');
    if (!tabs.length) return;

    function switchTab(name) {
      tabs.forEach(function (t) {
        var active = t.getAttribute('data-natura-auth-tab') === name;
        t.classList.toggle('is-active', active);
        t.setAttribute('aria-selected', active ? 'true' : 'false');
      });
      panels.forEach(function (p) {
        var active = p.getAttribute('data-natura-auth-panel') === name;
        p.classList.toggle('is-active', active);
        p.setAttribute('aria-hidden', active ? 'false' : 'true');
      });
    }

    tabs.forEach(function (tab) {
      tab.addEventListener('click', function () { switchTab(tab.getAttribute('data-natura-auth-tab')); });
    });

    group.querySelectorAll('[data-natura-switch-to]').forEach(function (link) {
      link.addEventListener('click', function (e) {
        e.preventDefault();
        switchTab(link.getAttribute('data-natura-switch-to'));
      });
    });
  }

  function bindPasswordToggles(group) {
    group.querySelectorAll('[data-natura-password-toggle]').forEach(function (btn) {
      btn.addEventListener('click', function () {
        var input = btn.parentElement.querySelector('input');
        if (!input) return;
        var isPass = input.type === 'password';
        input.type = isPass ? 'text' : 'password';
        btn.classList.toggle('is-revealed', isPass);
      });
    });
  }

  function bindStrengthMeter(group) {
    var input = group.querySelector('[data-natura-strength-input]');
    var bar = group.querySelector('[data-natura-strength-bar]');
    if (!input || !bar) return;

    input.addEventListener('input', function () {
      var s = strength(input.value);
      bar.style.width = s.percent + '%';
      bar.style.backgroundColor = s.color;
    });
  }

  function bindPasswordMatch(group) {
    var source = group.querySelector('[data-natura-strength-input]');
    var confirm = group.querySelector('[data-natura-password-match]');
    var msg = group.querySelector('[data-natura-password-match-msg]');
    if (!source || !confirm || !msg) return;

    function check() {
      if (!confirm.value) {
        msg.textContent = '';
        msg.className = 'natura-password-match';
        return;
      }
      if (confirm.value === source.value) {
        msg.textContent = '✓ Parolele se potrivesc';
        msg.className = 'natura-password-match is-match';
      } else {
        msg.textContent = '✕ Parolele nu se potrivesc';
        msg.className = 'natura-password-match is-mismatch';
      }
    }

    source.addEventListener('input', check);
    confirm.addEventListener('input', check);
  }

  function strength(pw) {
    var score = 0;
    if (pw.length >= 8) score += 25;
    if (/[a-z]/.test(pw)) score += 25;
    if (/[A-Z]/.test(pw)) score += 25;
    if (/[0-9]/.test(pw)) score += 15;
    if (/[^a-zA-Z0-9]/.test(pw)) score += 10;
    var color = '#ef4444';
    if (score >= 75) color = '#22c55e';
    else if (score >= 50) color = '#eab308';
    else if (score >= 25) color = '#f97316';
    return { percent: Math.min(score, 100), color: color };
  }

  /* ---------------- Modal-specific (open/close + AJAX login) ---------------- */

  var modal = document.getElementById('natura-login-modal');
  var cfg = window.natura_login;
  if (!modal || !cfg) return;

  var loginForm = document.getElementById('natura-login-form');
  var errorBox = modal.querySelector('[data-natura-login-error]');
  var submitBtn = modal.querySelector('[data-natura-login-submit]');
  var currentNonce = cfg.nonce;

  function open() {
    modal.removeAttribute('inert');
    modal.classList.add('is-visible');
    document.body.classList.add('natura-login-open');
    refreshNonce();
    var first = modal.querySelector('input[type="text"], input[type="email"]');
    if (first) setTimeout(function () { first.focus(); }, 120);
  }

  function close() {
    modal.classList.remove('is-visible');
    modal.setAttribute('inert', '');
    document.body.classList.remove('natura-login-open');
    if (errorBox) errorBox.classList.remove('is-visible');
  }

  document.addEventListener('click', function (e) {
    if (e.target.closest && e.target.closest('.open-login-modal')) {
      e.preventDefault();
      open();
      return;
    }
    if (e.target.closest && e.target.closest('[data-natura-login-close]')) {
      close();
    }
  });

  document.addEventListener('keyup', function (e) {
    if (e.key === 'Escape' && modal.classList.contains('is-visible')) close();
  });

  function refreshNonce() {
    var body = new URLSearchParams();
    body.append('action', 'natura_login_nonce');
    return fetch(cfg.ajax_url, {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      credentials: 'same-origin',
      body: body.toString(),
    })
      .then(function (r) { return r.json(); })
      .then(function (res) { if (res && res.success) currentNonce = res.data.nonce; })
      .catch(function () {});
  }

  if (loginForm) {
    loginForm.addEventListener('submit', function (e) {
      e.preventDefault();
      hideError();

      var username = loginForm.querySelector('[name="username"]').value.trim();
      var password = loginForm.querySelector('[name="password"]').value;
      var remember = loginForm.querySelector('[name="rememberme"]').checked ? '1' : '';

      if (!username || !password) {
        showError(cfg.i18n.empty);
        return;
      }

      setBusy(true);

      var body = new URLSearchParams();
      body.append('action', 'natura_login');
      body.append('security', currentNonce);
      body.append('username', username);
      body.append('password', password);
      body.append('remember', remember);

      fetch(cfg.ajax_url, {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        credentials: 'same-origin',
        body: body.toString(),
      })
        .then(function (r) { return r.json(); })
        .then(function (res) {
          if (res && res.success) {
            setSubmitText(cfg.i18n.success);
            location.reload();
          } else {
            showError((res && res.data && res.data.message) || cfg.i18n.error);
            setBusy(false);
          }
        })
        .catch(function () {
          showError(cfg.i18n.network);
          setBusy(false);
        });
    });
  }

  function setBusy(busy) {
    if (!submitBtn) return;
    submitBtn.disabled = busy;
    submitBtn.classList.toggle('is-loading', busy);
    setSubmitText(busy ? cfg.i18n.working : cfg.i18n.cta);
  }

  function setSubmitText(text) {
    var el = submitBtn ? submitBtn.querySelector('.natura-auth-submit__text') : null;
    if (el) el.textContent = text;
  }

  function showError(msg) {
    if (!errorBox) return;
    errorBox.textContent = msg;
    errorBox.classList.add('is-visible');
  }

  function hideError() {
    if (errorBox) errorBox.classList.remove('is-visible');
  }
})();
