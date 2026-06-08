{{-- Promoții — bandă bundle deal (verde închis). --}}
<section class="bundle-band">
  <div class="bundle-inner">
    <div>
      <div class="eyebrow">{{ __('Combină și economisești', 'sage') }}</div>
      <h2>{{ __('Cumpără 3 pachete, economisești', 'sage') }} <em>{{ __('25%', 'sage') }}</em> {{ __('total.', 'sage') }}</h2>
      <p>{{ __('Funcționează pe orice combinație de pachete. Discount-ul se aplică automat la finalul comenzii. Fără cod, fără cupon, fără să soliciți ceva.', 'sage') }}</p>
    </div>
    <div class="bundle-cta-wrap">
      <a class="bundle-cta" href="{{ esc_url(home_url('/pachete/')) }}">{{ __('Vezi pachetele', 'sage') }}
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
      </a>
    </div>
  </div>
</section>
