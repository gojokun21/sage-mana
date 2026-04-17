@php
  global $product;

  if (!is_a($product, WC_Product::class) || !$product->is_visible()) {
      return;
  }

  $product_id = $product->get_id();
  $title = $product->get_name();
  $link = get_permalink($product_id);
  $image = wp_get_attachment_image_src(get_post_thumbnail_id($product_id), 'large');

  $regular_price = $product->get_regular_price();
  $sale_price = $product->get_sale_price();
  $discount = 0;

  if ($product->is_on_sale() && $regular_price > 0 && $sale_price != '') {
      $discount = round((($regular_price - $sale_price) / $regular_price) * 100);
  }

  $info_generala = get_field('informatie_generala', $product_id);
  $protocol_zile = !empty($info_generala['protocol_zile']) ? $info_generala['protocol_zile'] : '';
@endphp

<li class="product product-card {{ implode(' ', wc_get_product_class('', $product_id)) }}">
  <div class="product_img position-relative">
    @if ($discount > 0)
      <span class="price_discount">-{{ $discount }}%</span>
    @endif
    <div class="favorite_btn">
      {!! \App\favorite_button($product_id) !!}
    </div>
    <a href="{{ esc_url($link) }}">
      <img src="{{ esc_url($image[0] ?? '') }}" alt="{{ esc_attr($title) }}">
    </a>
  </div>

  <div class="product_content">
    <div class="price_box">
      @if ($product->is_on_sale() && $sale_price != '')
        <span class="price_new">{!! wc_price($sale_price) !!}</span>
        <span class="price_old">{!! wc_price($regular_price) !!}</span>
      @else
        <span class="price_regular">{!! wc_price($regular_price) !!}</span>
      @endif
    </div>

    @if ($protocol_zile)
      <span class="protocol_badge">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 448 512" fill="currentColor"><path d="M128 0c17.7 0 32 14.3 32 32V64H288V32c0-17.7 14.3-32 32-32s32 14.3 32 32V64h48c26.5 0 48 21.5 48 48v48H0V112C0 85.5 21.5 64 48 64H96V32c0-17.7 14.3-32 32-32zM0 192H448V464c0 26.5-21.5 48-48 48H48c-26.5 0-48-21.5-48-48V192zm64 80v32c0 8.8 7.2 16 16 16h32c8.8 0 16-7.2 16-16V272c0-8.8-7.2-16-16-16H80c-8.8 0-16 7.2-16 16zm128 0v32c0 8.8 7.2 16 16 16h32c8.8 0 16-7.2 16-16V272c0-8.8-7.2-16-16-16H208c-8.8 0-16 7.2-16 16zm144-16c-8.8 0-16 7.2-16 16v32c0 8.8 7.2 16 16 16h32c8.8 0 16-7.2 16-16V272c0-8.8-7.2-16-16-16H336zM64 400v32c0 8.8 7.2 16 16 16h32c8.8 0 16-7.2 16-16V400c0-8.8-7.2-16-16-16H80c-8.8 0-16 7.2-16 16zm144-16c-8.8 0-16 7.2-16 16v32c0 8.8 7.2 16 16 16h32c8.8 0 16-7.2 16-16V400c0-8.8-7.2-16-16-16H208zm112 16v32c0 8.8 7.2 16 16 16h32c8.8 0 16-7.2 16-16V400c0-8.8-7.2-16-16-16H336c-8.8 0-16 7.2-16 16z"/></svg>
        {{ esc_html($protocol_zile) }}
      </span>
    @endif

    <h3 class="product_title">
      <a href="{{ esc_url($link) }}">{{ esc_html($title) }}</a>
    </h3>

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

    @php
      $can_buy = $product->is_purchasable() && $product->is_in_stock();
      // Only simple products can be AJAX-added from a loop without choosing
      // variations or bundle items — everything else routes to the product page.
      $can_ajax = $can_buy && $product->is_type('simple');
    @endphp

    @if ($can_buy)
      <a href="{{ esc_url($product->add_to_cart_url()) }}"
         class="btn-primary mn-atc-btn product_type_{{ esc_attr($product->get_type()) }}{{ $can_ajax ? ' add_to_cart_button ajax_add_to_cart' : '' }}"
         data-product_id="{{ esc_attr($product_id) }}"
         data-product_sku="{{ esc_attr($product->get_sku()) }}"
         data-quantity="1"
         data-product_name="{{ esc_attr($title) }}"
         data-product_img="{{ esc_url(wp_get_attachment_image_url($product->get_image_id(), 'medium')) }}"
         data-product_url="{{ esc_url($link) }}"
         data-product_packaging="{{ wp_strip_all_tags($product->get_short_description()) }}"
         aria-label="{{ esc_attr(sprintf(__('Adaugă %s în coș', 'sage'), $title)) }}"
         rel="nofollow">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true">
          <path d="M2 3h2l3 13h12l2-9H6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
          <circle cx="9" cy="20" r="1.5" fill="currentColor"/>
          <circle cx="17" cy="20" r="1.5" fill="currentColor"/>
        </svg>
        {{ esc_html($product->add_to_cart_text()) }}
      </a>
    @else
      <span class="btn-primary btn-unavailable mn-unavail-btn"
            data-product_id="{{ esc_attr($product_id) }}"
            aria-disabled="true">
        {{ esc_html($product->get_type() === 'bundle' ? __('Pachet indisponibil', 'sage') : __('Stoc epuizat', 'sage')) }}
      </span>
    @endif
  </div>
</li>
