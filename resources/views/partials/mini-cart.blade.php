{{-- Mini Cart Drawer — shell rendered once per page. --}}
<div id="miniCartOverlay" class="mini-cart-overlay" aria-hidden="true"></div>

<aside id="miniCartDrawer" class="mini-cart-drawer" aria-hidden="true" aria-labelledby="miniCartTitle" role="dialog">
  <header class="mini-cart-drawer__header">
    <h2 id="miniCartTitle" class="mini-cart-drawer__title">
      {{ __('Coșul tău', 'sage') }}
      <span class="mini-cart-drawer__count" data-mini-cart-count>
        {{ function_exists('WC') && WC()->cart ? WC()->cart->get_cart_contents_count() : 0 }}
      </span>
    </h2>
    <button type="button" class="mini-cart-drawer__close" data-mini-cart-close aria-label="{{ __('Închide coșul', 'sage') }}">
      <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M18 6L6 18M6 6l12 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
      </svg>
    </button>
  </header>

  <div class="mini-cart-drawer__body" data-mini-cart-body>
    @include('partials.mini-cart-items')
  </div>
</aside>
