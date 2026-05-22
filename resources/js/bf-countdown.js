/* ==================== BLACK FRIDAY COUNTDOWN ====================
 * Numără invers până la expirarea reducerii. Gestionează TOATE elementele
 * `.bf-countdown[data-deadline]` din pagină cu un singur interval — atât
 * varianta mare de pe pagina de produs, cât și variantele compacte de pe
 * cardurile din slider-ele home.
 *
 * Lazy-importat din app.js doar când există `.bf-countdown` în DOM.
 *
 * `data-deadline` e un timestamp absolut (UTC, ms) calculat server-side în
 * fusul orar al site-ului, comparat cu Date.now() (tot UTC) — corect indiferent
 * de fusul vizitatorului. Discount-ul de preț se oprește singur la aceeași oră
 * (bf_is_live() în PHP), deci o reîncărcare după deadline nu mai arată timer-ul.
 */
(function () {
  var nodes = document.querySelectorAll('.bf-countdown[data-deadline]');
  if (!nodes.length) return;

  function pad(n) {
    return n < 10 ? '0' + n : '' + n;
  }

  var items = [];
  nodes.forEach(function (el) {
    var deadline = parseInt(el.getAttribute('data-deadline'), 10);
    if (!deadline) return;

    items.push({
      el: el,
      deadline: deadline,
      hours: el.querySelector('[data-hours]'),
      min: el.querySelector('[data-min]'),
      sec: el.querySelector('[data-sec]'),
      expired: el.querySelector('.bf-countdown__expired'),
      // Părțile „vii" ascunse la expirare (orice nu e nota de expirat).
      live: el.querySelectorAll(
        '.bf-countdown__head, .bf-countdown__clock, .bf-countdown__compact, .bf-countdown__icon'
      ),
      done: false,
    });
  });

  if (!items.length) return;

  var timer = null;

  function expire(item) {
    item.done = true;
    item.live.forEach(function (n) {
      n.hidden = true;
    });
    if (item.expired) item.expired.hidden = false;
    item.el.classList.add('is-expired');
  }

  function tick() {
    var now = Date.now();
    var allDone = true;

    items.forEach(function (item) {
      if (item.done) return;

      var diff = item.deadline - now;
      if (diff <= 0) {
        expire(item);
        return;
      }

      allDone = false;
      var t = Math.floor(diff / 1000);
      // Fără zile: orele includ și zilele convertite (pot depăși 24).
      if (item.hours) item.hours.textContent = pad(Math.floor(t / 3600));
      if (item.min) item.min.textContent = pad(Math.floor((t % 3600) / 60));
      if (item.sec) item.sec.textContent = pad(t % 60);
    });

    if (allDone && timer) clearInterval(timer);
  }

  tick();
  timer = setInterval(tick, 1000);
})();
