{{--
  Hero cart: h1 "Coșul tău." + subline cu count + "Pagina este securizată" pe dreapta.
  Vars (from cart.blade.php):
    $count    int    număr de produse (WC()->cart->get_cart_contents_count())
    $subtotal string formatted subtotal HTML (wc_price)
--}}

<section class="cart-hero" aria-label="{{ esc_attr__('Coșul tău', 'sage') }}">
  <div class="cart-hero-inner">
    <div class="cart-hero-left">
      <h1>{{ __('Coșul', 'sage') }} <em>{{ __('tău.', 'sage') }}</em></h1>
      <p class="cart-hero-sub">
        <strong>{{ sprintf(_n('%d produs', '%d produse', $count, 'sage'), $count) }}</strong>
        ·
        {!! $subtotal !!}
      </p>
    </div>

    <span class="cart-hero-secure">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
      </svg>
      {{ __('Pagina este securizată · plata se face la pasul 3', 'sage') }}
    </span>
  </div>
</section>
