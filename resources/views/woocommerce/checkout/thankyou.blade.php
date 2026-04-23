{{--
  Thank-you page — custom Natura design.
  @see https://woocommerce.com/document/template-structure/
--}}

@php defined('ABSPATH') || exit; @endphp

<div class="woocommerce-order or-page">
  {!! \App\render_checkout_steps() !!}

  @if ($order)
    @php do_action('woocommerce_before_thankyou', $order->get_id()) @endphp

    @if ($order->has_status('failed'))
      <div class="or-failed">
        <div class="or-failed__icon" aria-hidden="true">
          <svg viewBox="0 0 24 24" width="40" height="40" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/>
          </svg>
        </div>
        <h1 class="or-failed__title">{{ __('Plata comenzii a fost respinsă', 'sage') }}</h1>
        <p class="or-failed__text">
          {{ __('Din păcate, banca/procesatorul a refuzat tranzacția. Poți încerca din nou plata sau alege o altă metodă.', 'sage') }}
        </p>
        <div class="or-failed__actions">
          <a href="{{ esc_url($order->get_checkout_payment_url()) }}" class="or-btn or-btn--primary">
            {{ __('Încearcă plata din nou', 'sage') }}
          </a>
          @if (is_user_logged_in())
            <a href="{{ esc_url(wc_get_page_permalink('myaccount')) }}" class="or-btn or-btn--ghost">
              {{ __('Contul meu', 'sage') }}
            </a>
          @endif
        </div>
      </div>
    @else
      {{-- ============================ Hero success ============================ --}}
      <section class="or-hero">
        <div class="or-hero__icon" aria-hidden="true">
          <svg viewBox="0 0 24 24" width="44" height="44" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="20 6 9 17 4 12"/>
          </svg>
        </div>
        <h1 class="or-hero__title">{{ __('Mulțumim! Comanda ta a fost primită.', 'sage') }}</h1>
        <p class="or-hero__subtitle">
          @if ($order->get_billing_email())
            {!! sprintf(
              esc_html__('Am trimis un email de confirmare la %s. Vei primi un alt mesaj când comanda este expediată.', 'sage'),
              '<strong>' . esc_html($order->get_billing_email()) . '</strong>'
            ) !!}
          @else
            {{ __('Vei primi un email când comanda este expediată.', 'sage') }}
          @endif
        </p>
      </section>

      {{-- ============================ Overview cards ============================ --}}
      <section class="or-overview" aria-label="{{ __('Rezumat comandă', 'sage') }}">
        <div class="or-card">
          <span class="or-card__label">{{ __('Număr comandă', 'sage') }}</span>
          <strong class="or-card__value">#{{ $order->get_order_number() }}</strong>
        </div>
        <div class="or-card">
          <span class="or-card__label">{{ __('Dată', 'sage') }}</span>
          <strong class="or-card__value">{{ wc_format_datetime($order->get_date_created()) }}</strong>
        </div>
        <div class="or-card">
          <span class="or-card__label">{{ __('Total', 'sage') }}</span>
          <strong class="or-card__value or-card__value--accent">{!! $order->get_formatted_order_total() !!}</strong>
        </div>
        @if ($order->get_payment_method_title())
          <div class="or-card">
            <span class="or-card__label">{{ __('Metodă de plată', 'sage') }}</span>
            <strong class="or-card__value">{!! wp_kses_post($order->get_payment_method_title()) !!}</strong>
          </div>
        @endif
      </section>

      {{-- ============================ Timeline ============================ --}}
      <section class="or-timeline-wrap">
        <h2 class="or-section-title">{{ __('Ce urmează?', 'sage') }}</h2>
        <ol class="or-timeline">
          <li class="or-timeline__step is-done">
            <div class="or-timeline__dot">
              <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
            </div>
            <div class="or-timeline__body">
              <span class="or-timeline__title">{{ __('Comandă primită', 'sage') }}</span>
              <span class="or-timeline__text">{{ __('Ai plasat comanda cu succes.', 'sage') }}</span>
            </div>
          </li>
          <li class="or-timeline__step is-active">
            <div class="or-timeline__dot"><span>2</span></div>
            <div class="or-timeline__body">
              <span class="or-timeline__title">{{ __('În pregătire', 'sage') }}</span>
              <span class="or-timeline__text">{{ __('Mana Naturii îți pregătește produsele pentru livrare.', 'sage') }}</span>
            </div>
          </li>
          <li class="or-timeline__step">
            <div class="or-timeline__dot"><span>3</span></div>
            <div class="or-timeline__body">
              <span class="or-timeline__title">{{ __('Expediată', 'sage') }}</span>
              <span class="or-timeline__text">{{ __('Vei primi un email când comanda ajunge la tine.', 'sage') }}</span>
            </div>
          </li>
        </ol>
      </section>

      {{-- ============================ WC hooks: payment details + order details + addresses ============================ --}}
      <section class="or-details">
        @php do_action('woocommerce_thankyou_' . $order->get_payment_method(), $order->get_id()) @endphp
        @php do_action('woocommerce_thankyou', $order->get_id()) @endphp
      </section>

      {{-- ============================ CTAs ============================ --}}
      <section class="or-actions">
        @if (is_user_logged_in())
          <a href="{{ esc_url(wc_get_page_permalink('myaccount')) }}" class="or-btn or-btn--primary">
            {{ __('Vezi comanda în contul meu', 'sage') }}
          </a>
        @endif
        <a href="{{ esc_url(wc_get_page_permalink('shop')) }}" class="or-btn or-btn--ghost">
          {{ __('Continuă cumpărăturile', 'sage') }}
        </a>
      </section>
    @endif
  @else
    <section class="or-hero or-hero--empty">
      <h1 class="or-hero__title">{{ __('Comandă indisponibilă', 'sage') }}</h1>
      <p class="or-hero__subtitle">{{ __('Nu am putut găsi detaliile acestei comenzi.', 'sage') }}</p>
    </section>
  @endif
</div>
