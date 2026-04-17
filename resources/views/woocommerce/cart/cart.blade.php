{{--
  Cart page.
  Ported from mana-naturii/woocommerce/cart/cart.php (WC template v10.1.0 baseline).
--}}

@php
  defined('ABSPATH') || exit;

  $cart_total = WC()->cart->get_subtotal();
  $missing = max(0, \App\FREE_SHIPPING_MIN - $cart_total);

  $recommended = \App\cart_recommended_products();
  $cart_has_upsell = \App\cart_has_upsell_product();
  $upsell_percent = \App\cart_upsell_percent();
  $upsell_nonce = wp_create_nonce(\App\UPSELL_NONCE);

  $applied_coupons = WC()->cart->get_applied_coupons();
  $has_coupon = ! empty($applied_coupons);

  do_action('woocommerce_before_cart');
@endphp

{!! \App\render_checkout_steps() !!}

<form class="woocommerce-cart-form" action="{{ esc_url(wc_get_cart_url()) }}" method="post">
  @php do_action('woocommerce_before_cart_table') @endphp

  <table class="shop_table shop_table_responsive cart woocommerce-cart-form__contents" cellspacing="0">
    <thead>
      <tr>
        <th class="product-thumbnail"><span class="screen-reader-text">{{ __('Thumbnail image', 'woocommerce') }}</span></th>
        <th scope="col" class="product-name">{{ __('Product', 'woocommerce') }}</th>
        <th scope="col" class="product-price">{{ __('Price', 'woocommerce') }}</th>
        <th scope="col" class="product-quantity">{{ __('Quantity', 'woocommerce') }}</th>
        <th scope="col" class="product-subtotal">{{ __('Subtotal', 'woocommerce') }}</th>
        <th class="product-remove"><span class="screen-reader-text">{{ __('Remove item', 'woocommerce') }}</span></th>
      </tr>
    </thead>
    <tbody data-cart-items>
      @php do_action('woocommerce_before_cart_contents') @endphp

      @include('partials.cart.cart-items')

      @php do_action('woocommerce_cart_contents') @endphp

      <tr>
        <td colspan="6" class="actions">
          <button type="submit" class="button" name="update_cart" value="{{ esc_attr__('Update cart', 'woocommerce') }}">
            {{ __('Update cart', 'woocommerce') }}
          </button>

          @php do_action('woocommerce_cart_actions') @endphp

          {!! wp_nonce_field('woocommerce-cart', 'woocommerce-cart-nonce', true, false) !!}
        </td>
      </tr>

      @php do_action('woocommerce_after_cart_contents') @endphp
    </tbody>
  </table>
  @php do_action('woocommerce_after_cart_table') @endphp
</form>

@include('partials.cart.free-shipping-box', ['missing' => $missing])

@if (! empty($recommended))
  @include('partials.cart.recommended-slider', [
    'recommended' => $recommended,
    'cart_has_upsell' => $cart_has_upsell,
    'upsell_percent' => $upsell_percent,
    'upsell_nonce' => $upsell_nonce,
  ])
@endif

<div class="cart_bottom_grid">
  <div class="cart_coupon_area">
    <h3>{{ __('Cod de reducere', 'sage') }}</h3>

    @if (wc_coupons_enabled())
      @include('partials.cart.coupon-form', [
        'has_coupon' => $has_coupon,
        'applied_coupon' => $has_coupon ? $applied_coupons[0] : '',
      ])
    @endif
  </div>

  @php do_action('woocommerce_before_cart_collaterals') @endphp

  <div class="cart_left_area">
    <div class="cart-collaterals">
      @php do_action('woocommerce_cart_collaterals') @endphp
    </div>
  </div>
</div>

@php do_action('woocommerce_after_cart') @endphp
