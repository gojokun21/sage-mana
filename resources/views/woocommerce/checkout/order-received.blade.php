{{--
  "Order received" message (inside the thankyou page).
  @see https://woocommerce.com/document/template-structure/
  @version 8.8.0

  Vars:
    $order  WC_Order|false
--}}

@php
  defined('ABSPATH') || exit;

  $message = apply_filters(
    'woocommerce_thankyou_order_received_text',
    esc_html(__('Thank you. Your order has been received.', 'woocommerce')),
    $order
  );
@endphp

<p class="woocommerce-notice woocommerce-notice--success woocommerce-thankyou-order-received">
  {!! $message !!}
</p>
