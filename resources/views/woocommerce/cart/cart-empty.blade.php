{{--
  Empty cart page.
--}}

@php
  defined('ABSPATH') || exit;

  do_action('woocommerce_cart_is_empty');

  $logo_id = get_theme_mod('custom_logo');
  $logo = $logo_id ? wp_get_attachment_image_src($logo_id, 'full') : null;
  $shop_page_id = wc_get_page_id('shop');
@endphp

@if ($shop_page_id > 0)
  <div class="return-to-shop">
    @if ($logo)
      <img src="{{ esc_url($logo[0]) }}" alt="{{ get_bloginfo('name') }}">
    @endif

    <p>{{ __('Nu este niciun produs în coș', 'sage') }}</p>

    <a class="button wc-backward"
       href="{{ esc_url(apply_filters('woocommerce_return_to_shop_redirect', wc_get_page_permalink('shop'))) }}">
      {{ esc_html(apply_filters('woocommerce_return_to_shop_text', __('Return to shop', 'woocommerce'))) }}
    </a>
  </div>
@endif
