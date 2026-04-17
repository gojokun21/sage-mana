{{--
  Login (+ optional register) modal, rendered on wp_footer for guests.
  Triggered by any element with class `.open-login-modal`.
--}}

<div id="natura-login-modal" class="natura-login-modal" data-natura-auth-group inert>
  <div class="natura-login-modal__overlay" data-natura-login-close></div>

  <div class="natura-login-modal__content" role="dialog" aria-modal="true" aria-labelledby="natura-login-title">
    <button type="button" class="natura-login-modal__close" data-natura-login-close aria-label="{{ esc_attr__('Închide fereastra', 'sage') }}">&times;</button>

    @if ($enable_registration)
      <div class="natura-auth-tabs" role="tablist">
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
    <div class="natura-auth-panel is-active" id="natura-panel-login" data-natura-auth-panel="login" role="tabpanel">
      @if (! $enable_registration)
        <h2 id="natura-login-title" class="natura-auth-title">{{ __('Autentificare', 'sage') }}</h2>
      @endif

      <form class="natura-login-form" id="natura-login-form" novalidate>
        <div class="natura-auth-field">
          <label for="natura-login-username">{{ __('Email sau utilizator', 'sage') }}</label>
          <input type="text" id="natura-login-username" name="username" autocomplete="username"
                 placeholder="{{ esc_attr__('Introdu email-ul sau numele de utilizator', 'sage') }}" required>
        </div>

        <div class="natura-auth-field">
          <label for="natura-login-password">{{ __('Parolă', 'sage') }}</label>
          <div class="natura-password-wrap">
            <input type="password" id="natura-login-password" name="password" autocomplete="current-password"
                   placeholder="{{ esc_attr__('Introdu parola', 'sage') }}" required>
            <button type="button" class="natura-password-toggle" data-natura-password-toggle aria-label="{{ esc_attr__('Arată parola', 'sage') }}">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                <circle cx="12" cy="12" r="3"/>
              </svg>
            </button>
          </div>
        </div>

        <div class="natura-auth-row">
          <label class="natura-auth-remember">
            <input type="checkbox" name="rememberme" value="forever">
            <span>{{ __('Ține-mă minte', 'sage') }}</span>
          </label>
          <a href="{{ esc_url($lost_password_url) }}" class="natura-auth-lostpass">
            {{ __('Ai uitat parola?', 'sage') }}
          </a>
        </div>

        <div class="natura-auth-error" data-natura-login-error role="alert" aria-live="polite"></div>

        <button type="submit" class="natura-auth-submit" data-natura-login-submit>
          <span class="natura-auth-submit__text">{{ __('Conectează-te', 'sage') }}</span>
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <line x1="5" y1="12" x2="19" y2="12"/>
            <polyline points="12 5 19 12 12 19"/>
          </svg>
        </button>
      </form>

      @if ($enable_registration)
        <p class="natura-auth-switch">
          {{ __('Nu ai cont?', 'sage') }}
          <a href="#" data-natura-switch-to="register">{{ __('Înregistrează-te acum', 'sage') }}</a>
        </p>
      @endif
    </div>

    {{-- REGISTER PANEL --}}
    @if ($enable_registration)
      <div class="natura-auth-panel" id="natura-panel-register" data-natura-auth-panel="register" role="tabpanel" aria-hidden="true">
        <form method="post" class="natura-login-form" action="{{ esc_url($register_action_url) }}">
          {!! wp_nonce_field('woocommerce-register', 'woocommerce-register-nonce', true, false) !!}

          @if (! $generate_username)
            <div class="natura-auth-field">
              <label for="natura-reg-username">{{ __('Nume utilizator', 'sage') }}</label>
              <input type="text" id="natura-reg-username" name="username" autocomplete="username"
                     placeholder="{{ esc_attr__('Alege un nume de utilizator', 'sage') }}" required>
            </div>
          @endif

          <div class="natura-auth-field">
            <label for="natura-reg-email">{{ __('Adresă email', 'sage') }}</label>
            <input type="email" id="natura-reg-email" name="email" autocomplete="email"
                   placeholder="{{ esc_attr__('Introdu adresa ta de email', 'sage') }}" required>
          </div>

          @if (! $generate_password)
            <div class="natura-auth-field">
              <label for="natura-reg-password">{{ __('Parolă', 'sage') }}</label>
              <div class="natura-password-wrap">
                <input type="password" id="natura-reg-password" name="password" autocomplete="new-password"
                       data-natura-strength-input
                       placeholder="{{ esc_attr__('Creează o parolă sigură', 'sage') }}" required>
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

          <div class="natura-auth-benefits">
            <h4>{{ __('Beneficiile unui cont:', 'sage') }}</h4>
            <ul>
              <li>{{ __('Urmărește comenzile tale', 'sage') }}</li>
              <li>{{ __('Checkout mai rapid', 'sage') }}</li>
              <li>{{ __('Acces la oferte și beneficii dedicate', 'sage') }}</li>
            </ul>
          </div>

          <button type="submit" name="register" class="natura-auth-submit">
            <span class="natura-auth-submit__text">{{ __('Creează cont', 'sage') }}</span>
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
              <line x1="5" y1="12" x2="19" y2="12"/>
              <polyline points="12 5 19 12 12 19"/>
            </svg>
          </button>
        </form>

        <p class="natura-auth-switch">
          {{ __('Ai deja cont?', 'sage') }}
          <a href="#" data-natura-switch-to="login">{{ __('Conectează-te', 'sage') }}</a>
        </p>
      </div>
    @endif
  </div>
</div>
