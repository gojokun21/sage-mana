{{--
  Payment methods only (place-order button is rendered separately by
  form-checkout.blade.php). Re-rendered via WC AJAX during updated_checkout.
  @see https://woocommerce.com/document/template-structure/
  @version 9.8.0
--}}

@php
  defined('ABSPATH') || exit;

  if (! wp_doing_ajax()) {
    do_action('woocommerce_review_order_before_payment');
  }
@endphp

<div id="payment" class="woocommerce-checkout-payment">
  @if (WC()->cart && WC()->cart->needs_payment())
    <ul class="wc_payment_methods payment_methods methods">
      @if (! empty($available_gateways))
        @foreach ($available_gateways as $gateway)
          @php wc_get_template('checkout/payment-method.php', ['gateway' => $gateway]) @endphp
        @endforeach
      @else
        <li>
          @php
            wc_print_notice(
              apply_filters(
                'woocommerce_no_available_payment_methods_message',
                WC()->customer->get_billing_country()
                  ? esc_html__('Sorry, it seems that there are no available payment methods. Please contact us if you require assistance or wish to make alternate arrangements.', 'woocommerce')
                  : esc_html__('Please fill in your details above to see available payment methods.', 'woocommerce')
              ),
              'notice'
            );
          @endphp
        </li>
      @endif
    </ul>
  @endif
</div>

@php
  if (! wp_doing_ajax()) {
    do_action('woocommerce_review_order_after_payment');
  }
@endphp
