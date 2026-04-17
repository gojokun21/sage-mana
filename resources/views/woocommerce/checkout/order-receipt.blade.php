{{--
  Order receipt (gateway iframes / offline receipt page).
  @see https://woocommerce.com/document/template-structure/
  @version 3.2.0
--}}

@php defined('ABSPATH') || exit; @endphp

<ul class="order_details">
  <li class="order">
    {{ __('Order number:', 'woocommerce') }}
    <strong>{{ esc_html($order->get_order_number()) }}</strong>
  </li>
  <li class="date">
    {{ __('Date:', 'woocommerce') }}
    <strong>{{ esc_html(wc_format_datetime($order->get_date_created())) }}</strong>
  </li>
  <li class="total">
    {{ __('Total:', 'woocommerce') }}
    <strong>{!! wp_kses_post($order->get_formatted_order_total()) !!}</strong>
  </li>
  @if ($order->get_payment_method_title())
    <li class="method">
      {{ __('Payment method:', 'woocommerce') }}
      <strong>{!! wp_kses_post($order->get_payment_method_title()) !!}</strong>
    </li>
  @endif
</ul>

@php do_action('woocommerce_receipt_' . $order->get_payment_method(), $order->get_id()) @endphp

<div class="clear"></div>
