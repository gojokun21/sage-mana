{{--
  Checkout coupon form (toggle + form).
  @see https://woocommerce.com/document/template-structure/
  @version 9.8.0
--}}

@php
  defined('ABSPATH') || exit;

  if (! wc_coupons_enabled()) {
    return;
  }
@endphp

<div class="woocommerce-form-coupon-toggle">
  @php
    wc_print_notice(
      apply_filters(
        'woocommerce_checkout_coupon_message',
        esc_html__('Have a coupon?', 'woocommerce')
          . ' <a href="#" role="button" aria-label="' . esc_attr__('Enter your coupon code', 'woocommerce')
          . '" aria-controls="woocommerce-checkout-form-coupon" aria-expanded="false" class="showcoupon">'
          . esc_html__('Click here to enter your code', 'woocommerce') . '</a>'
      ),
      'notice'
    );
  @endphp
</div>

<form class="checkout_coupon woocommerce-form-coupon" method="post" style="display:none" id="woocommerce-checkout-form-coupon">
  <p class="form-row form-row-first">
    <label for="coupon_code" class="screen-reader-text">{{ __('Coupon:', 'woocommerce') }}</label>
    <input type="text" name="coupon_code" class="input-text" placeholder="{{ esc_attr__('Coupon code', 'woocommerce') }}" id="coupon_code" value="" />
  </p>

  <p class="form-row form-row-last">
    <button type="submit" class="button" name="apply_coupon" value="{{ esc_attr__('Apply coupon', 'woocommerce') }}">
      {{ __('Apply coupon', 'woocommerce') }}
    </button>
  </p>

  <div class="clear"></div>
</form>
