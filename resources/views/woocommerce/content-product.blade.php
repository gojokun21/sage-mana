@php
  global $product;

  if (!is_a($product, WC_Product::class) || !$product->is_visible()) {
      return;
  }

  $product_id = $product->get_id();
  $title = $product->get_name();
  $link = get_permalink($product_id);
  $thumb_id = get_post_thumbnail_id($product_id);

  // Standard WP responsive image — srcset + sizes emitted automatically from
  // the `large` base across every registered image size. Lets TinyPNG (or any
  // other image optimizer plugin) see a normal `<img>` through the
  // `wp_get_attachment_image` filter and do its WebP/optimization work the
  // way it expects. If the plugin misbehaves, the fix belongs in the plugin
  // settings (or the plugin gets disabled), not in theme-side workarounds.
  $thumb_html = $thumb_id
      ? wp_get_attachment_image($thumb_id, 'large', false, [
          'alt' => esc_attr($title),
          'sizes' => '(max-width: 640px) 45vw, 260px',
          'loading' => 'lazy',
          'decoding' => 'async',
      ])
      : '<img src="' . esc_url(wc_placeholder_img_src()) . '" alt="' . esc_attr($title) . '" loading="lazy" decoding="async">';

  $regular_price = $product->get_regular_price();
  $sale_price = $product->get_sale_price();
  $discount = 0;

  if ($product->is_on_sale() && $regular_price > 0 && $sale_price != '') {
      $discount = round((($regular_price - $sale_price) / $regular_price) * 100);
  }

  $info_generala = get_field('informatie_generala', $product_id);
  $protocol_zile = !empty($info_generala['protocol_zile']) ? $info_generala['protocol_zile'] : '';
  $beneficii = !empty($info_generala['beneficii']) && is_array($info_generala['beneficii'])
      ? array_slice($info_generala['beneficii'], 0, 3)
      : [];
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
      {!! $thumb_html !!}
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

    @if (!empty($beneficii))
      <ul class="benefits_product benefits_wrap">
        @foreach ($beneficii as $beneficiu)
          <li>{{ esc_html($beneficiu['denumire_beneficiu'] ?? '') }}</li>
        @endforeach
      </ul>
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
        <svg class="cart-shopping" width="15" height="15" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
          <path d="M0.5625 1.125C0.250781 1.125 0 1.37578 0 1.6875C0 1.99922 0.250781 2.25 0.5625 2.25H1.62422C1.71563 2.25 1.79297 2.31562 1.80937 2.40469L3.03047 9.11484C3.17578 9.91641 3.87422 10.5 4.68984 10.5H10.6875C10.9992 10.5 11.25 10.2492 11.25 9.9375C11.25 9.62578 10.9992 9.375 10.6875 9.375H4.68984C4.41797 9.375 4.18594 9.18047 4.13672 8.91328L4.01719 8.25H11.1328C11.8547 8.25 12.4734 7.73672 12.607 7.02656L13.3336 3.13828C13.4203 2.67656 13.0664 2.25 12.5953 2.25H2.92266L2.91328 2.20312C2.80078 1.57969 2.25703 1.125 1.62187 1.125H0.5625ZM4.875 13.5C5.49609 13.5 6 12.9961 6 12.375C6 11.7539 5.49609 11.25 4.875 11.25C4.25391 11.25 3.75 11.7539 3.75 12.375C3.75 12.9961 4.25391 13.5 4.875 13.5ZM10.125 13.5C10.7461 13.5 11.25 12.9961 11.25 12.375C11.25 11.7539 10.7461 11.25 10.125 11.25C9.50391 11.25 9 11.7539 9 12.375C9 12.9961 9.50391 13.5 10.125 13.5Z" fill="currentColor"/>
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
