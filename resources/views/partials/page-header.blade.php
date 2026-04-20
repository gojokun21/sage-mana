<div class="page-header">
  <h1>{!! $title !!}</h1>
  @if (function_exists('is_cart') && is_cart() && ! WC()->cart->is_empty())
    <a href="{{ esc_url(wc_get_checkout_url()) }}" class="page-header-checkout-btn">
      {{ __('Finalizează comanda', 'sage') }}
    </a>
  @endif
</div>
