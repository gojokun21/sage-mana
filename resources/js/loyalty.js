/**
 * Pagina „Cod fidelitate" (tab Contul meu) — copy-to-clipboard + acordeon FAQ.
 * Lazy-load din app.js pe `.loyalty-tab`.
 */

let toastEl = null;
let toastTimer = null;

function showToast(msg) {
  if (!toastEl) {
    toastEl = document.createElement('div');
    toastEl.className = 'mn-loyalty-toast';
    Object.assign(toastEl.style, {
      position: 'fixed',
      bottom: '32px',
      left: '50%',
      transform: 'translateX(-50%) translateY(20px)',
      background: '#0f3a1c',
      color: '#fff',
      padding: '12px 22px',
      borderRadius: '999px',
      fontFamily: 'inherit',
      fontSize: '13.5px',
      fontWeight: '600',
      boxShadow: '0 14px 30px -10px rgba(15,42,29,.45)',
      opacity: '0',
      pointerEvents: 'none',
      transition: 'all .25s ease',
      zIndex: '2000',
    });
    document.body.appendChild(toastEl);
  }
  toastEl.textContent = msg;
  requestAnimationFrame(() => {
    toastEl.style.opacity = '1';
    toastEl.style.transform = 'translateX(-50%) translateY(0)';
  });
  clearTimeout(toastTimer);
  toastTimer = setTimeout(() => {
    toastEl.style.opacity = '0';
    toastEl.style.transform = 'translateX(-50%) translateY(20px)';
  }, 2000);
}

function copyText(text) {
  if (navigator.clipboard && window.isSecureContext) {
    return navigator.clipboard.writeText(text);
  }
  return new Promise((resolve) => {
    const ta = document.createElement('textarea');
    ta.value = text;
    ta.style.position = 'fixed';
    ta.style.opacity = '0';
    document.body.appendChild(ta);
    ta.select();
    try { document.execCommand('copy'); } catch (e) { /* noop */ }
    document.body.removeChild(ta);
    resolve();
  });
}

// Copy buttons
document.querySelectorAll('.loyalty-tab [data-mn-copy]').forEach((btn) => {
  btn.addEventListener('click', () => {
    const text = btn.getAttribute('data-mn-copy') || '';
    const msg = btn.getAttribute('data-mn-toast') || 'Copiat';
    copyText(text).then(() => showToast(msg), () => showToast(msg));
  });
});

// FAQ accordion
document.querySelectorAll('.loyalty-tab [data-mn-faq]').forEach((item) => {
  const q = item.querySelector('.faq-q');
  if (q) {
    q.addEventListener('click', () => item.classList.toggle('open'));
  }
});
