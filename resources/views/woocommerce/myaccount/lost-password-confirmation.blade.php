{{--
  Lost password — confirmation screen.
  @see https://woocommerce.com/document/template-structure/
  @version 3.9.0
--}}

@php
  defined('ABSPATH') || exit;

  $message = apply_filters(
    'woocommerce_lost_password_confirmation_message',
    __('Un email cu instrucțiuni a fost trimis către adresa din contul tău. Poate dura câteva minute să apară în inbox — te rugăm să aștepți cel puțin 10 minute înainte să încerci din nou.', 'sage')
  );

  do_action('woocommerce_before_lost_password_confirmation_message');
@endphp

<div class="natura-auth-page">
  <div class="natura-auth-card natura-auth-card--success">
    <div class="natura-auth-success-icon" aria-hidden="true">
      <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
        <polyline points="20 6 9 17 4 12"/>
      </svg>
    </div>

    <div class="natura-auth-card__header">
      <p class="natura-auth-card__eyebrow">{{ __('Email trimis', 'sage') }}</p>
      <h1 class="natura-auth-card__title">{{ __('Verifică inbox-ul', 'sage') }}</h1>
      <p class="natura-auth-card__subtitle">{{ esc_html($message) }}</p>
    </div>

    <div class="natura-auth-confirmation-actions">
      <a href="{{ esc_url(wc_get_page_permalink('myaccount')) }}" class="natura-auth-submit natura-auth-submit--outline">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
          <line x1="19" y1="12" x2="5" y2="12"/>
          <polyline points="12 19 5 12 12 5"/>
        </svg>
        <span class="natura-auth-submit__text">{{ __('Înapoi la autentificare', 'sage') }}</span>
      </a>
    </div>
  </div>
</div>

@php do_action('woocommerce_after_lost_password_confirmation_message') @endphp
