{{--
  Pagina de autentificare (login / înregistrare) — redesign după mockup
  `preferinte/Pagina Autentificare.html`. Afișată pe /contul-meu/ pentru vizitatori.

  Stil scoped sub `.natura-auth-screen` (vezi resources/css/auth.css) ca să NU
  afecteze modalul de login din header (care folosește aceleași clase de bază
  `.natura-auth-*`). Comportamentul (tab-uri, toggle parolă, strength) e driven de
  resources/js/auth.js prin hook-urile `data-natura-*` — păstrate intacte.

  Butoanele SSO (Google/Facebook/Apple) sunt VIZUALE deocamdată (fără OAuth
  configurat) — la cererea clientului, configurarea rețelelor e amânată.

  @see https://woocommerce.com/document/template-structure/
  @version 9.9.0
--}}

@php
  defined('ABSPATH') || exit;

  $enable_registration = 'yes' === get_option('woocommerce_enable_myaccount_registration');
  $generate_username = 'yes' === get_option('woocommerce_registration_generate_username');
  $generate_password = 'yes' === get_option('woocommerce_registration_generate_password');
  $terms_url = home_url('/termeni-si-conditii/');
  $privacy_url = home_url('/politica-de-confidentialitate/');
@endphp

<div class="natura-auth-screen" data-natura-auth-group>

  {{-- HERO --}}
  <section class="auth-hero">
    <h1>{{ __('Bun venit', 'sage') }} <em>{{ __('înapoi.', 'sage') }}</em></h1>
    <p class="sub">{{ __('Intră în cont sau creează unul nou în sub un minut.', 'sage') }}</p>
  </section>

  <section class="auth-wrap">

    @php do_action('woocommerce_before_customer_login_form') @endphp

    <div class="auth-card">

      {{-- Tabs --}}
      <div class="tabs" role="tablist">
        <button type="button" class="tab-btn is-active" role="tab" aria-selected="true" data-natura-auth-tab="login">{{ __('Autentificare', 'sage') }}</button>
        @if ($enable_registration)
          <button type="button" class="tab-btn" role="tab" aria-selected="false" data-natura-auth-tab="register">{{ __('Cont nou', 'sage') }}</button>
        @endif
      </div>

      <div class="card-body">

        {{-- SSO (vizual; OAuth neconfigurat încă) --}}
        <div class="sso-label">{{ __('Intră rapid cu', 'sage') }}</div>
        <div class="sso-stack">
          <button class="sso-btn" type="button">
            <svg width="18" height="18" viewBox="0 0 48 48" aria-hidden="true"><path fill="#FFC107" d="M43.611 20.083H42V20H24v8h11.303c-1.649 4.657-6.08 8-11.303 8-6.627 0-12-5.373-12-12s5.373-12 12-12c3.059 0 5.842 1.154 7.961 3.039l5.657-5.657C34.046 6.053 29.268 4 24 4 12.955 4 4 12.955 4 24s8.955 20 20 20 20-8.955 20-20c0-1.341-.138-2.65-.389-3.917z"/><path fill="#FF3D00" d="m6.306 14.691 6.571 4.819C14.655 15.108 18.961 12 24 12c3.059 0 5.842 1.154 7.961 3.039l5.657-5.657C34.046 6.053 29.268 4 24 4 16.318 4 9.656 8.337 6.306 14.691z"/><path fill="#4CAF50" d="M24 44c5.166 0 9.86-1.977 13.409-5.192l-6.19-5.238A11.91 11.91 0 0 1 24 36c-5.202 0-9.619-3.317-11.283-7.946l-6.522 5.025C9.505 39.556 16.227 44 24 44z"/><path fill="#1976D2" d="M43.611 20.083H42V20H24v8h11.303a12.04 12.04 0 0 1-4.087 5.571l.003-.002 6.19 5.238C36.971 39.205 44 34 44 24c0-1.341-.138-2.65-.389-3.917z"/></svg>
            {{ __('Continuă cu Google', 'sage') }}
          </button>
          <button class="sso-btn" type="button">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="#1877F2" aria-hidden="true"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
            {{ __('Continuă cu Facebook', 'sage') }}
          </button>
          <button class="sso-btn" type="button">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="#000" aria-hidden="true"><path d="M17.05 20.28c-.98.95-2.05.8-3.08.35-1.09-.46-2.09-.48-3.24 0-1.44.62-2.2.44-3.06-.35C2.79 15.25 3.51 7.59 9.05 7.31c1.35.07 2.29.74 3.08.8 1.18-.24 2.31-.93 3.57-.84 1.51.12 2.65.72 3.4 1.8-3.12 1.87-2.38 5.98.48 7.13-.57 1.5-1.31 2.99-2.54 4.09zM12 7.25c-.15-2.23 1.66-4.07 3.74-4.25.29 2.58-2.34 4.5-3.74 4.25z"/></svg>
            {{ __('Continuă cu Apple', 'sage') }}
          </button>
        </div>
        <p class="sso-consent">{!! wp_kses(sprintf(
          __('Făcând login cu Google, Facebook sau Apple, accepți %sTermenii%s și %sPolitica de confidențialitate%s.', 'sage'),
          '<a href="' . esc_url($terms_url) . '">', '</a>',
          '<a href="' . esc_url($privacy_url) . '">', '</a>'
        ), ['a' => ['href' => []]]) !!}</p>

        <div class="or-sep"><span>{{ __('sau cu email', 'sage') }}</span></div>

        {{-- ============ PANE LOGIN ============ --}}
        <div class="natura-auth-panel is-active" data-natura-auth-panel="login" role="tabpanel">
          <form class="auth-form woocommerce-form woocommerce-form-login login" method="post" novalidate>
            @php do_action('woocommerce_login_form_start') @endphp

            <div class="field">
              <label for="username">{{ __('Email', 'sage') }}</label>
              <div class="field-wrap">
                <input type="text"
                       class="woocommerce-Input woocommerce-Input--text input-text"
                       name="username"
                       id="username"
                       autocomplete="username"
                       placeholder="nume@email.ro"
                       value="{{ esc_attr(wp_unslash($_POST['username'] ?? '')) }}"
                       required>
              </div>
            </div>

            <div class="field">
              <label for="password">{{ __('Parolă', 'sage') }}</label>
              <div class="field-wrap with-icon">
                <input class="woocommerce-Input woocommerce-Input--text input-text"
                       type="password"
                       name="password"
                       id="password"
                       autocomplete="current-password"
                       placeholder="••••••••"
                       required>
                <button type="button" class="toggle-pw" data-natura-password-toggle aria-label="{{ esc_attr__('Arată parola', 'sage') }}">
                  <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                </button>
              </div>
            </div>

            @php do_action('woocommerce_login_form') @endphp

            <div class="row-between">
              <label class="check-inline">
                <input class="woocommerce-form__input woocommerce-form__input-checkbox" name="rememberme" type="checkbox" id="rememberme" value="forever">
                <span class="cb-box" aria-hidden="true"><svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M20 6 9 17l-5-5"/></svg></span>
                {{ __('Ține-mă conectat', 'sage') }}
              </label>
              <a href="{{ esc_url(wp_lostpassword_url()) }}" class="forgot-link">{{ __('Am uitat parola', 'sage') }}</a>
            </div>

            {!! wp_nonce_field('woocommerce-login', 'woocommerce-login-nonce', true, false) !!}

            <button type="submit" class="submit-btn" name="login" value="{{ esc_attr__('Intră în cont', 'sage') }}">
              {{ __('Intră în cont', 'sage') }}
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
            </button>

            @php do_action('woocommerce_login_form_end') @endphp

            @if ($enable_registration)
              <p class="alt-line">{{ __('Nu ai cont?', 'sage') }} <a href="#" data-natura-switch-to="register">{{ __('Creează unul.', 'sage') }}</a></p>
            @endif
          </form>
        </div>

        {{-- ============ PANE REGISTER ============ --}}
        @if ($enable_registration)
          <div class="natura-auth-panel" data-natura-auth-panel="register" role="tabpanel" aria-hidden="true">
            <form method="post" class="auth-form woocommerce-form woocommerce-form-register register" @php do_action('woocommerce_register_form_tag') @endphp>
              @php do_action('woocommerce_register_form_start') @endphp

              @if (! $generate_username)
                <div class="field">
                  <label for="reg_username">{{ __('Nume utilizator', 'sage') }}</label>
                  <div class="field-wrap">
                    <input type="text"
                           class="woocommerce-Input woocommerce-Input--text input-text"
                           name="username"
                           id="reg_username"
                           autocomplete="username"
                           placeholder="{{ esc_attr__('Alege un nume de utilizator', 'sage') }}"
                           value="{{ esc_attr(wp_unslash($_POST['username'] ?? '')) }}"
                           required>
                  </div>
                </div>
              @endif

              <div class="field">
                <label for="reg_email">{{ __('Email', 'sage') }}</label>
                <div class="field-wrap">
                  <input type="email"
                         class="woocommerce-Input woocommerce-Input--text input-text"
                         name="email"
                         id="reg_email"
                         autocomplete="email"
                         placeholder="nume@email.ro"
                         value="{{ esc_attr(wp_unslash($_POST['email'] ?? '')) }}"
                         required>
                </div>
              </div>

              @if (! $generate_password)
                <div class="field">
                  <label for="reg_password">{{ __('Parolă', 'sage') }}</label>
                  <div class="field-wrap with-icon">
                    <input type="password"
                           class="woocommerce-Input woocommerce-Input--text input-text"
                           name="password"
                           id="reg_password"
                           autocomplete="new-password"
                           data-natura-strength-input
                           placeholder="{{ esc_attr__('Cel puțin 8 caractere', 'sage') }}"
                           required>
                    <button type="button" class="toggle-pw" data-natura-password-toggle aria-label="{{ esc_attr__('Arată parola', 'sage') }}">
                      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                    </button>
                  </div>
                  <div class="pw-strength-track"><span class="pw-strength-bar" data-natura-strength-bar></span></div>
                  <p class="helper">{{ __('Minim 8 caractere, cu litere și cifre.', 'sage') }}</p>
                </div>
              @else
                <p class="auth-info">{{ __('Vei primi un email cu un link pentru a seta parola contului tău.', 'sage') }}</p>
              @endif

              @php do_action('woocommerce_register_form') @endphp

              <label class="consent-row required">
                <input type="checkbox" name="natura_terms_accept" required>
                <span class="cb-box" aria-hidden="true"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M20 6 9 17l-5-5"/></svg></span>
                <span class="txt">{!! wp_kses(sprintf(
                  __('Am citit și sunt de acord cu %sTermenii și Condițiile%s și %sPolitica de confidențialitate%s.', 'sage'),
                  '<a href="' . esc_url($terms_url) . '">', '</a>',
                  '<a href="' . esc_url($privacy_url) . '">', '</a>'
                ), ['a' => ['href' => []]]) !!}</span>
              </label>
              <label class="consent-row">
                <input type="checkbox" name="natura_newsletter_optin" value="1">
                <span class="cb-box" aria-hidden="true"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M20 6 9 17l-5-5"/></svg></span>
                <span class="txt">{{ __('Vreau să primesc sfaturi despre sănătate naturală și oferte. Poți dezabona oricând.', 'sage') }}</span>
              </label>

              {!! wp_nonce_field('woocommerce-register', 'woocommerce-register-nonce', true, false) !!}

              <button type="submit" class="submit-btn" name="register" value="{{ esc_attr__('Creează cont', 'sage') }}">
                {{ __('Creează cont', 'sage') }}
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
              </button>

              @php do_action('woocommerce_register_form_end') @endphp

              <p class="alt-line">{{ __('Ai deja cont?', 'sage') }} <a href="#" data-natura-switch-to="login">{{ __('Intră.', 'sage') }}</a></p>
            </form>
          </div>
        @endif

      </div>
    </div>

    {{-- Guest escape --}}
    <div class="guest-card">
      <div class="ico" aria-hidden="true"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13l-1.5 6H19M7 13L5.4 5"/><circle cx="9" cy="20" r="1.5"/><circle cx="17" cy="20" r="1.5"/></svg></div>
      <h3>{{ __('Preferi', 'sage') }} <em>{{ __('fără cont?', 'sage') }}</em></h3>
      <p>{{ __('Poți cumpăra și ca invitat, fără să-ți faci cont. Primim comanda, livrăm, gata.', 'sage') }}</p>
      <a href="{{ esc_url(wc_get_cart_url()) }}">
        {{ __('Mergi la coș ca invitat', 'sage') }}
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
      </a>
    </div>

    {{-- Trust signals --}}
    <div class="trust-row">
      <span class="trust-item">
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
        {{ __('Conexiune securizată SSL', 'sage') }}
      </span>
      <span class="trust-item">
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
        {{ __('Date stocate în UE, conform GDPR', 'sage') }}
      </span>
      <span class="trust-item">
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><path d="m22 6-10 7L2 6"/></svg>
        {{ __('Fără spam, niciodată', 'sage') }}
      </span>
    </div>

  </section>
</div>

@php do_action('woocommerce_after_customer_login_form') @endphp
