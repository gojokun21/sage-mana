@php
  global $product;

  if (!is_a($product, WC_Product::class) || !$product->is_visible()) {
      return;
  }

  $product_id = $product->get_id();
  $title = $product->get_name();
  $link = get_permalink($product_id);
  $thumb_id = get_post_thumbnail_id($product_id);

  $thumb_html = $thumb_id
      ? wp_get_attachment_image($thumb_id, 'large', false, [
          'alt' => esc_attr($title),
          'sizes' => '(max-width: 640px) 90vw, 380px',
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
  $days = !empty($info_generala['protocol_zile']) ? (int) $info_generala['protocol_zile'] : 0;
  $forma = !empty($info_generala['forma']) ? trim((string) $info_generala['forma']) : '';
  $beneficii = !empty($info_generala['beneficii']) && is_array($info_generala['beneficii'])
      ? array_slice($info_generala['beneficii'], 0, 3)
      : [];

  // Badge "240 capsule · 120 zile" — formă + protocol_zile (numeric, zile de cură).
  $badge_parts = [];
  if ($forma) { $badge_parts[] = $forma; }
  if ($days > 0) { $badge_parts[] = sprintf(_n('%d zi', '%d zile', $days, 'sage'), $days); }
  $badge_text = implode(' · ', $badge_parts);

  // Primele 3 carduri din pagina shop (page 1) → CTA filled. Setat în shop-loop.blade.php.
  $is_featured = ! empty($GLOBALS['mn_pcard_featured']);

  $can_buy = $product->is_purchasable() && $product->is_in_stock();
  $can_ajax = $can_buy && $product->is_type('simple');

  $mn_brand = \App\resolve_product_brand($product);
  $mn_category = \App\resolve_product_category($product);

  // Chip „Abonament: <preț> · −X%" — preț + discount REALE când produsul are
  // abonament activ (plugin mn-subscriptions). Aceeași logică ca pe cardurile home.
  $sub_enabled = class_exists('MN_Subs_Product') && \MN_Subs_Product::is_enabled($product_id);
  $sub_price_html = '';
  $sub_pct_label = '';
  if ($sub_enabled) {
      $sub_pct = \MN_Subs_Product::discount_pct($product_id);
      $sub_price_html = wc_price(\MN_Subs_Pricing::discounted_unit_price((float) $product->get_price(), $sub_pct));
      $sub_pct_label = rtrim(rtrim(number_format((float) $sub_pct, 2), '0'), '.');
  }
@endphp

<article class="pcard{{ $is_featured ? ' featured' : '' }} {{ implode(' ', wc_get_product_class('', $product_id)) }}">
  <div class="favorite_btn pcard-fav-wrap">
    {!! \App\favorite_button($product_id) !!}
  </div>

  @if ($badge_text || $discount > 0)
    <div class="pcard-badges">
      @if ($badge_text)
        <span class="badge">{{ esc_html($badge_text) }}</span>
      @endif
      @if ($discount > 0)
        <span class="badge badge-discount">−{{ $discount }}%</span>
      @endif
    </div>
  @endif

  <a class="img" href="{{ esc_url($link) }}" aria-label="{{ esc_attr($title) }}">
    {!! $thumb_html !!}
  </a>

  <div class="body">
    <h3><a href="{{ esc_url($link) }}">{{ $title }}</a></h3>

    @if (!empty($beneficii))
      <ul>
        @foreach ($beneficii as $beneficiu)
          <li>{{ esc_html($beneficiu['denumire_beneficiu'] ?? '') }}</li>
        @endforeach
      </ul>
    @endif

    @if ($sub_enabled)
      <span class="chip-loyalty">
        <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" aria-hidden="true">
          <path d="M3 12a9 9 0 1 0 3-6.7"/><path d="M3 4v5h5"/>
        </svg>
        {!! sprintf(__('Abonament: %1$s · −%2$s%%', 'sage'), $sub_price_html, esc_html($sub_pct_label)) !!}
      </span>
    @endif

    <div class="foot">
      <span class="price">
        @if ($product->is_on_sale() && $sale_price != '')
          {!! wc_price($sale_price) !!}
          <del class="price-old">{!! wc_price($regular_price) !!}</del>
        @else
          {!! wc_price($regular_price) !!}
        @endif
      </span>

      @if ($can_buy)
        <a href="{{ esc_url($product->add_to_cart_url()) }}"
           class="cta mn-atc-btn product_type_{{ esc_attr($product->get_type()) }}{{ $can_ajax ? ' add_to_cart_button ajax_add_to_cart' : '' }}"
           data-product_id="{{ esc_attr($product_id) }}"
           data-product_sku="{{ esc_attr($product->get_sku()) }}"
           data-quantity="1"
           data-product_name="{{ $title }}"
           data-product_price="{{ esc_attr((string) wc_format_decimal($product->get_price(), wc_get_price_decimals())) }}"
           data-product_brand="{{ esc_attr($mn_brand) }}"
           @if ($mn_category) data-product_category="{{ esc_attr($mn_category) }}" @endif
           data-product_img="{{ esc_url(wp_get_attachment_image_url($product->get_image_id(), 'medium')) }}"
           data-product_url="{{ esc_url($link) }}"
           data-product_packaging="{{ wp_strip_all_tags($product->get_short_description()) }}"
           aria-label="{{ sprintf(__('Adaugă %s în coș', 'sage'), $title) }}"
           rel="nofollow">
          {{ esc_html($product->add_to_cart_text()) }}
        </a>
      @else
        <span class="cta cta-unavailable mn-unavail-btn"
              data-product_id="{{ esc_attr($product_id) }}"
              aria-disabled="true">
          {{ esc_html($product->get_type() === 'bundle' ? __('Indisponibil', 'sage') : __('Stoc epuizat', 'sage')) }}
        </span>
      @endif
    </div>
  </div>
</article>
