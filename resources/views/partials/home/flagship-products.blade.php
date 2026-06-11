{{--
  Flagship products — primele 3 din $popular_packages (Home composer).
  Date din WooCommerce: titlu, link, imagine featured, preț, rating + nr. recenzii.
  Date statice per slot (până apare ACF pe produs): eyebrow, subtitlu, chip loyalty.
--}}
@php
  $packages = isset($popular_packages) && is_array($popular_packages) ? $popular_packages : [];
  $packages = array_slice($packages, 0, 3);

  // Etichetele per slot vin din ACF (grup Home → tab „Produse recomandate"),
  // cu fallback pe database/seeds/home.php. Produsele rămân dinamice.
  $slot_fallbacks = \App\home_field('flagship_slots') ?: [];

  $star_path = 'm12 2 3 7 7 1-5 5 1 7-6-3-6 3 1-7-5-5 7-1z';
@endphp

<section class="flagship">
  <div class="flagship-head">
    <div class="eyebrow">{{ \App\home_field('flagship_eyebrow') }}</div>
    <h2>{{ \App\home_field('flagship_titlu') }} <em>{{ \App\home_field('flagship_titlu_em') }}</em></h2>
  </div>

  @if (! empty($packages))
    <div class="flagship-grid">
      @foreach ($packages as $i => $product)
        @php
          $product_id = $product->get_id();
          $title = $product->get_name();
          $link = get_permalink($product_id);
          $thumb_id = get_post_thumbnail_id($product_id);
          $thumb_html = $thumb_id
            ? wp_get_attachment_image($thumb_id, 'large', false, [
                'alt' => esc_attr($title),
                'sizes' => '(max-width: 900px) 90vw, 380px',
                'loading' => 'lazy',
                'decoding' => 'async',
              ])
            : '<img src="' . esc_url(wc_placeholder_img_src()) . '" alt="' . esc_attr($title) . '" loading="lazy" decoding="async">';

          $price_html = $product->get_price_html();
          $short = wp_strip_all_tags($product->get_short_description());

          $avg = (float) $product->get_average_rating();
          $review_count = (int) $product->get_review_count();
          $filled = (int) round($avg);
          $filled = max(0, min(5, $filled));

          $slot = $slot_fallbacks[$i] ?? (! empty($slot_fallbacks) ? end($slot_fallbacks) : ['eyebrow_class' => 'green', 'eyebrow_text' => '']);
          $sub = $short !== '' ? $short : __('Formulat onest, dozaj măsurat, lot trasabil.', 'sage');

          // Subscription offer chip — real price + discount when the product is
          // subscription-enabled (mn-subscriptions plugin).
          $sub_enabled = class_exists('MN_Subs_Product') && MN_Subs_Product::is_enabled($product_id);
          $sub_price_html = '';
          $sub_pct_label = '';
          if ($sub_enabled) {
            $base_price = (float) $product->get_price();
            $sub_pct = MN_Subs_Product::discount_pct($product_id);
            $sub_price_html = wc_price(MN_Subs_Pricing::discounted_unit_price($base_price, $sub_pct));
            $sub_pct_label = rtrim(rtrim(number_format((float) $sub_pct, 2), '0'), '.');
          }
        @endphp

        <article class="prod-card">
          <a class="prod-photo" href="{{ esc_url($link) }}" aria-label="{{ esc_attr($title) }}">
            {!! $thumb_html !!}
          </a>
          <div class="prod-body">
            <span class="eyebrow-mini {{ $slot['eyebrow_class'] }}">{{ $slot['eyebrow_text'] }}</span>
            <h3><a href="{{ esc_url($link) }}">{{ $title }}</a></h3>
            @if ($sub)
              <p class="sub">{{ $sub }}</p>
            @endif

            @if ($review_count > 0)
              <div class="rating-mini">
                <div class="stars">
                  @for ($s = 1; $s <= 5; $s++)
                    @if ($s <= $filled)
                      <svg viewBox="0 0 24 24" fill="currentColor"><path d="{{ $star_path }}"/></svg>
                    @else
                      <svg class="empty" viewBox="0 0 24 24"><path d="{{ $star_path }}"/></svg>
                    @endif
                  @endfor
                </div>
                <strong>{{ number_format_i18n($avg, 1) }} / 5</strong>
                <span>({{ $review_count }})</span>
              </div>
            @endif

            <div class="price-row">
              <span class="price">{!! $price_html !!}</span>
            </div>

            @if ($sub_enabled)
              <span class="chip-loyalty">
                <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" aria-hidden="true">
                  <path d="M3 12a9 9 0 1 0 3-6.7"/><path d="M3 4v5h5"/>
                </svg>
                {!! sprintf(__('Abonament: %1$s · −%2$s%%', 'sage'), $sub_price_html, esc_html($sub_pct_label)) !!}
              </span>
            @endif

            <a class="lk" href="{{ esc_url($link) }}">
              {{ __('Vezi produsul', 'sage') }}
              <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="m9 6 6 6-6 6"/></svg>
            </a>
          </div>
        </article>
      @endforeach
    </div>
  @endif

  <div class="flagship-foot">
    <a href="{{ function_exists('wc_get_page_id') ? esc_url(get_permalink(wc_get_page_id('shop'))) : esc_url(home_url('/magazin/')) }}">
      {{ \App\home_field('flagship_foot') }}
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
        <path d="M5 12h14"/><path d="m12 5 7 7-7 7"/>
      </svg>
    </a>
  </div>
</section>
