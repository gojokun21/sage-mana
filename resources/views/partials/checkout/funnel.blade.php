{{-- Funnel 4 pași — Coșul tău (done) → Livrare (activ) → Plata → Confirmare.
     Vizual identic cu cart (partials/cart/funnel-steps), dar pasul 2 e activ. --}}

<div class="funnel">
  <div class="funnel-inner">
    <a href="{{ esc_url(wc_get_cart_url()) }}" class="step done">
      <span class="n" aria-hidden="true">✓</span>{{ __('Coșul tău', 'sage') }}
    </a>
    <span class="arrow" aria-hidden="true">›</span>
    <span class="step active">
      <span class="n">2</span>{{ __('Livrare', 'sage') }}
    </span>
    <span class="arrow" aria-hidden="true">›</span>
    <span class="step">
      <span class="n">3</span>{{ __('Plata', 'sage') }}
    </span>
    <span class="arrow" aria-hidden="true">›</span>
    <span class="step">
      <span class="n">4</span>{{ __('Confirmare', 'sage') }}
    </span>
  </div>
</div>
