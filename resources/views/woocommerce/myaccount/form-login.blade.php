{{--
  Login / register page (shown on /contul-meu/ for guests).
  @see https://woocommerce.com/document/template-structure/
  @version 9.9.0
--}}

@php
  defined('ABSPATH') || exit;

  $enable_registration = 'yes' === get_option('woocommerce_enable_myaccount_registration');
  $generate_username = 'yes' === get_option('woocommerce_registration_generate_username');
  $generate_password = 'yes' === get_option('woocommerce_registration_generate_password');

  do_action('woocommerce_before_customer_login_form');
@endphp

<div class="natura-auth-page" data-natura-auth-group>
  @if ($enable_registration)
    <div class="natura-auth-tabs natura-auth-tabs--page" role="tablist">
      <button type="button" class="natura-auth-tab is-active" role="tab" aria-selected="true" data-natura-auth-tab="login">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
          <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/>
          <polyline points="10 17 15 12 10 7"/>
          <line x1="15" y1="12" x2="3" y2="12"/>
        </svg>
        {{ __('Autentificare', 'sage') }}
      </button>
      <button type="button" class="natura-auth-tab" role="tab" aria-selected="false" data-natura-auth-tab="register">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
          <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
          <circle cx="8.5" cy="7" r="4"/>
          <line x1="20" y1="8" x2="20" y2="14"/>
          <line x1="23" y1="11" x2="17" y2="11"/>
        </svg>
        {{ __('Înregistrare', 'sage') }}
      </button>
    </div>
  @endif

  {{-- LOGIN PANEL --}}
  <div class="natura-auth-panel is-active" data-natura-auth-panel="login" role="tabpanel">
    <div class="natura-auth-card">
      <div class="natura-auth-card__header">
        <p class="natura-auth-card__eyebrow">{{ __('Contul tău', 'sage') }}</p>
        <h1 class="natura-auth-card__title">{{ __('Bine ai revenit', 'sage') }}</h1>
        <p class="natura-auth-card__subtitle">{{ __('Conectează-te pentru a-ți accesa comenzile și adresele salvate.', 'sage') }}</p>
      </div>

      <form class="woocommerce-form woocommerce-form-login login natura-auth-form" method="post" novalidate>
        @php do_action('woocommerce_login_form_start') @endphp

        <div class="natura-auth-field">
          <label for="username">{{ __('Email sau nume utilizator', 'sage') }}</label>
          <input type="text"
                 class="woocommerce-Input woocommerce-Input--text input-text"
                 name="username"
                 id="username"
                 autocomplete="username"
                 placeholder="{{ esc_attr__('Introdu email-ul sau numele de utilizator', 'sage') }}"
                 value="{{ esc_attr(wp_unslash($_POST['username'] ?? '')) }}"
                 required>
        </div>

        <div class="natura-auth-field">
          <label for="password">{{ __('Parolă', 'sage') }}</label>
          <div class="natura-password-wrap">
            <input class="woocommerce-Input woocommerce-Input--text input-text"
                   type="password"
                   name="password"
                   id="password"
                   autocomplete="current-password"
                   placeholder="{{ esc_attr__('Introdu parola', 'sage') }}"
                   required>
            <button type="button" class="natura-password-toggle" data-natura-password-toggle aria-label="{{ esc_attr__('Arată parola', 'sage') }}">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                <circle cx="12" cy="12" r="3"/>
              </svg>
            </button>
          </div>
        </div>

        @php do_action('woocommerce_login_form') @endphp

        <div class="natura-auth-row">
          <label class="natura-auth-remember">
            <input class="woocommerce-form__input woocommerce-form__input-checkbox" name="rememberme" type="checkbox" id="rememberme" value="forever">
            <span>{{ __('Ține-mă minte', 'sage') }}</span>
          </label>
          <a href="{{ esc_url(wp_lostpassword_url()) }}" class="natura-auth-lostpass">
            {{ __('Ai uitat parola?', 'sage') }}
          </a>
        </div>

        {!! wp_nonce_field('woocommerce-login', 'woocommerce-login-nonce', true, false) !!}

        <button type="submit" class="natura-auth-submit" name="login" value="{{ esc_attr__('Conectează-te', 'sage') }}">
          <span class="natura-auth-submit__text">{{ __('Conectează-te', 'sage') }}</span>
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <line x1="5" y1="12" x2="19" y2="12"/>
            <polyline points="12 5 19 12 12 19"/>
          </svg>
        </button>

        @php do_action('woocommerce_login_form_end') @endphp
      </form>

      @if ($enable_registration)
        <p class="natura-auth-switch">
          {{ __('Nu ai cont?', 'sage') }}
          <a href="#" data-natura-switch-to="register">{{ __('Înregistrează-te acum', 'sage') }}</a>
        </p>
      @endif
    </div>
  </div>

  {{-- REGISTER PANEL --}}
  @if ($enable_registration)
    <div class="natura-auth-panel" data-natura-auth-panel="register" role="tabpanel" aria-hidden="true">
      <div class="natura-auth-card">
        <div class="natura-auth-card__header">
          <p class="natura-auth-card__eyebrow">{{ __('Cont nou', 'sage') }}</p>
          <h1 class="natura-auth-card__title">{{ __('Creează un cont', 'sage') }}</h1>
          <p class="natura-auth-card__subtitle">{{ __('Cumpără mai rapid, urmărește comenzile și primește oferte personalizate.', 'sage') }}</p>
        </div>

        <form method="post" class="woocommerce-form woocommerce-form-register register natura-auth-form" @php do_action('woocommerce_register_form_tag') @endphp>
          @php do_action('woocommerce_register_form_start') @endphp

          @if (! $generate_username)
            <div class="natura-auth-field">
              <label for="reg_username">{{ __('Nume utilizator', 'sage') }}</label>
              <input type="text"
                     class="woocommerce-Input woocommerce-Input--text input-text"
                     name="username"
                     id="reg_username"
                     autocomplete="username"
                     placeholder="{{ esc_attr__('Alege un nume de utilizator', 'sage') }}"
                     value="{{ esc_attr(wp_unslash($_POST['username'] ?? '')) }}"
                     required>
            </div>
          @endif

          <div class="natura-auth-field">
            <label for="reg_email">{{ __('Adresă email', 'sage') }}</label>
            <input type="email"
                   class="woocommerce-Input woocommerce-Input--text input-text"
                   name="email"
                   id="reg_email"
                   autocomplete="email"
                   placeholder="{{ esc_attr__('Introdu adresa ta de email', 'sage') }}"
                   value="{{ esc_attr(wp_unslash($_POST['email'] ?? '')) }}"
                   required>
          </div>

          @if (! $generate_password)
            <div class="natura-auth-field">
              <label for="reg_password">{{ __('Parolă', 'sage') }}</label>
              <div class="natura-password-wrap">
                <input type="password"
                       class="woocommerce-Input woocommerce-Input--text input-text"
                       name="password"
                       id="reg_password"
                       autocomplete="new-password"
                       data-natura-strength-input
                       placeholder="{{ esc_attr__('Creează o parolă sigură', 'sage') }}"
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
          @else
            <p class="natura-auth-info">{{ __('Vei primi un email cu un link pentru a seta parola contului tău.', 'sage') }}</p>
          @endif

          @php do_action('woocommerce_register_form') @endphp

          <ul class="natura-auth-benefits-list">
            <li>{{ __('Urmărește comenzile tale', 'sage') }}</li>
            <li>{{ __('Checkout mai rapid', 'sage') }}</li>
            <li>{{ __('Acces la oferte și beneficii dedicate', 'sage') }}</li>
          </ul>

          {!! wp_nonce_field('woocommerce-register', 'woocommerce-register-nonce', true, false) !!}

          <button type="submit" class="natura-auth-submit" name="register" value="{{ esc_attr__('Creează cont', 'sage') }}">
            <span class="natura-auth-submit__text">{{ __('Creează cont', 'sage') }}</span>
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
              <line x1="5" y1="12" x2="19" y2="12"/>
              <polyline points="12 5 19 12 12 19"/>
            </svg>
          </button>

          @php do_action('woocommerce_register_form_end') @endphp
        </form>

        <p class="natura-auth-switch">
          {{ __('Ai deja cont?', 'sage') }}
          <a href="#" data-natura-switch-to="login">{{ __('Conectează-te', 'sage') }}</a>
        </p>
      </div>
    </div>
  @endif
</div>

@php do_action('woocommerce_after_customer_login_form') @endphp
