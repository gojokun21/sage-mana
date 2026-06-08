{{--
  Cart totals — restructurat ca summary rows (per mockup), nu mai e <table>.
  Păstrăm wrapper-ul .cart_totals pentru ca cart.js să poată face replaceWith
  pe acest element la AJAX update.

  Structura: subtotal → coupons (per cod aplicat) → shipping → fees → taxes →
  coupon-shell (input/badge) → divider → order total → proceed_to_checkout hook.
--}}

@php
  defined('ABSPATH') || exit;

  $applied_coupons = WC()->cart->get_applied_coupons();
  $has_coupon = ! empty($applied_coupons);
  $shipping_total = WC()->cart->needs_shipping() ? WC()->cart->get_cart_shipping_total() : '';
  $shipping_is_free = $shipping_total && (stripos((string) $shipping_total, 'gratuit') !== false || stripos((string) $shipping_total, 'free') !== false || (float) wp_strip_all_tags((string) $shipping_total) === 0.0);
@endphp

<div class="cart_totals cart-totals {{ WC()->customer->has_calculated_shipping() ? 'calculated_shipping' : '' }}">
  @php do_action('woocommerce_before_cart_totals') @endphp

  <h2 class="screen-reader-text">{{ __('Sumar comandă', 'sage') }}</h2>

  @php
    $cart_count = WC()->cart->get_cart_contents_count();
  @endphp

  <div class="cart-totals__rows">
    <div class="cart-totals__row">
      <span>{{ __('Subtotal', 'sage') }} ({{ sprintf(_n('%d produs', '%d produse', $cart_count, 'sage'), $cart_count) }})</span>
      <span class="cart-totals__v">{!! WC()->cart->get_cart_subtotal() !!}</span>
    </div>

    @foreach (WC()->cart->get_coupons() as $code => $coupon)
      <div class="cart-totals__row cart-totals__row--discount cart-discount coupon-{{ esc_attr(sanitize_title($code)) }}">
        <span>{!! wc_cart_totals_coupon_label($coupon) !!}</span>
        <span class="cart-totals__v">@php wc_cart_totals_coupon_html($coupon) @endphp</span>
      </div>
    @endforeach

    @if (WC()->cart->needs_shipping())
      <div class="cart-totals__row cart-totals__row--shipping shipping {{ $shipping_is_free ? 'is-free' : '' }}">
        <span>{{ __('Transport (Sameday / FAN)', 'sage') }}</span>
        <span class="cart-totals__v">
          @if ($shipping_total)
            @if ($shipping_is_free)
              {{ __('Gratuit', 'sage') }}
            @else
              {!! $shipping_total !!}
            @endif
          @else
            {{ __('Se calculează la checkout', 'sage') }}
          @endif
        </span>
      </div>
    @endif

    @foreach (WC()->cart->get_fees() as $fee)
      <div class="cart-totals__row fee">
        <span>{{ esc_html($fee->name) }}</span>
        <span class="cart-totals__v">@php wc_cart_totals_fee_html($fee) @endphp</span>
      </div>
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
          <div class="cart-totals__row tax-rate tax-rate-{{ esc_attr(sanitize_title($code)) }}">
            <span>{!! esc_html($tax->label) . $estimated_text !!}</span>
            <span class="cart-totals__v">{!! wp_kses_post($tax->formatted_amount) !!}</span>
          </div>
        @endforeach
      @else
        <div class="cart-totals__row tax-total">
          <span>{!! esc_html(WC()->countries->tax_or_vat()) . $estimated_text !!}</span>
          <span class="cart-totals__v">@php wc_cart_totals_taxes_total_html() @endphp</span>
        </div>
      @endif
    @endif

    @if (wc_coupons_enabled() && ! $has_coupon)
      <div class="cart-totals__coupon">
        @include('partials.cart.coupon-form', [
          'has_coupon' => $has_coupon,
          'applied_coupon' => '',
        ])
      </div>
    @elseif (wc_coupons_enabled())
      <div class="cart-totals__coupon">
        @include('partials.cart.coupon-form', [
          'has_coupon' => true,
          'applied_coupon' => $applied_coupons[0],
        ])
      </div>
    @endif

    @php do_action('woocommerce_cart_totals_before_order_total') @endphp
  </div>

  <div class="cart-totals__divider" aria-hidden="true"></div>

  <div class="cart-totals__total order-total">
    <span class="cart-totals__total-lbl">{{ __('Total', 'sage') }}</span>
    <span class="cart-totals__total-v">@php wc_cart_totals_order_total_html() @endphp</span>
  </div>

  @php do_action('woocommerce_cart_totals_after_order_total') @endphp

  @php do_action('woocommerce_after_cart_totals') @endphp
</div>
