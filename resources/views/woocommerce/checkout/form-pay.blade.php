{{--
  Pay for order form (used when customer re-visits a pending order).
  @see https://woocommerce.com/document/template-structure/
  @version 8.2.0
--}}

@php
  defined('ABSPATH') || exit;
  $totals = $order->get_order_item_totals();
@endphp

<form id="order_review" method="post" class="woocommerce-pay-for-order">
  <table class="shop_table">
    <thead>
      <tr>
        <th class="product-name">{{ __('Product', 'woocommerce') }}</th>
        <th class="product-quantity">{{ __('Qty', 'woocommerce') }}</th>
        <th class="product-total">{{ __('Totals', 'woocommerce') }}</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($order->get_items() as $item_id => $item)
        @php
          if (! apply_filters('woocommerce_order_item_visible', true, $item)) {
            continue;
          }
        @endphp
        <tr class="{{ esc_attr(apply_filters('woocommerce_order_item_class', 'order_item', $item, $order)) }}">
          <td class="product-name">
            {!! wp_kses_post(apply_filters('woocommerce_order_item_name', $item->get_name(), $item, false)) !!}
            @php
              do_action('woocommerce_order_item_meta_start', $item_id, $item, $order, false);
              wc_display_item_meta($item);
              do_action('woocommerce_order_item_meta_end', $item_id, $item, $order, false);
            @endphp
          </td>
          <td class="product-quantity">
            {!! apply_filters('woocommerce_order_item_quantity_html', ' <strong class="product-quantity">' . sprintf('&times;&nbsp;%s', esc_html($item->get_quantity())) . '</strong>', $item) !!}
          </td>
          <td class="product-subtotal">
            {!! $order->get_formatted_line_subtotal($item) !!}
          </td>
        </tr>
      @endforeach
    </tbody>
    <tfoot>
      @if ($totals)
        @foreach ($totals as $total)
          <tr>
            <th scope="row" colspan="2">{!! $total['label'] !!}</th>
            <td class="product-total">{!! $total['value'] !!}</td>
          </tr>
        @endforeach
      @endif
    </tfoot>
  </table>

  @php do_action('woocommerce_pay_order_before_payment') @endphp

  <div id="payment">
    @if ($order->needs_payment())
      <ul class="wc_payment_methods payment_methods methods">
        @if (! empty($available_gateways))
          @foreach ($available_gateways as $gateway)
            @php wc_get_template('checkout/payment-method.php', ['gateway' => $gateway]) @endphp
          @endforeach
        @else
          <li>
            @php
              wc_print_notice(
                apply_filters('woocommerce_no_available_payment_methods_message', esc_html__('Sorry, it seems that there are no available payment methods for your location. Please contact us if you require assistance or wish to make alternate arrangements.', 'woocommerce')),
                'notice'
              );
            @endphp
          </li>
        @endif
      </ul>
    @endif

    <div class="form-row">
      <input type="hidden" name="woocommerce_pay" value="1" />

      @php wc_get_template('checkout/terms.php') @endphp

      @php do_action('woocommerce_pay_order_before_submit') @endphp

      {!! apply_filters(
        'woocommerce_pay_order_button_html',
        '<button type="submit" class="button alt" id="place_order" value="' . esc_attr($order_button_text) . '" data-value="' . esc_attr($order_button_text) . '">' . esc_html($order_button_text) . '</button>'
      ) !!}

      @php do_action('woocommerce_pay_order_after_submit') @endphp

      {!! wp_nonce_field('woocommerce-pay', 'woocommerce-pay-nonce', true, false) !!}
    </div>
  </div>
</form>
