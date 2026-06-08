{{-- Funnel 4 pași — Coșul tău (activ) → Livrare → Plata → Confirmare. Toate hardcodate. --}}

<div class="cart-funnel">
  <div class="cart-funnel-inner">
    <span class="cart-funnel__step is-active">
      <span class="cart-funnel__n">1</span>{{ __('Coșul tău', 'sage') }}
    </span>
    <span class="cart-funnel__arrow" aria-hidden="true">›</span>
    <span class="cart-funnel__step">
      <span class="cart-funnel__n">2</span>{{ __('Livrare', 'sage') }}
    </span>
    <span class="cart-funnel__arrow" aria-hidden="true">›</span>
    <span class="cart-funnel__step">
      <span class="cart-funnel__n">3</span>{{ __('Plata', 'sage') }}
    </span>
    <span class="cart-funnel__arrow" aria-hidden="true">›</span>
    <span class="cart-funnel__step">
      <span class="cart-funnel__n">4</span>{{ __('Confirmare', 'sage') }}
    </span>
  </div>
</div>
