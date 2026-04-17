{{--
  Checkout login form. The default WC action that renders this is removed in
  app/checkout.php (we use our own Guest/Login tabs inside form-billing).
  Kept as a safety net: if any other code path calls this template, render
  nothing when logged in, and use the WC default otherwise.

  @see https://woocommerce.com/document/template-structure/
  @version 10.0.0
--}}

@php
  defined('ABSPATH') || exit;

  if (is_user_logged_in()) {
    return;
  }

  $registration_at_checkout = \WC_Checkout::instance()->is_registration_enabled();
  $login_reminder_at_checkout = 'yes' === get_option('woocommerce_enable_checkout_login_reminder');
@endphp

@if ($login_reminder_at_checkout)
  <div class="woocommerce-form-login-toggle">
    @php
      wc_print_notice(
        apply_filters('woocommerce_checkout_login_message', esc_html__('Returning customer?', 'woocommerce'))
          . ' <a href="#" class="showlogin">' . esc_html__('Click here to login', 'woocommerce') . '</a>',
        'notice'
      );
    @endphp
  </div>
@endif

@if ($registration_at_checkout || $login_reminder_at_checkout)
  @php
    $show_form = isset($_POST['login']);
    woocommerce_login_form([
      'message' => esc_html__('If you have shopped with us before, please enter your details below. If you are a new customer, please proceed to the Billing section.', 'woocommerce'),
      'redirect' => wc_get_checkout_url(),
      'hidden' => ! $show_form,
    ]);
  @endphp
@endif
