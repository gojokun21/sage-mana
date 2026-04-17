{{--
  Reset password form (after clicking the email link).
  @see https://woocommerce.com/document/template-structure/
  @version 9.2.0
--}}

@php
  defined('ABSPATH') || exit;
  do_action('woocommerce_before_reset_password_form');
@endphp

<div class="natura-auth-page" data-natura-auth-group>
  <div class="natura-auth-card">
    <div class="natura-auth-card__header">
      <p class="natura-auth-card__eyebrow">{{ __('Parolă nouă', 'sage') }}</p>
      <h1 class="natura-auth-card__title">{{ __('Creează o parolă nouă', 'sage') }}</h1>
      <p class="natura-auth-card__subtitle">{{ __('Alege o parolă sigură pentru contul tău.', 'sage') }}</p>
    </div>

    <form method="post" class="woocommerce-ResetPassword lost_reset_password natura-auth-form">
      <div class="natura-auth-field">
        <label for="password_1">{{ __('Parolă nouă', 'sage') }}</label>
        <div class="natura-password-wrap">
          <input type="password"
                 class="woocommerce-Input woocommerce-Input--text input-text"
                 name="password_1"
                 id="password_1"
                 autocomplete="new-password"
                 data-natura-strength-input
                 placeholder="{{ esc_attr__('Introdu parola nouă', 'sage') }}"
                 required>
          <button type="button" class="natura-password-toggle" data-natura-password-toggle aria-label="{{ esc_attr__('Arată parola', 'sage') }}">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
              <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
              <circle cx="12" cy="12" r="3"/>
            </svg>
          </button>
        </div>
        <div class="natura-password-strength"><div class="natura-password-strength__bar" data-natura-strength-bar></div></div>
      </div>

      <div class="natura-auth-field">
        <label for="password_2">{{ __('Confirmă parola', 'sage') }}</label>
        <div class="natura-password-wrap">
          <input type="password"
                 class="woocommerce-Input woocommerce-Input--text input-text"
                 name="password_2"
                 id="password_2"
                 autocomplete="new-password"
                 data-natura-password-match
                 placeholder="{{ esc_attr__('Repetă parola nouă', 'sage') }}"
                 required>
          <button type="button" class="natura-password-toggle" data-natura-password-toggle aria-label="{{ esc_attr__('Arată parola', 'sage') }}">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
              <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
              <circle cx="12" cy="12" r="3"/>
            </svg>
          </button>
        </div>
        <p class="natura-password-match" data-natura-password-match-msg aria-live="polite"></p>
      </div>

      <div class="natura-auth-info natura-auth-info--tip">
        <strong>{{ __('Pentru o parolă sigură', 'sage') }}</strong>
        <span>{{ __('Folosește cel puțin 8 caractere: litere mari și mici, cifre și simboluri.', 'sage') }}</span>
      </div>

      <input type="hidden" name="reset_key" value="{{ esc_attr($args['key']) }}">
      <input type="hidden" name="reset_login" value="{{ esc_attr($args['login']) }}">
      <input type="hidden" name="wc_reset_password" value="true">

      @php do_action('woocommerce_resetpassword_form') @endphp
      {!! wp_nonce_field('reset_password', 'woocommerce-reset-password-nonce', true, false) !!}

      <button type="submit" class="natura-auth-submit" value="{{ esc_attr__('Salvează parola nouă', 'sage') }}">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
          <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
          <polyline points="17 21 17 13 7 13 7 21"/>
          <polyline points="7 3 7 8 15 8"/>
        </svg>
        <span class="natura-auth-submit__text">{{ __('Salvează parola nouă', 'sage') }}</span>
      </button>
    </form>
  </div>
</div>

@php do_action('woocommerce_after_reset_password_form') @endphp
