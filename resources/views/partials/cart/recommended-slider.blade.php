{{--
  Recommended products slider for the cart page (native scroll-snap, no library).

  Vars:
    $recommended       \WC_Product[]
    $cart_has_upsell   bool
    $upsell_percent    float
    $upsell_nonce      string
--}}

<section class="cart-recommended">
  <div class="cart-recommended__header">
    <h3 class="cart-recommended__title">{{ __('Recomandate împreună cu produsele din coș', 'sage') }}</h3>

    <div class="cart-recommended__nav" role="group" aria-label="{{ esc_attr__('Navigare recomandări', 'sage') }}">
      <button type="button" class="cart-recommended__arrow" data-dir="prev" aria-label="{{ esc_attr__('Anterior', 'sage') }}">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
          <path d="M15 18l-6-6 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
      </button>
      <button type="button" class="cart-recommended__arrow" data-dir="next" aria-label="{{ esc_attr__('Următorul', 'sage') }}">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
          <path d="M9 18l6-6-6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
      </button>
    </div>
  </div>

  <ul class="cart-recommended__track" data-cart-slider>
    @foreach ($recommended as $product_id => $product)
      @php
        if (! $product || ! $product->is_visible()) continue;

        $regular_price = (float) $product->get_regular_price();
        $current_price = (float) $product->get_price();

        if (! $cart_has_upsell) {
          $display_price = round($current_price * (1 - $upsell_percent / 100), 2);
        } else {
          $display_price = $current_price;
        }

        $total_discount = 0;
        if ($regular_price > 0 && $display_price < $regular_price) {
          $total_discount = (int) round((($regular_price - $display_price) / $regular_price) * 100);
        }

        $permalink = get_permalink($product_id);

        $info_generala = get_field('informatie_generala', $product_id);
        $protocol_zile = ! empty($info_generala['protocol_zile']) ? $info_generala['protocol_zile'] : '';

        $add_url = $cart_has_upsell
          ? add_query_arg(['add-to-cart' => $product_id], wc_get_cart_url())
          : add_query_arg([
              'add-to-cart' => $product_id,
              'upsell_discount' => 1,
              '_upsell_nonce' => $upsell_nonce,
            ], wc_get_cart_url());
      @endphp

      <li class="cart-recommended__item">
        <div class="product_img">
          @if ($total_discount > 0)
            <span class="price_discount">-{{ $total_discount }}%</span>
          @endif
          <a href="{{ esc_url($permalink) }}">{!! $product->get_image('woocommerce_thumbnail') !!}</a>
        </div>

        <div class="product_content">
          <div class="price_box">
            @if ($display_price < $regular_price)
              <span class="price_new">{!! wc_price($display_price) !!}</span>
              <span class="price_old">{!! wc_price($regular_price) !!}</span>
            @else
              <span class="price_new">{!! wc_price($display_price) !!}</span>
            @endif
          </div>

          @if ($protocol_zile)
            <span class="protocol_badge">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 448 512" fill="currentColor"><path d="M128 0c17.7 0 32 14.3 32 32V64H288V32c0-17.7 14.3-32 32-32s32 14.3 32 32V64h48c26.5 0 48 21.5 48 48v48H0V112C0 85.5 21.5 64 48 64H96V32c0-17.7 14.3-32 32-32zM0 192H448V464c0 26.5-21.5 48-48 48H48c-26.5 0-48-21.5-48-48V192zm64 80v32c0 8.8 7.2 16 16 16h32c8.8 0 16-7.2 16-16V272c0-8.8-7.2-16-16-16H80c-8.8 0-16 7.2-16 16zm128 0v32c0 8.8 7.2 16 16 16h32c8.8 0 16-7.2 16-16V272c0-8.8-7.2-16-16-16H208c-8.8 0-16 7.2-16 16zm144-16c-8.8 0-16 7.2-16 16v32c0 8.8 7.2 16 16 16h32c8.8 0 16-7.2 16-16V272c0-8.8-7.2-16-16-16H336zM64 400v32c0 8.8 7.2 16 16 16h32c8.8 0 16-7.2 16-16V400c0-8.8-7.2-16-16-16H80c-8.8 0-16 7.2-16 16zm144-16c-8.8 0-16 7.2-16 16v32c0 8.8 7.2 16 16 16h32c8.8 0 16-7.2 16-16V400c0-8.8-7.2-16-16-16H208zm112 16v32c0 8.8 7.2 16 16 16h32c8.8 0 16-7.2 16-16V400c0-8.8-7.2-16-16-16H336c-8.8 0-16 7.2-16 16z"/></svg>
              {{ esc_html($protocol_zile) }}
            </span>
          @endif

          <a href="{{ esc_url($permalink) }}" class="cart-recommended__name">
            <h4>{{ esc_html($product->get_name()) }}</h4>
          </a>

          @if (have_rows('informatie_generala', $product_id))
            @while (have_rows('informatie_generala', $product_id)) @php the_row() @endphp
              @if (have_rows('beneficii'))
                <ul class="benefits_product benefits_wrap">
                  @php $count = 0; @endphp
                  @while (have_rows('beneficii')) @php the_row() @endphp
                    @if ($count >= 3) @break @endif
                    <li>{{ esc_html(get_sub_field('denumire_beneficiu')) }}</li>
                    @php $count++ @endphp
                  @endwhile
                </ul>
              @endif
            @endwhile
          @endif

          <a href="{{ esc_url($add_url) }}"
             class="btn-primary add_to_cart_button ajax_add_to_cart"
             data-product_id="{{ $product->get_id() }}"
             data-product_sku="{{ esc_attr($product->get_sku()) }}"
             data-quantity="1"
             @if (! $cart_has_upsell)
               data-upsell_discount="1"
               data-_upsell_nonce="{{ $upsell_nonce }}"
             @endif>
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true">
              <path d="M2 3h2l3 13h12l2-9H6" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/>
              <circle cx="9" cy="20" r="1.4" fill="currentColor"/>
              <circle cx="17" cy="20" r="1.4" fill="currentColor"/>
            </svg>
            {{ __('Adaugă în coș', 'sage') }}
          </a>
        </div>
      </li>
    @endforeach
  </ul>
</section>
