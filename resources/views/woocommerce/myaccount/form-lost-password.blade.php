{{--
  Lost password form.
  @see https://woocommerce.com/document/template-structure/
  @version 9.2.0
--}}

@php
  defined('ABSPATH') || exit;
  do_action('woocommerce_before_lost_password_form');
@endphp

<div class="natura-auth-page" data-natura-auth-group>
  <div class="natura-auth-card">
    <div class="natura-auth-card__header">
      <p class="natura-auth-card__eyebrow">{{ __('Recuperare cont', 'sage') }}</p>
      <h1 class="natura-auth-card__title">{{ __('Ai uitat parola?', 'sage') }}</h1>
      <p class="natura-auth-card__subtitle">{{ __('Introdu email-ul sau numele de utilizator și îți trimitem un link pentru resetare.', 'sage') }}</p>
    </div>

    <form method="post" class="woocommerce-ResetPassword lost_reset_password natura-auth-form">
      <div class="natura-auth-field">
        <label for="user_login">{{ __('Email sau nume utilizator', 'sage') }}</label>
        <input type="text"
               class="woocommerce-Input woocommerce-Input--text input-text"
               name="user_login"
               id="user_login"
               autocomplete="username"
               placeholder="{{ esc_attr__('Introdu email-ul sau numele de utilizator', 'sage') }}"
               required>
      </div>

      @php do_action('woocommerce_lostpassword_form') @endphp

      <input type="hidden" name="wc_reset_password" value="true">
      {!! wp_nonce_field('lost_password', 'woocommerce-lost-password-nonce', true, false) !!}

      <button type="submit" class="natura-auth-submit" value="{{ esc_attr__('Trimite link de resetare', 'sage') }}">
        <span class="natura-auth-submit__text">{{ __('Trimite link de resetare', 'sage') }}</span>
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
          <line x1="22" y1="2" x2="11" y2="13"/>
          <polygon points="22 2 15 22 11 13 2 9 22 2"/>
        </svg>
      </button>
    </form>

    <p class="natura-auth-switch natura-auth-switch--back">
      <a href="{{ esc_url(wc_get_page_permalink('myaccount')) }}">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
          <line x1="19" y1="12" x2="5" y2="12"/>
          <polyline points="12 19 5 12 12 5"/>
        </svg>
        {{ __('Înapoi la autentificare', 'sage') }}
      </a>
    </p>
  </div>
</div>

@php do_action('woocommerce_after_lost_password_form') @endphp
