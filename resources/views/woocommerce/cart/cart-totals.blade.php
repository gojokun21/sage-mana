{{--
  Cart totals. Matches legacy markup so the existing CSS selectors keep working.
--}}

@php
  defined('ABSPATH') || exit;
@endphp

<div class="cart_totals {{ WC()->customer->has_calculated_shipping() ? 'calculated_shipping' : '' }}">
  @php do_action('woocommerce_before_cart_totals') @endphp

  <h2>{{ __('Cart totals', 'woocommerce') }}</h2>

  <table cellspacing="0" class="shop_table shop_table_responsive">
    <tr class="cart-subtotal">
      <th>{{ __('Subtotal', 'woocommerce') }}</th>
      <td data-title="{{ esc_attr__('Subtotal', 'woocommerce') }}">{!! WC()->cart->get_cart_subtotal() !!}</td>
    </tr>

    @foreach (WC()->cart->get_coupons() as $code => $coupon)
      <tr class="cart-discount coupon-{{ esc_attr(sanitize_title($code)) }}">
        <th>{!! wc_cart_totals_coupon_label($coupon) !!}</th>
        <td data-title="{{ esc_attr(wc_cart_totals_coupon_label($coupon, false)) }}">@php wc_cart_totals_coupon_html($coupon) @endphp</td>
      </tr>
    @endforeach

    @if (WC()->cart->needs_shipping())
      <tr class="shipping">
        <th>{{ __('Livrare', 'sage') }}</th>
        <td data-title="{{ esc_attr__('Livrare', 'sage') }}">
          @php
            $shipping_total = WC()->cart->get_cart_shipping_total();
          @endphp
          {!! $shipping_total ?: __('Se calculează la checkout', 'sage') !!}
        </td>
      </tr>
    @endif

    @foreach (WC()->cart->get_fees() as $fee)
      <tr class="fee">
        <th>{{ esc_html($fee->name) }}</th>
        <td data-title="{{ esc_attr($fee->name) }}">@php wc_cart_totals_fee_html($fee) @endphp</td>
      </tr>
    @endforeach

    @if (wc_tax_enabled() && ! WC()->cart->display_prices_including_tax())
      @php
        $taxable_address = WC()->customer->get_taxable_address();
        $estimated_text = '';
        if (WC()->customer->is_customer_outside_base() && ! WC()->customer->has_calculated_shipping()) {
          $estimated_text = sprintf(' <small>' . esc_html__('(estimated for %s)', 'woocommerce') . '</small>', WC()->countries->estimated_for_prefix($taxable_address[0]) . WC()->countries->countries[$taxable_address[0]]);
        }
      @endphp

      @if (get_option('woocommerce_tax_total_display') === 'itemized')
        @foreach (WC()->cart->get_tax_totals() as $code => $tax)
          <tr class="tax-rate tax-rate-{{ esc_attr(sanitize_title($code)) }}">
            <th>{!! esc_html($tax->label) . $estimated_text !!}</th>
            <td data-title="{{ esc_attr($tax->label) }}">{!! wp_kses_post($tax->formatted_amount) !!}</td>
          </tr>
        @endforeach
      @else
        <tr class="tax-total">
          <th>{!! esc_html(WC()->countries->tax_or_vat()) . $estimated_text !!}</th>
          <td data-title="{{ esc_attr(WC()->countries->tax_or_vat()) }}">@php wc_cart_totals_taxes_total_html() @endphp</td>
        </tr>
      @endif
    @endif

    @php do_action('woocommerce_cart_totals_before_order_total') @endphp

    <tr class="order-total">
      <th>{{ __('Total', 'woocommerce') }}</th>
      <td data-title="{{ esc_attr__('Total', 'woocommerce') }}">@php wc_cart_totals_order_total_html() @endphp</td>
    </tr>

    @php do_action('woocommerce_cart_totals_after_order_total') @endphp
  </table>

  <div class="wc-proceed-to-checkout">
    @php do_action('woocommerce_proceed_to_checkout') @endphp
  </div>

  @php do_action('woocommerce_after_cart_totals') @endphp
</div>
