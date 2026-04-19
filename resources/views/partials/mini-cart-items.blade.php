@php
  $cart = function_exists('WC') ? WC()->cart : null;
  $is_empty = ! $cart || $cart->is_empty();
@endphp

@if ($is_empty)
  <div class="mini-cart-empty">
    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
      <path d="M2 3L2.265 3.088C3.585 3.528 4.245 3.748 4.622 4.272C4.999 4.796 5 5.492 5 6.883V9.5C5 12.328 5 13.743 5.879 14.621C6.757 15.5 8.172 15.5 11 15.5H19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
      <path d="M5 6H16.45C18.505 6 19.533 6 19.978 6.674C20.422 7.349 20.018 8.293 19.208 10.182L18.779 11.182C18.401 12.064 18.212 12.504 17.837 12.752C17.461 13 16.981 13 16.022 13H5" stroke="currentColor" stroke-width="1.5"/>
      <circle cx="9" cy="19" r="1.5" stroke="currentColor" stroke-width="1.5"/>
      <circle cx="17" cy="19" r="1.5" stroke="currentColor" stroke-width="1.5"/>
    </svg>
    <p>{{ __('Coșul tău este gol.', 'sage') }}</p>
    <a href="{{ function_exists('wc_get_page_permalink') ? wc_get_page_permalink('shop') : home_url('/') }}" class="mini-cart-empty__cta">
      {{ __('Descoperă produsele', 'sage') }}
    </a>
  </div>
@else
  <ul class="mini-cart-items" role="list">
    @foreach ($cart->get_cart() as $key => $item)
      @php
        $product = $item['data'] ?? null;
        if (! $product || ! $product->exists() || $item['quantity'] <= 0) continue;

        // Skip bundled child items (WC Product Bundles) — we want just the
        // parent bundle to show in the mini-cart. The `bundled_by` key is
        // present on every child item added under a parent bundle.
        $is_bundled_child = function_exists('wc_pb_is_bundled_cart_item')
          ? wc_pb_is_bundled_cart_item($item)
          : ! empty($item['bundled_by']);
        if ($is_bundled_child) continue;

        $product_id = $item['variation_id'] ?: $item['product_id'];
        $thumb_id = $product->get_image_id();
        $thumb_url = $thumb_id ? wp_get_attachment_image_url($thumb_id, 'woocommerce_thumbnail') : wc_placeholder_img_src();
        $permalink = $product->is_visible() ? $product->get_permalink($item) : '';
        $line_total = WC()->cart->get_product_subtotal($product, $item['quantity']);
        $item_max = $product->get_max_purchase_quantity();
        $item_max = ($item_max > 0) ? (int) $item_max : '';

        $total_sales = (int) $product->get_total_sales();
        $sales_label = $total_sales > 0
          ? ($total_sales === 1
              ? __('1 persoană a cumpărat acest produs', 'sage')
              : sprintf(__('%d persoane au cumpărat acest produs', 'sage'), $total_sales))
          : '';
      @endphp

      <li class="mini-cart-item" data-cart-item-key="{{ $key }}">
        <a href="{{ esc_url($permalink) }}" class="mini-cart-item__thumb" tabindex="-1">
          <img src="{{ esc_url($thumb_url) }}" alt="{{ esc_attr($product->get_name()) }}" loading="lazy">
        </a>

        <div class="mini-cart-item__info">
          <a href="{{ esc_url($permalink) }}" class="mini-cart-item__title">
            {{ $product->get_name() }}
          </a>

          @if ($sales_label)
            <div class="mini-cart-item__sales">
              <svg width="12" height="12" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <path d="M17 20h5v-2a4 4 0 0 0-3-3.87M9 20H3v-2a4 4 0 0 1 3-3.87m3 5.87a4 4 0 1 0 0-8 4 4 0 0 0 0 8zm7-8a4 4 0 1 0 0-8 4 4 0 0 0 0 8z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
              <span>{{ $sales_label }}</span>
            </div>
          @endif

          @if ($item['variation'] ?? false)
            <div class="mini-cart-item__meta">
              {!! wc_get_formatted_cart_item_data($item) !!}
            </div>
          @endif

          <div class="mini-cart-item__bottom">
            @include('partials.qty-stepper', [
              'name' => 'quantity',
              'value' => $item['quantity'],
              'min' => 0,
              'max' => $item_max,
              'size' => 'sm',
              'input_class' => 'mini-cart-qty-input',
              'input_attrs' => ['data-mini-cart-qty-input' => '1', 'aria-label' => __('Cantitate', 'sage')],
            ])

            <div class="mini-cart-item__price">{!! $line_total !!}</div>
          </div>
        </div>

        <button type="button" class="mini-cart-item__remove" data-mini-cart-remove aria-label="{{ __('Elimină produsul', 'sage') }}">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M18 6L6 18M6 6l12 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
        </button>
      </li>
    @endforeach
  </ul>

  @php
    $needs_shipping = $cart->needs_shipping();
    $shipping_total_html = $needs_shipping ? $cart->get_cart_shipping_total() : '';
  @endphp

  <footer class="mini-cart-footer">
    <div class="mini-cart-footer__row">
      <span>{{ __('Subtotal', 'sage') }}</span>
      <strong data-mini-cart-subtotal>{!! $cart->get_cart_subtotal() !!}</strong>
    </div>

    @if ($needs_shipping)
      <div class="mini-cart-footer__row">
        <span>{{ __('Livrare', 'sage') }}</span>
        <strong data-mini-cart-shipping>
          @if ($shipping_total_html)
            {!! $shipping_total_html !!}
          @else
            {{ __('Se calculează la checkout', 'sage') }}
          @endif
        </strong>
      </div>
    @endif

    <div class="mini-cart-footer__row mini-cart-footer__total">
      <span>{{ __('Total', 'sage') }}</span>
      <strong data-mini-cart-total>{!! $cart->get_total() !!}</strong>
    </div>

    <div class="mini-cart-footer__actions">
      <a href="{{ wc_get_cart_url() }}" class="mini-cart-btn mini-cart-btn--primary">
        {{ __('Finalizează comanda', 'sage') }}
      </a>
    </div>
  </footer>
@endif
