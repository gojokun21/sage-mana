{{-- Empty cart — redesign per mockup: cerc icon + h1 italic + 2 CTAs + footer mini-links. --}}

@php
  defined('ABSPATH') || exit;

  do_action('woocommerce_cart_is_empty');

  $shop_url = wc_get_page_permalink('shop') ?: home_url('/');
  $quiz_url = home_url('/quiz/');
@endphp

<div class="cart-page cart-page--empty">
  <section class="cart-empty" aria-label="{{ esc_attr__('Coșul este gol', 'sage') }}">
    <div class="cart-empty__inner">
      <div class="cart-empty__illu" aria-hidden="true">
        <svg width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
          <path d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13l-1.5 6H19M7 13L5.4 5"/>
          <circle cx="9" cy="20" r="1.5"/>
          <circle cx="17" cy="20" r="1.5"/>
        </svg>
      </div>

      <h1 class="cart-empty__title">
        {{ __('Coșul tău este', 'sage') }} <em>{{ __('gol.', 'sage') }}</em>
      </h1>
      <p class="cart-empty__sub">{{ __('Nu te grăbi — mai bine alegi cu cap decât să cumperi pe panică.', 'sage') }}</p>

      <div class="cart-empty__actions">
        <a class="cart-empty__btn cart-empty__btn--primary" href="{{ esc_url($quiz_url) }}">
          {{ __('Fă testul de 60 sec', 'sage') }}
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
            <path d="M5 12h14"/>
            <path d="m12 5 7 7-7 7"/>
          </svg>
        </a>
        <a class="cart-empty__btn cart-empty__btn--outline" href="{{ esc_url(apply_filters('woocommerce_return_to_shop_redirect', $shop_url)) }}">
          {{ __('Vezi catalogul', 'sage') }}
        </a>
      </div>

      <div class="cart-empty__links">
        <a href="{{ esc_url($shop_url . '?orderby=popularity') }}">{{ __('Cele mai vândute', 'sage') }}</a>
        <span aria-hidden="true">·</span>
        <a href="{{ esc_url($shop_url . '?max_price=200') }}">{{ __('Sub 200 lei', 'sage') }}</a>
        <span aria-hidden="true">·</span>
        <a href="{{ esc_url(home_url('/pachete/')) }}">{{ __('Pachete', 'sage') }}</a>
      </div>
    </div>
  </section>
</div>
