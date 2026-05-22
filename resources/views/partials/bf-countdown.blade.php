{{--
  Countdown reducere (10%) pe pagina de produs.

  Randat din app/black-friday.php (hook woocommerce_single_product_summary,
  prio 26) DOAR pe pachetele cu reducerea BF activă. `$deadlineMs` e un timestamp
  absolut (UTC, în milisecunde) calculat în fusul orar al site-ului, deci
  bf-countdown.js afișează corect timpul rămas indiferent de fusul vizitatorului.

  Variabile: $deadlineMs (int), $percent (int).
--}}
<div class="bf-countdown" id="bfCountdown" data-deadline="{{ $deadlineMs }}" role="timer">
  <div class="bf-countdown__head">
    <svg class="bf-countdown__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
      <circle cx="12" cy="12" r="9"/>
      <path d="M12 7v5l3 2"/>
    </svg>
    <span class="bf-countdown__text">Reducerea de <strong>{{ $percent }}%</strong> expiră în:</span>
  </div>

  <div class="bf-countdown__clock" aria-hidden="true">
    <div class="bf-countdown__unit">
      <span class="bf-countdown__num" data-hours>00</span>
      <span class="bf-countdown__lbl">ore</span>
    </div>
    <div class="bf-countdown__unit">
      <span class="bf-countdown__num" data-min>00</span>
      <span class="bf-countdown__lbl">min</span>
    </div>
    <div class="bf-countdown__unit">
      <span class="bf-countdown__num" data-sec>00</span>
      <span class="bf-countdown__lbl">sec</span>
    </div>
  </div>

  <p class="bf-countdown__expired" hidden>Promoția s-a încheiat.</p>
</div>
