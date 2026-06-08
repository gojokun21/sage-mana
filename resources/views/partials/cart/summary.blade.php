{{--
  Summary card sticky pe coloana dreaptă.
  - Header "Sumar comandă"
  - .cart_totals wrapper (înăuntru: subtotal, shipping, coupon row, coupon-shell, divider, total)
    — păstrăm wrapper-ul .cart_totals pentru replaceWith JS în cart.js.
  - În afara .cart_totals: CTAs (checkout + continue) și reassurance items.
--}}

@php
  $has_coupon = ! empty(WC()->cart->get_applied_coupons());
  $applied_coupons = WC()->cart->get_applied_coupons();
@endphp

<div class="cart-summary">
  <h3 class="cart-summary__title">{{ __('Sumar comandă', 'sage') }}</h3>

  @include('woocommerce.cart.cart-totals')

  <a class="cart-summary__checkout" href="{{ esc_url(wc_get_checkout_url()) }}">
    {{ __('Mergi la checkout', 'sage') }}
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
      <path d="M5 12h14"/>
      <path d="m12 5 7 7-7 7"/>
    </svg>
  </a>

  <a class="cart-summary__continue" href="{{ esc_url(wc_get_page_permalink('shop')) }}">
    {{ __('Continuă cumpărăturile', 'sage') }}
  </a>

  @include('partials.cart.reassurance')
</div>
