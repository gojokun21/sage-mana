{{--
  Cross-sell inline (single card per mockup), AJAX-driven.

  Wrapped in <div data-cart-cross-sell> for fragment swap by cart.js after
  AJAX add. The <button> uses [data-cart-cross-add] + data-product-id + data-upsell-nonce
  for the click handler. No <a href> with add-to-cart params — that caused
  double-add on refresh and was the wrong UX for an SPA-style cart page.

  Vars:
    $recommended      \WC_Product[]   array indexat prin product_id => WC_Product
    $cart_has_upsell  bool
    $upsell_percent   float
    $upsell_nonce     string
--}}

@php
  $rec = null;
  $rec_id = null;
  foreach ($recommended as $product_id => $product) {
      if ($product && $product->is_visible()) {
          $rec = $product;
          $rec_id = (int) $product_id;
          break;
      }
  }
@endphp

<div class="cart-cross-wrap" data-cart-cross-sell>
  @if ($rec)
    @php
      $current_price = (float) $rec->get_price();
      $display_price = $cart_has_upsell
        ? $current_price
        : round($current_price * (1 - $upsell_percent / 100), 2);

      $fmt_lei = static fn ($v) => number_format_i18n((float) $v, 0) . ' lei';

      $trigger_name = '';
      $first_item = current(WC()->cart->get_cart());
      if ($first_item && ! empty($first_item['data'])) {
          $trigger_name = $first_item['data']->get_name();
      }
    @endphp

    <aside class="cart-cross" aria-label="{{ esc_attr__('Recomandare', 'sage') }}">
      <a class="cart-cross__img" href="{{ esc_url(get_permalink($rec_id)) }}" aria-hidden="true" tabindex="-1">
        {!! $rec->get_image('thumbnail', ['loading' => 'lazy', 'decoding' => 'async']) !!}
      </a>

      <div class="cart-cross__copy">
        <h4 class="cart-cross__title">
          @if ($trigger_name)
            {{ __('Clienții care iau', 'sage') }} {{ $trigger_name }} {{ __('aleg adesea', 'sage') }} <em>{{ $rec->get_name() }}</em>
          @else
            {{ __('Recomandat pentru tine:', 'sage') }} <em>{{ $rec->get_name() }}</em>
          @endif
        </h4>
        <p class="cart-cross__hint">
          {{ __('Combinație favorită a clienților.', 'sage') }} <strong>{{ __('Opțional, nu obligatoriu.', 'sage') }}</strong>
        </p>
      </div>

      <button type="button"
              class="cart-cross__add"
              data-cart-cross-add
              data-product-id="{{ $rec_id }}"
              data-upsell-nonce="{{ esc_attr($upsell_nonce) }}"
              aria-label="{{ esc_attr(sprintf(__('Adaugă %s în coș', 'sage'), $rec->get_name())) }}">
        <span class="cart-cross__add-icon" aria-hidden="true">
          <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
            <path d="M12 5v14M5 12h14"/>
          </svg>
        </span>
        <span class="cart-cross__add-text">{{ __('Adaugă', 'sage') }} · {{ $fmt_lei($display_price) }}</span>
        <span class="cart-cross__add-spinner" aria-hidden="true"></span>
      </button>
    </aside>
  @endif
</div>
