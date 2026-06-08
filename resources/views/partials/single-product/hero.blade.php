{{-- PDP hero — galerie (stânga) + info produs (dreapta).
     Date REALE: titlu, preț, descriere scurtă, add-to-cart WooCommerce.
     STATIC (placeholder ACF): eyebrow, subline, rating, sub-options, bulk-note, trust-row. --}}
@php
  global $product;
  $rating_count = $product->get_rating_count();
  $average = $product->get_average_rating();
@endphp

<section class="pdp-hero">
  <div class="pdp-hero-inner">

    {{-- GALERIE (swiper + lightbox, prin product-image.php) --}}
    <div class="gallery">
      @php woocommerce_show_product_images() @endphp
    </div>

    {{-- INFO PRODUS --}}
    <div class="pinfo">
      <span class="eyebrow">{{ __('Capsule · imunitate & sănătatea inimii', 'sage') }}</span>

      <h1>{{ $product->get_name() }}</h1>

      <p class="subline">{{ __('ulei de chimen negru egiptean presat la rece · vitamina E naturală · 240 capsule · 4 luni.', 'sage') }}</p>

      <div class="rating">
        <span class="stars" aria-hidden="true">★★★★★</span>
        @if ($rating_count > 0)
          <span>{{ number_format_i18n((float) $average, 1) }} / 5</span>
          <span>· {{ sprintf(_n('%s recenzie verificată', '%s recenzii verificate', $rating_count, 'sage'), number_format_i18n($rating_count)) }}</span>
        @else
          <span>4,9 / 5</span>
          <span>· {{ __('5 recenzii verificate', 'sage') }}</span>
        @endif
      </div>

      @php $short = $product->get_short_description(); @endphp
      @if ($short)
        <div class="desc">{!! apply_filters('woocommerce_short_description', $short) !!}</div>
      @else
        <p class="desc">{!! wp_kses(__('<strong>Ulei de Nigella sativa egiptean încapsulat.</strong> Vitamina E naturală. 4 luni de susținere pentru imunitate, inimă și echilibru metabolic.', 'sage'), ['strong' => []]) !!}</p>
      @endif

      <div class="price-box">
        <span class="lbl">{{ __('Preț', 'sage') }}</span>
        <span class="amount">{!! $product->get_price_html() !!}</span>
        <span class="meta">{{ __('Stoc disponibil', 'sage') }}</span>
      </div>

      {{-- ADD-TO-CART REAL WooCommerce (qty + buton AJAX / variații). --}}
      <div class="pdp-atc">
        @php woocommerce_template_single_add_to_cart() @endphp
      </div>

      <div class="bulk-note">{{ __('Reduceri la cantitate: 3 buc −5% · 5 buc −10%', 'sage') }}</div>

      <div class="trust-row">
        <div class="item"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true"><path d="M20 6 9 17l-5-5"/></svg>{{ __('Livrare gratuită peste 199 lei', 'sage') }}</div>
        <div class="item"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true"><path d="M20 6 9 17l-5-5"/></svg>{{ __('Livrare 24–48h', 'sage') }}</div>
        <div class="item"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true"><path d="M20 6 9 17l-5-5"/></svg>{{ __('Analize de lot publice', 'sage') }}</div>
        <div class="item"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true"><path d="M20 6 9 17l-5-5"/></svg>{{ __('Stoc disponibil', 'sage') }}</div>
      </div>
    </div>
  </div>
</section>
