{{-- 3 reassurance items in sub-totals: garanție / livrare / plată. Toate hardcodate. --}}

<div class="cart-reassure">
  <div class="cart-reassure__item">
    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
      <path d="M9 12l2 2 4-4"/>
      <circle cx="12" cy="12" r="10"/>
    </svg>
    <span>
      <strong>{{ __('14 zile garanție · doar sigilat', 'sage') }}</strong>
      {{ __('Returnezi produsele neîncepute și primești banii înapoi.', 'sage') }}
    </span>
  </div>

  <div class="cart-reassure__item">
    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
      <rect x="3" y="8" width="15" height="10" rx="1"/>
      <path d="M18 11h2l3 3v4h-5"/>
      <circle cx="7" cy="20" r="1.5"/>
      <circle cx="18" cy="20" r="1.5"/>
    </svg>
    <span>
      <strong>{{ __('Livrare 24–48h', 'sage') }}</strong>
      {{ __('Prin Sameday sau FAN Courier, în toată țara.', 'sage') }}
    </span>
  </div>

  <div class="cart-reassure__item">
    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
      <rect x="2" y="6" width="20" height="12" rx="2"/>
      <path d="M2 10h20"/>
    </svg>
    <span>
      <strong>{{ __('Plata la livrare disponibilă', 'sage') }}</strong>
      {{ __('Sau card / Apple Pay / Google Pay la pasul 3.', 'sage') }}
    </span>
  </div>
</div>
