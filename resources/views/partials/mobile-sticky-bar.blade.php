{{--
  Bară de navigare sticky jos, doar pe mobil (≤640px). Globală (inclusă în layout).
  - Acasă → home; activ pe prima pagină.
  - Caută → deschide popup-ul de căutare mobil existent (clasa .mobile-search-trigger,
    legată în resources/js/app.js).
  - Test 60s → URL filtrabil (mn_mobile_nav_test_url); implicit hub-ul „După simptom".
  - Coș → deschide mini-cart drawer (data-mini-cart-trigger) + badge live
    (data-mini-cart-count, sincronizat de resources/js/mini-cart.js).
  CSS: resources/css/mobile-sticky-bar.css (scope .mn-tabbar), importat în app.css.
--}}
@php
  $home_url  = home_url('/');
  $cart_url  = function_exists('wc_get_cart_url') ? wc_get_cart_url() : home_url('/cos/');
  $test_url  = apply_filters('mn_mobile_nav_test_url', home_url('/dupa-simptom/'));
  $cart_count = (function_exists('WC') && WC()->cart) ? (int) WC()->cart->get_cart_contents_count() : 0;

  $is_home = is_front_page();
  $is_cart = (function_exists('is_cart') && is_cart()) || (function_exists('is_checkout') && is_checkout());
@endphp

<nav class="mn-tabbar" aria-label="{{ esc_attr__('Navigare principală mobil', 'sage') }}">
  <a class="mn-tabbar__item {{ $is_home ? 'is-active' : '' }}" href="{{ esc_url($home_url) }}" @if ($is_home) aria-current="page" @endif>
    <span class="mn-tabbar__icon"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2h-4v-7h-6v7H5a2 2 0 0 1-2-2z"/></svg></span>
    <span class="mn-tabbar__label">{{ __('Acasă', 'sage') }}</span>
  </a>

  <button type="button" class="mn-tabbar__item mobile-search-trigger" aria-label="{{ esc_attr__('Caută', 'sage') }}">
    <span class="mn-tabbar__icon"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg></span>
    <span class="mn-tabbar__label">{{ __('Caută', 'sage') }}</span>
  </button>

  <a class="mn-tabbar__item" href="{{ esc_url($test_url) }}">
    <span class="mn-tabbar__icon"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M9 2v6.4a4 4 0 0 1-.4 1.8L4 18a2 2 0 0 0 1.8 2.8h12.4a2 2 0 0 0 1.8-2.8l-4.6-7.8a4 4 0 0 1-.4-1.8V2"/><path d="M8 2h8M9 14h6"/></svg></span>
    <span class="mn-tabbar__label">{{ __('Test 60s', 'sage') }}</span>
  </a>

  <a class="mn-tabbar__item {{ $is_cart ? 'is-active' : '' }}" href="{{ esc_url($cart_url) }}"
     data-mini-cart-trigger
     aria-controls="miniCartDrawer"
     aria-expanded="false"
     aria-label="{{ esc_attr__('Deschide coșul', 'sage') }}">
    <span class="mn-tabbar__icon"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13l-1.5 6H19M7 13L5.4 5"/><circle cx="9" cy="20" r="1.5"/><circle cx="17" cy="20" r="1.5"/></svg></span>
    <span class="mn-tabbar__badge {{ $cart_count > 0 ? '' : 'is-empty' }}" data-mini-cart-count>{{ $cart_count }}</span>
    <span class="mn-tabbar__label">{{ __('Coș', 'sage') }}</span>
  </a>
</nav>
