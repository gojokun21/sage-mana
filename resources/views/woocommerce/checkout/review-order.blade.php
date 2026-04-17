{{--
  Checkout order review table. Re-rendered via WC AJAX on updated_checkout.
  @see https://woocommerce.com/document/template-structure/
  @version 5.2.0
--}}

@php defined('ABSPATH') || exit; @endphp

<table class="shop_table woocommerce-checkout-review-order-table">
  <thead>
    <tr>
      <th class="product-name">{{ __('Product', 'woocommerce') }}</th>
      <th class="product-total">{{ __('Subtotal', 'woocommerce') }}</th>
    </tr>
  </thead>
  <tbody>
    @php do_action('woocommerce_review_order_before_cart_contents') @endphp

    @foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item)
      @php
        $_product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);

        if (! ($_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters('woocommerce_checkout_cart_item_visible', true, $cart_item, $cart_item_key))) {
          continue;
        }

        // Skip bundled child items (WC Product Bundles) — the parent bundle
        // already represents them with the aggregated price.
        $is_bundled_child = function_exists('wc_pb_is_bundled_cart_item')
          ? wc_pb_is_bundled_cart_item($cart_item)
          : ! empty($cart_item['bundled_by']);
        if ($is_bundled_child) {
          continue;
        }
      @endphp

      <tr class="{{ esc_attr(apply_filters('woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key)) }}">
        <td class="product-name">
          <div class="checkout-product-item">
            <div class="checkout-product-image">
              {!! $_product->get_image('woocommerce_thumbnail') !!}
            </div>
            <div class="checkout-product-info">
              <span class="name">
                {!! wp_kses_post(apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key)) !!}
              </span>
              <span class="qty">
                {!! apply_filters(
                  'woocommerce_checkout_cart_item_quantity',
                  '<strong class="product-quantity">' . sprintf('&times; %s', (int) $cart_item['quantity']) . '</strong>',
                  $cart_item,
                  $cart_item_key
                ) !!}
              </span>
              {!! wc_get_formatted_cart_item_data($cart_item) !!}
            </div>
          </div>
        </td>
        <td class="product-total">
          {!! apply_filters('woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal($_product, $cart_item['quantity']), $cart_item, $cart_item_key) !!}
        </td>
      </tr>
    @endforeach

    @php do_action('woocommerce_review_order_after_cart_contents') @endphp
  </tbody>
  <tfoot>
    <tr class="cart-subtotal">
      <th>{{ __('Subtotal', 'woocommerce') }}</th>
      <td>{!! WC()->cart->get_cart_subtotal() !!}</td>
    </tr>

    @foreach (WC()->cart->get_coupons() as $code => $coupon)
      <tr class="cart-discount coupon-{{ esc_attr(sanitize_title($code)) }}">
        <th>{!! wc_cart_totals_coupon_label($coupon) !!}</th>
        <td>@php wc_cart_totals_coupon_html($coupon) @endphp</td>
      </tr>
    @endforeach

    @if (WC()->cart->needs_shipping() && WC()->cart->show_shipping())
      @php
        $shipping_total = (float) WC()->cart->get_shipping_total();
        $shipping_tax = (float) WC()->cart->get_shipping_tax();
        $total = $shipping_total + $shipping_tax;
      @endphp
      <tr class="shipping">
        <th>{{ __('Livrare', 'sage') }}</th>
        <td>
          @if ($total > 0)
            {!! wc_price($total) !!}
          @else
            <span class="free-shipping">{{ __('Livrare gratuită', 'sage') }}</span>
          @endif
        </td>
      </tr>
    @endif

    @foreach (WC()->cart->get_fees() as $fee)
      <tr class="fee">
        <th>{{ esc_html($fee->name) }}</th>
        <td>@php wc_cart_totals_fee_html($fee) @endphp</td>
      </tr>
    @endforeach

    @if (wc_tax_enabled() && ! WC()->cart->display_prices_including_tax())
      @if (get_option('woocommerce_tax_total_display') === 'itemized')
        @foreach (WC()->cart->get_tax_totals() as $code => $tax)
          <tr class="tax-rate tax-rate-{{ esc_attr(sanitize_title($code)) }}">
            <th>{{ esc_html($tax->label) }}</th>
            <td>{!! wp_kses_post($tax->formatted_amount) !!}</td>
          </tr>
        @endforeach
      @else
        <tr class="tax-total">
          <th>{{ esc_html(WC()->countries->tax_or_vat()) }}</th>
          <td>@php wc_cart_totals_taxes_total_html() @endphp</td>
        </tr>
      @endif
    @endif

    @php do_action('woocommerce_review_order_before_order_total') @endphp

    <tr class="order-total">
      <th>{{ __('Total', 'woocommerce') }}</th>
      <td>@php wc_cart_totals_order_total_html() @endphp</td>
    </tr>

    @php do_action('woocommerce_review_order_after_order_total') @endphp
  </tfoot>
</table>
