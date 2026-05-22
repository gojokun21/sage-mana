{{--
  Promo countdown în bara de navigare (desktop): „Reduceri la pachete -10% expiră în …".
  Link către /pachete/. Randat din sections/header.blade.php DOAR cât timp campania
  e activă (\App\bf_is_live).

  Clasa .bf-countdown e doar cârligul pentru bf-countdown.js (care umple
  data-hours/min/sec și adaugă .is-expired la final). Stilul e 100% în app.css
  (.nav-promo, global), ca să meargă pe orice pagină — nu doar produs/home, unde
  se încarcă bf-countdown.css. La expirare: .is-expired → display:none.

  Variabile: $deadlineMs (int), $percent (int), $rootClass (opțional: 'nav-promo' în
  bară desktop / 'promo-strip' ca bandă pe mobil — vezi layouts/app.blade.php).
--}}
@php $rootClass = $rootClass ?? 'nav-promo'; @endphp
<a href="{{ esc_url(home_url('/pachete/')) }}"
   class="bf-countdown {{ $rootClass }}"
   data-deadline="{{ $deadlineMs }}"
   aria-label="{{ esc_attr(sprintf(__('Reduceri la pachete -%d%%, vezi pachetele', 'sage'), $percent)) }}">
  <svg class="nav-promo__icon" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
    <path d="M13 2 L4 14 H11 L11 22 L20 10 H13 Z"/>
  </svg>
  <span class="nav-promo__text">Reduceri la pachete <strong>-{{ $percent }}%</strong> · expiră în</span>
  <span class="nav-promo__time" aria-hidden="true">
    <span data-hours>00</span><span class="nav-promo__u">h</span><span class="nav-promo__sep">:</span><span data-min>00</span><span class="nav-promo__u">min</span><span class="nav-promo__sep">:</span><span data-sec>00</span><span class="nav-promo__u">sec</span>
  </span>
</a>
