{{--
  Cart errors page.
  @see https://woocommerce.com/document/template-structure/
  @version 3.5.0
--}}

@php defined('ABSPATH') || exit; @endphp

<div class="woocommerce-cart-errors">
  <p>{{ __('There are some issues with the items in your cart. Please go back to the cart page and resolve these issues before checking out.', 'woocommerce') }}</p>

  @php do_action('woocommerce_cart_has_errors') @endphp

  <p>
    <a class="button wc-backward" href="{{ esc_url(wc_get_cart_url()) }}">
      {{ __('Return to cart', 'woocommerce') }}
    </a>
  </p>
</div>
