{{-- Single PACHET — hero (galerie stânga + info dreapta).
     Date REALE din WooCommerce: nume, galerie, produse componente (nume + preț
     individual + imagine), preț pachet, economisire vs. suma individuală,
     add-to-cart real. ACF (grup pachet, seed `natura:pachet-seed`): eyebrow,
     tagline; descrierea lungă vine din post_excerpt. STATIC: rating fallback,
     trust-row. --}}
@php
  global $product;

  $rating_count = $product->get_rating_count();
  $average = $product->get_average_rating();

  $pk_eyebrow = get_field('pk_eyebrow') ?: __('Pachet · cură completă', 'sage');
  $pk_tagline = get_field('pk_tagline');

  // Zile de cură (informatie_generala.protocol_zile) pentru sub-line-ul de preț.
  $pk_info = function_exists('get_field') ? get_field('informatie_generala', $product->get_id()) : null;
  $pk_days = (is_array($pk_info) && ! empty($pk_info['protocol_zile'])) ? (int) $pk_info['protocol_zile'] : 0;

  // Produsele componente ale bundle-ului (vezi și partials/pachete/card.blade.php).
  $items = [];
  $items_total = 0;

  foreach ($product->get_bundled_items() as $bi) {
      $p = $bi->get_product();
      if (! $p) {
          continue;
      }
      $qty = max(1, (int) $bi->get_quantity('min'));
      $line = (float) $p->get_price() * $qty;
      $items_total += $line;

      $meta = wp_strip_all_tags($p->get_short_description());
      if (function_exists('mb_strimwidth') && $meta !== '') {
          $meta = mb_strimwidth($meta, 0, 60, '…', 'UTF-8');
      }

      $items[] = [
          'name' => $p->get_name(),
          'meta' => $meta,
          'qty' => $qty,
          'price' => $line,
          'image_id' => $p->get_image_id(),
          'permalink' => get_permalink($p->get_id()),
      ];
  }

  $bundle_price = (float) $product->get_price();
  $saving = $items_total - $bundle_price;
  $count = count($items);
@endphp

<section class="pachet-hero">
  <div class="pachet-hero-inner">

    {{-- GALERIE (swiper + lightbox, prin product-image.php) --}}
    <div class="gallery">
      @php woocommerce_show_product_images() @endphp
    </div>

    {{-- INFO PACHET --}}
    <div class="pinfo">
      <span class="eyebrow">{{ $pk_eyebrow }}</span>

      <h1>{{ $product->get_name() }}</h1>

      @if ($pk_tagline)
        <p class="subline">{{ $pk_tagline }}</p>
      @endif

      @php $short = $product->get_short_description(); @endphp
      @if ($short)
        <div class="{{ $pk_tagline ? 'desc' : 'subline' }}">{!! apply_filters('woocommerce_short_description', $short) !!}</div>
      @elseif (! $pk_tagline)
        <p class="subline">{{ __('Două produse care lucrează împreună, într-o singură cură.', 'sage') }}</p>
      @endif

      <div class="rating">
        <span class="stars" aria-hidden="true">★★★★★</span>
        @if ($rating_count > 0)
          <span class="score">{{ number_format_i18n((float) $average, 1) }} {{ __('din 5', 'sage') }}</span>
          <span class="count">· {{ sprintf(_n('%s recenzie verificată', '%s recenzii verificate', $rating_count, 'sage'), number_format_i18n($rating_count)) }}</span>
        @else
          <span class="score">4,8 {{ __('din 5', 'sage') }}</span>
          <span class="count">· {{ __('recenzii verificate', 'sage') }}</span>
        @endif
      </div>

      {{-- Lista produselor din pachet — date reale --}}
      @if ($count > 0)
        <div class="bundle-list">
          @foreach ($items as $item)
            <a class="bundle-line" href="{{ esc_url($item['permalink']) }}">
              <span class="mini-ph">
                @if ($item['image_id'])
                  {!! wp_get_attachment_image($item['image_id'], 'woocommerce_gallery_thumbnail', false, [
                    'alt' => $item['name'],
                    'loading' => 'lazy',
                    'decoding' => 'async',
                  ]) !!}
                @endif
              </span>
              <span class="b-info">
                <span class="b-name">{{ $item['qty'] > 1 ? $item['qty'] . '× ' : '' }}{{ $item['name'] }}</span>
                @if ($item['meta'])
                  <span class="b-meta">{{ $item['meta'] }}</span>
                @endif
              </span>
              <span class="b-price">{{ number_format_i18n($item['price'], 0) }} {{ __('lei', 'sage') }}</span>
            </a>
          @endforeach
        </div>
      @endif

      {{-- Bloc preț pachet --}}
      <div class="price-block">
        <div class="row">
          <span class="lbl">{{ __('Preț pachet', 'sage') }}</span>
          <span class="now-price">{!! $product->get_price_html() !!}</span>
        </div>
        @if ($saving >= 1)
          <div class="save-row">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" aria-hidden="true"><path d="M20 6 9 17l-5-5"/></svg>
            {{ sprintf(__('Economisești %s lei față de cumpărarea separată', 'sage'), number_format_i18n($saving, 0)) }}
          </div>
        @endif
        <div class="sub-line">
          @if ($pk_days > 0)
            {{ sprintf(__('Cură completă de %s. Oprești oricând, fără obligativitate.', 'sage'), sprintf(_n('%d zi', '%d zile', $pk_days, 'sage'), $pk_days)) }}
          @else
            {{ __('Cură completă. Oprești oricând, fără obligativitate.', 'sage') }}
          @endif
        </div>
      </div>

      {{-- ADD-TO-CART REAL WooCommerce (form bundle + buton). --}}
      <div class="pachet-atc">
        @php woocommerce_template_single_add_to_cart() @endphp
      </div>

      <div class="trust-row">
        <div class="item"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true"><path d="M20 6 9 17l-5-5"/></svg>{{ __('Livrare 24–48h', 'sage') }}</div>
        <div class="item"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true"><path d="M20 6 9 17l-5-5"/></svg>{{ __('Retur 14 zile', 'sage') }}</div>
        <div class="item"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true"><path d="M20 6 9 17l-5-5"/></svg>{{ __('Plata la livrare', 'sage') }}</div>
      </div>
    </div>
  </div>
</section>
