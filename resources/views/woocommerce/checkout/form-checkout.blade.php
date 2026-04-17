{{--
  Checkout — 2-column layout ported from mana-naturii.
  Left: customer details (guest/login tabs + billing + shipping-to-different +
  shipping-methods + payment-methods).
  Right: order review + place-order + terms.

  @see https://woocommerce.com/document/template-structure/
  @version 9.4.0
--}}

@php
  defined('ABSPATH') || exit;

  do_action('woocommerce_before_checkout_form', $checkout);

  if (! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in()) {
    echo esc_html(apply_filters('woocommerce_checkout_must_be_logged_in_message', __('You must be logged in to checkout.', 'woocommerce')));
    return;
  }
@endphp

{!! \App\render_checkout_steps() !!}

<form name="checkout"
      method="post"
      class="checkout woocommerce-checkout checkout-two-columns"
      action="{{ esc_url(wc_get_checkout_url()) }}"
      enctype="multipart/form-data">

  <div class="checkout-grid">
    <div class="checkout-left">
      @if ($checkout->get_checkout_fields())
        @php do_action('woocommerce_checkout_before_customer_details') @endphp

        <div id="customer_details">
          <div class="billing-fields">
            @php do_action('woocommerce_checkout_billing') @endphp
          </div>

          <div class="shipping-fields">
            @php do_action('woocommerce_checkout_shipping') @endphp
          </div>
        </div>

        @php do_action('woocommerce_checkout_after_customer_details') @endphp
      @endif

      @if (WC()->cart->needs_shipping() && WC()->cart->show_shipping())
        <div id="shipping-section" class="checkout-shipping-wrapper">
          <h3 class="shipping_title">{{ __('Metodă de livrare', 'sage') }}</h3>
          <div class="shipping-methods-list">
            @php wc_cart_totals_shipping_html() @endphp
          </div>
        </div>
      @endif

      <div id="payment-section" class="checkout-payment-wrapper">
        <h3 class="payment_title">{{ __('Metodă de plată', 'sage') }}</h3>
        @php woocommerce_checkout_payment() @endphp
      </div>
    </div>

    <div class="checkout-right">
      @php do_action('woocommerce_checkout_before_order_review_heading') @endphp

      <h3 class="order_title">{{ __('Comanda ta', 'sage') }}</h3>

      @php do_action('woocommerce_checkout_before_order_review') @endphp

      <div id="order_review" class="woocommerce-checkout-review-order">
        @php woocommerce_order_review() @endphp
      </div>

      @php do_action('woocommerce_checkout_after_order_review') @endphp

      <div class="checkout-place-order-wrapper">
        <div class="form-row place-order">
          <noscript>
            {{ sprintf(__('Since your browser does not support JavaScript, or it is disabled, please ensure you click the %1$sUpdate Totals%2$s button before placing your order.', 'woocommerce'), '', '') }}
            <br/>
            <button type="submit" class="button alt" name="woocommerce_checkout_update_totals" value="{{ esc_attr__('Update totals', 'woocommerce') }}">
              {{ __('Update totals', 'woocommerce') }}
            </button>
          </noscript>

          @php wc_get_template('checkout/terms.php') @endphp

          @php do_action('woocommerce_review_order_before_submit') @endphp

          @php
            $order_button_text = apply_filters('woocommerce_order_button_text', __('Place order', 'woocommerce'));
            echo apply_filters(
              'woocommerce_order_button_html',
              '<button type="submit" class="button alt" name="woocommerce_checkout_place_order" id="place_order" value="' . esc_attr($order_button_text) . '" data-value="' . esc_attr($order_button_text) . '">' . esc_html($order_button_text) . '</button>'
            );
          @endphp

          @php do_action('woocommerce_review_order_after_submit') @endphp

          {!! wp_nonce_field('woocommerce-process_checkout', 'woocommerce-process-checkout-nonce', true, false) !!}
        </div>
      </div>
    </div>
  </div>
</form>

@php do_action('woocommerce_after_checkout_form', $checkout) @endphp
