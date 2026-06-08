{{--
  Sumar comandă (order review) — redesign mockup `.summary`.
  Re-randat prin WC AJAX la fiecare `updated_checkout`, deci AICI stau
  produsele + totalurile (singura parte care se actualizează dinamic).
  Structura `.mini-items` + `.row`/`.total-row` înlocuiește tabelul WC implicit;
  WC doar înlocuiește innerHTML-ul lui `.woocommerce-checkout-review-order`,
  nu cere un <table>. Toată logica de totaluri (subtotal, cupoane, livrare,
  taxe ramburs/discount card, TVA, total) e păstrată identic.

  @see https://woocommerce.com/document/template-structure/
  @version 5.2.0
--}}

@php defined('ABSPATH') || exit; @endphp

{{--
  Wrapper-ul `.woocommerce-checkout-review-order-table` e OBLIGATORIU: WC
  actualizează sumarul prin AJAX (`update_order_review`) făcând
  `$('.woocommerce-checkout-review-order-table').replaceWith(fragment)`. Fără
  acest selector pe elementul rădăcină, totalurile ar rămâne neactualizate la
  schimbarea metodei de plată / aplicarea cuponului. Nu mai e un <table>, dar
  selectorul de clasă e tot ce contează pentru fragment replacement.
--}}
<div class="woocommerce-checkout-review-order-table">

<div class="mini-items">
  @php do_action('woocommerce_review_order_before_cart_contents') @endphp

  @foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item)
    @php
      $_product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);

      if (! ($_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters('woocommerce_checkout_cart_item_visible', true, $cart_item, $cart_item_key))) {
        continue;
      }

      // Skip bundled child items (WC Product Bundles) — pachetul părinte le
      // reprezintă deja cu prețul agregat.
      $is_bundled_child = function_exists('wc_pb_is_bundled_cart_item')
        ? wc_pb_is_bundled_cart_item($cart_item)
        : ! empty($cart_item['bundled_by']);
      if ($is_bundled_child) {
        continue;
      }
    @endphp

    <div class="mini-item {{ esc_attr(apply_filters('woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key)) }}">
      <div class="ph">
        {!! $_product->get_image('woocommerce_thumbnail') !!}
      </div>
      <div class="name">
        {!! wp_kses_post(apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key)) !!}
        <span class="qty">{{ __('cantitate', 'sage') }} × {{ (int) $cart_item['quantity'] }}</span>
        {!! wc_get_formatted_cart_item_data($cart_item) !!}
      </div>
      <span class="v">
        {!! apply_filters('woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal($_product, $cart_item['quantity']), $cart_item, $cart_item_key) !!}
      </span>
    </div>
  @endforeach

  @php do_action('woocommerce_review_order_after_cart_contents') @endphp
</div>

<div class="divider"></div>

<div class="totals">
  <div class="row">
    <span>{{ __('Subtotal', 'woocommerce') }}</span>
    <span class="v">{!! WC()->cart->get_cart_subtotal() !!}</span>
  </div>

  @foreach (WC()->cart->get_coupons() as $code => $coupon)
    <div class="row muted coupon-{{ esc_attr(sanitize_title($code)) }}">
      <span>{!! wc_cart_totals_coupon_label($coupon) !!}</span>
      <span class="v">@php wc_cart_totals_coupon_html($coupon) @endphp</span>
    </div>
  @endforeach

  @if (WC()->cart->needs_shipping() && WC()->cart->show_shipping())
    @php
      $shipping_total = (float) WC()->cart->get_shipping_total();
      $shipping_tax = (float) WC()->cart->get_shipping_tax();
      $shipping_sum = $shipping_total + $shipping_tax;
    @endphp
    <div class="row {{ $shipping_sum > 0 ? '' : 'free' }}">
      <span>{{ __('Livrare', 'sage') }}</span>
      <span class="v">
        @if ($shipping_sum > 0)
          {!! wc_price($shipping_sum) !!}
        @else
          {{ __('Gratuit', 'sage') }}
        @endif
      </span>
    </div>
  @endif

  @foreach (WC()->cart->get_fees() as $fee)
    <div class="row muted fee">
      <span>{{ esc_html($fee->name) }}</span>
      <span class="v">@php wc_cart_totals_fee_html($fee) @endphp</span>
    </div>
  @endforeach

  @if (wc_tax_enabled() && ! WC()->cart->display_prices_including_tax())
    @if (get_option('woocommerce_tax_total_display') === 'itemized')
      @foreach (WC()->cart->get_tax_totals() as $code => $tax)
        <div class="row muted tax-rate-{{ esc_attr(sanitize_title($code)) }}">
          <span>{{ esc_html($tax->label) }}</span>
          <span class="v">{!! wp_kses_post($tax->formatted_amount) !!}</span>
        </div>
      @endforeach
    @else
      <div class="row muted">
        <span>{{ esc_html(WC()->countries->tax_or_vat()) }}</span>
        <span class="v">@php wc_cart_totals_taxes_total_html() @endphp</span>
      </div>
    @endif
  @endif

  @php do_action('woocommerce_review_order_before_order_total') @endphp

  <div class="total-row">
    <span class="lbl">{{ __('Total', 'woocommerce') }}</span>
    <span class="v">@php wc_cart_totals_order_total_html() @endphp</span>
  </div>

  @php do_action('woocommerce_review_order_after_order_total') @endphp
</div>

</div>{{-- /.woocommerce-checkout-review-order-table --}}
