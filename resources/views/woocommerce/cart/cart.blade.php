{{--
  Cart page — redesign după mockup `preferinte/Pagina Cos - Cart.html`.

  Structură:
    - .cart-page (scope CSS)
      - funnel-steps (4 pași, primul activ)
      - cart-hero (titlu + count subline + secure-line)
      - free-shipping-box (progress bar)
      - .cart-grid (2-col)
        - items column: .cart-items (data-cart-items) + cross-sell-card
        - summary aside: cart-summary (.cart_totals + CTAs + reassurance)
      - pre-checkout-faq

  AJAX hooks păstrate prin selectoarele/atributele:
    [data-cart-item-key], [data-cart-item-remove], [data-cart-items],
    [data-coupon-shell], .qty-stepper__input, .cart_totals, .free-shipping-box.
--}}

@php
  defined('ABSPATH') || exit;

  $cart_subtotal_raw = WC()->cart->get_subtotal();
  $missing = max(0, \App\FREE_SHIPPING_MIN - $cart_subtotal_raw);
  $cart_count = WC()->cart->get_cart_contents_count();
  $subtotal_html = WC()->cart->get_cart_subtotal();

  $recommended = \App\cart_recommended_products();
  $cart_has_upsell = \App\cart_has_upsell_product();
  $upsell_percent = \App\cart_upsell_percent();
  $upsell_nonce = wp_create_nonce(\App\UPSELL_NONCE);

  do_action('woocommerce_before_cart');
@endphp

<div class="cart-page">
  @include('partials.cart.funnel-steps')

  @include('partials.cart.cart-hero', [
    'count' => $cart_count,
    'subtotal' => $subtotal_html,
  ])

  @include('partials.cart.free-shipping-box', ['missing' => $missing])

  <section class="cart-content">
    {{-- NOTE: was <form class="woocommerce-cart-form">, dar coupon form-ul AJAX
         (#mn-ajax-coupon-form) e nested în summary → browserul strip-uia form-ul
         intern (nested forms = invalid HTML). Schimbăm pe <div> — cart.js folosește
         doar selectorul de clasă, nu form.submit(). Hidden update_cart button rămâne
         pentru orice plugin care îl caută, dar nu mai există fallback non-AJAX. --}}
    <div class="woocommerce-cart-form cart-grid" data-cart-form>
      @php do_action('woocommerce_before_cart_table') @endphp

      <div class="cart-col-items">
        <div class="cart-items" data-cart-items>
          @php do_action('woocommerce_before_cart_contents') @endphp

          @include('partials.cart.cart-items')

          @php do_action('woocommerce_cart_contents') @endphp
          @php do_action('woocommerce_after_cart_contents') @endphp
        </div>

        @if (! empty($recommended))
          @include('partials.cart.cross-sell-card', [
            'recommended' => $recommended,
            'cart_has_upsell' => $cart_has_upsell,
            'upsell_percent' => $upsell_percent,
            'upsell_nonce' => $upsell_nonce,
          ])
        @endif

        {{-- Nonce-ul WC păstrat pentru pluginele care îl verifică. --}}
        <div class="cart-hidden-actions" hidden aria-hidden="true">
          {!! wp_nonce_field('woocommerce-cart', 'woocommerce-cart-nonce', true, false) !!}
          @php do_action('woocommerce_cart_actions') @endphp
        </div>
      </div>

      <aside class="cart-col-summary">
        <div class="cart-summary-sticky">
          @include('partials.cart.summary')
        </div>
      </aside>

      @php do_action('woocommerce_after_cart_table') @endphp
    </div>
  </section>

  {{-- `woocommerce_cart_collaterals` (WC default) re-renders cart-totals here
       — drop intenționat: avem totals în summary card și folosim propriul
       cross-sell ACF în coloana de items. Hook-ul `before_cart_collaterals` rămâne
       util pentru plugins care injectează ceva între cart și pre-checkout. --}}
  @php do_action('woocommerce_before_cart_collaterals') @endphp

  @include('partials.cart.pre-checkout-faq')
</div>

@php do_action('woocommerce_after_cart') @endphp
