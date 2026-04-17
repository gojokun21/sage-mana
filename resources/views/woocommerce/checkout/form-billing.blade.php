{{--
  Billing form with guest/login tabs (custom AJAX login).
  @see https://woocommerce.com/document/template-structure/
  @version 3.6.0
--}}

@php
  defined('ABSPATH') || exit;
@endphp

@if (! is_user_logged_in())
  <div class="checkout-tabs">
    <div class="tabs-buttons" role="tablist" aria-label="{{ esc_attr__('Mod de checkout', 'sage') }}">
      <button type="button"
              id="checkout-tab-guest"
              class="tab-btn tab-guest active"
              role="tab"
              aria-selected="true"
              aria-controls="checkout-panel-guest"
              data-checkout-tab="guest">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" aria-hidden="true">
          <path d="M7 3h10l3 3v14a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1h2" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
          <path d="M8 8h8M8 12h8M8 16h5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
        </svg>
        {{ __('Achiziție rapidă', 'sage') }}
      </button>

      <button type="button"
              id="checkout-tab-login"
              class="tab-btn tab-login"
              role="tab"
              aria-selected="false"
              aria-controls="checkout-panel-login"
              data-checkout-tab="login">
        <svg width="18" height="20" viewBox="0 0 24 24" fill="none" aria-hidden="true">
          <circle cx="12" cy="8" r="4" stroke="currentColor" stroke-width="1.6"/>
          <path d="M4 21a8 8 0 0 1 16 0" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
        </svg>
        {{ __('Autentificare', 'sage') }}
      </button>
    </div>

    <div class="tab-content-wrapper">
      <div class="tab-content content-guest active"
           id="checkout-panel-guest"
           role="tabpanel"
           aria-labelledby="checkout-tab-guest"
           data-checkout-panel="guest">
        {{-- billing fields render below, outside these tabs --}}
      </div>

      <div class="tab-content content-login"
           id="checkout-panel-login"
           role="tabpanel"
           aria-labelledby="checkout-tab-login"
           aria-hidden="true"
           data-checkout-panel="login">
        <div class="checkout-login-form">
          <p class="form-row form-row-first">
            <label for="checkout_username">{{ __('Nume utilizator sau email', 'sage') }}&nbsp;<span class="required">*</span></label>
            <input type="text" class="input-text" name="checkout_username" id="checkout_username" autocomplete="username">
          </p>
          <p class="form-row form-row-last">
            <label for="checkout_password">{{ __('Parolă', 'sage') }}&nbsp;<span class="required">*</span></label>
            <span class="password-input">
              <input class="input-text" type="password" name="checkout_password" id="checkout_password" autocomplete="current-password">
            </span>
          </p>
          <div class="clear"></div>
          <p class="form-row">
            <label class="woocommerce-form__label woocommerce-form__label-for-checkbox woocommerce-form-login__rememberme">
              <input class="woocommerce-form__input woocommerce-form__input-checkbox" name="checkout_rememberme" type="checkbox" id="checkout_rememberme" value="forever">
              <span>{{ __('Ține-mă minte', 'sage') }}</span>
            </label>
            <button type="button" class="woocommerce-button button woocommerce-form-login__submit" id="checkout_login_btn">
              {{ __('Autentificare', 'sage') }}
            </button>
          </p>
          <p class="lost_password">
            <a href="{{ esc_url(wp_lostpassword_url()) }}">{{ __('Ai uitat parola?', 'sage') }}</a>
          </p>
          <div class="checkout-login-message" aria-live="polite"></div>
        </div>
      </div>
    </div>
  </div>
@endif

<div class="woocommerce-billing-fields">
  @if (wc_ship_to_billing_address_only() && WC()->cart->needs_shipping())
    <h3>{{ __('Billing &amp; Shipping', 'woocommerce') }}</h3>
  @else
    <h3>{{ __('Billing details', 'woocommerce') }}</h3>
  @endif

  @php do_action('woocommerce_before_checkout_billing_form', $checkout) @endphp

  <div class="woocommerce-billing-fields__field-wrapper">
    @foreach ($checkout->get_checkout_fields('billing') as $key => $field)
      @php woocommerce_form_field($key, $field, $checkout->get_value($key)) @endphp
    @endforeach
  </div>

  @php do_action('woocommerce_after_checkout_billing_form', $checkout) @endphp
</div>

@if (! is_user_logged_in() && $checkout->is_registration_enabled())
  <div class="woocommerce-account-fields">
    @if (! $checkout->is_registration_required())
      <p class="form-row form-row-wide create-account">
        <label class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox">
          <input class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox" id="createaccount"
                 @checked(true === $checkout->get_value('createaccount') || true === apply_filters('woocommerce_create_account_default_checked', false))
                 type="checkbox" name="createaccount" value="1" />
          <span>{{ __('Create an account?', 'woocommerce') }}</span>
        </label>
      </p>
    @endif

    @php do_action('woocommerce_before_checkout_registration_form', $checkout) @endphp

    @if ($checkout->get_checkout_fields('account'))
      <div class="create-account">
        @foreach ($checkout->get_checkout_fields('account') as $key => $field)
          @php woocommerce_form_field($key, $field, $checkout->get_value($key)) @endphp
        @endforeach
        <div class="clear"></div>
      </div>
    @endif

    @php do_action('woocommerce_after_checkout_registration_form', $checkout) @endphp
  </div>
@endif
