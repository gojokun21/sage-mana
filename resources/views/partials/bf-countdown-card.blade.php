{{--
  Countdown compact pentru cardul de produs din slider-ele home.

  Randat din woocommerce/content-product.blade.php DOAR când:
    1. există flag-ul de context $GLOBALS['mn_card_countdown'] (setat doar de
       partials/home/products-slider.blade.php — deci NU apare pe arhivă /
       produse similare / căutare), ȘI
    2. produsul are reducerea BF activă (\App\bf_bundle_sale_price).

  Reutilizează bf-countdown.js prin clasa .bf-countdown + spans data-*.
  Variabile: $deadlineMs (int), $percent (int).
--}}
<div class="bf-countdown bf-countdown--card" data-deadline="{{ $deadlineMs }}" role="timer" aria-label="{{ esc_attr(sprintf(__('Reducere de %d%% — timp rămas', 'sage'), $percent)) }}">
  <svg class="bf-countdown__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
    <circle cx="12" cy="12" r="9"/>
    <path d="M12 7v5l3 2"/>
  </svg>
  <span class="bf-countdown__compact" aria-hidden="true">
    <span class="bf-countdown__cta">-{{ $percent }}% expiră în</span>
    <span class="bf-countdown__time">
      <span class="bf-countdown__grp"><span data-hours>00</span><span class="bf-countdown__tu">h</span></span>
      <span class="bf-countdown__tsep">:</span>
      <span class="bf-countdown__grp"><span data-min>00</span><span class="bf-countdown__tu">min</span></span>
      <span class="bf-countdown__tsep">:</span>
      <span class="bf-countdown__grp"><span data-sec>00</span><span class="bf-countdown__tu">sec</span></span>
    </span>
  </span>
  <span class="bf-countdown__expired" hidden>{{ __('Promoția s-a încheiat', 'sage') }}</span>
</div>
