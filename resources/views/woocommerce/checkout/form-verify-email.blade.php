{{--
  Email verification form (shown in place of the thank-you page when the
  customer cannot be identified).
  @see https://woocommerce.com/document/template-structure/
  @version 7.9.0

  Vars:
    $failed_submission  bool
    $verify_url         string
--}}

@php defined('ABSPATH') || exit; @endphp

<form name="checkout" method="post" class="woocommerce-form woocommerce-verify-email" action="{{ esc_url($verify_url) }}">
  {!! wp_nonce_field('wc_verify_email', 'check_submission', true, false) !!}

  @if ($failed_submission)
    @php
      wc_print_notice(esc_html__('We were unable to verify the email address you provided. Please try again.', 'woocommerce'), 'error');
    @endphp
  @endif

  <p>
    {!! sprintf(
      esc_html__('To view this page, you must either %1$slogin%2$s or verify the email address associated with the order.', 'woocommerce'),
      '<a href="' . esc_url(wc_get_page_permalink('myaccount')) . '">',
      '</a>'
    ) !!}
  </p>

  <p class="form-row">
    <label for="email">{{ __('Email address', 'woocommerce') }}&nbsp;<span class="required">*</span></label>
    <input type="email" class="input-text" name="email" id="email" autocomplete="email" />
  </p>

  <p class="form-row">
    <button type="submit" class="woocommerce-button button" name="verify" value="1">
      {{ __('Verify', 'woocommerce') }}
    </button>
  </p>
</form>
