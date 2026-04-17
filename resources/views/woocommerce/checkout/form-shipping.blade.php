{{--
  Shipping-to-different-address form (accordion).
  @see https://woocommerce.com/document/template-structure/
  @version 3.6.0
--}}

@php
  defined('ABSPATH') || exit;
@endphp

<div class="woocommerce-shipping-fields">
  @if (true === WC()->cart->needs_shipping_address())
    <div class="shipping-accordion" data-shipping-accordion>
      <button type="button"
              class="shipping-accordion__header"
              id="ship-to-different-address"
              aria-expanded="false"
              aria-controls="shipping-accordion-panel"
              data-shipping-toggle>
        <span class="shipping-accordion__title">{{ __('Ship to a different address?', 'woocommerce') }}</span>
        <svg class="shipping-accordion__icon" width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true">
          <path d="M6 9l6 6 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
      </button>

      <input type="hidden" id="ship-to-different-address-checkbox" name="ship_to_different_address" value="0" />

      <div class="shipping_address shipping-accordion__content"
           id="shipping-accordion-panel"
           data-shipping-panel>
        @php do_action('woocommerce_before_checkout_shipping_form', $checkout) @endphp

        <div class="woocommerce-shipping-fields__field-wrapper">
          @foreach ($checkout->get_checkout_fields('shipping') as $key => $field)
            @php woocommerce_form_field($key, $field, $checkout->get_value($key)) @endphp
          @endforeach
        </div>

        @php do_action('woocommerce_after_checkout_shipping_form', $checkout) @endphp
      </div>
    </div>
  @endif
</div>

<div class="woocommerce-additional-fields">
  @php do_action('woocommerce_before_order_notes', $checkout) @endphp

  @if (apply_filters('woocommerce_enable_order_notes_field', 'yes' === get_option('woocommerce_enable_order_comments', 'yes')))
    @if (! WC()->cart->needs_shipping() || wc_ship_to_billing_address_only())
      <h3>{{ __('Additional information', 'woocommerce') }}</h3>
    @endif

    <div class="woocommerce-additional-fields__field-wrapper">
      @foreach ($checkout->get_checkout_fields('order') as $key => $field)
        @php woocommerce_form_field($key, $field, $checkout->get_value($key)) @endphp
      @endforeach
    </div>
  @endif

  @php do_action('woocommerce_after_order_notes', $checkout) @endphp
</div>
