{{--
  Mega-meniu „După obiectiv" — structură identică cu mega-simptom (reutilizează
  `.ms-mega`/`.ms-grid`/`.ms-col`/`.symptom`/`.ms-bottom`), plus un rând de carduri
  featured (`.ms-fr`) și un link „Vezi toate obiectivele".
  Link-urile pe obiectiv se rezolvă la categoria de produs (product_cat) după slug
  (ca în partials/shop/sidebar.blade.php); fallback pe catalog dacă nu există.
--}}
@php
  $shop_url = function_exists('wc_get_page_permalink') ? wc_get_page_permalink('shop') : home_url('/suplimente-alimentare/');

  // Rezolvă: pagina de obiectiv (copil sub /dupa-obiectiv/) după slug >
  // url explicit > categorie product_cat > catalog.
  $mo_url = static function ($item) use ($shop_url) {
      if (! empty($item['slug'])) {
          $pg = get_page_by_path('dupa-obiectiv/' . $item['slug'], OBJECT, 'page');
          if ($pg) {
              return get_permalink($pg);
          }
      }
      if (! empty($item['url'])) {
          return $item['url'];
      }
      if (! empty($item['cat'])) {
          $term = get_term_by('slug', $item['cat'], 'product_cat');
          if ($term && ! is_wp_error($term)) {
              $link = get_term_link($term);
              if (! is_wp_error($link)) {
                  return $link;
              }
          }
      }
      return $shop_url;
  };

  // Iconițe Font Awesome 6 Free (solid), inline ca SVG cu clasă `.leaf`.
  $fa = [
      'bolt' => '<svg class="leaf" viewBox="0 0 448 512" fill="currentColor" aria-hidden="true"><path d="M349.4 44.6c5.9-13.7 1.5-29.7-10.6-38.5s-28.6-8-39.9 1.8l-256 224c-10 8.8-13.6 22.9-8.9 35.3S50.7 288 64 288l111.5 0L98.6 467.4c-5.9 13.7-1.5 29.7 10.6 38.5s28.6 8 39.9-1.8l256-224c10-8.8 13.6-22.9 8.9-35.3s-16.6-20.7-30-20.7l-111.5 0L349.4 44.6z"/></svg>',
      'hourglass' => '<svg class="leaf" viewBox="0 0 384 512" fill="currentColor" aria-hidden="true"><path d="M32 0C14.3 0 0 14.3 0 32S14.3 64 32 64l0 11c0 42.4 16.9 83.1 46.9 113.1L146.7 256 78.9 323.9C48.9 353.9 32 394.6 32 437l0 11c-17.7 0-32 14.3-32 32s14.3 32 32 32l32 0 256 0 32 0c17.7 0 32-14.3 32-32s-14.3-32-32-32l0-11c0-42.4-16.9-83.1-46.9-113.1L237.3 256l67.9-67.9c30-30 46.9-70.7 46.9-113.1l0-11c17.7 0 32-14.3 32-32s-14.3-32-32-32L320 0 64 0 32 0zM96 75l0-11 192 0 0 11c0 19-5.6 37.4-16 53L112 128c-10.3-15.6-16-34-16-53zm16 309c3.5-5.3 7.6-10.3 12.1-14.9L192 301.3l67.9 67.9c4.6 4.6 8.6 9.6 12.1 14.9L112 384z"/></svg>',
      'droplet' => '<svg class="leaf" viewBox="0 0 384 512" fill="currentColor" aria-hidden="true"><path d="M192 512C86 512 0 426 0 320C0 228.8 130.2 57.7 166.6 11.7C172.6 4.2 181.5 0 191.1 0l1.8 0c9.6 0 18.5 4.2 24.5 11.7C253.8 57.7 384 228.8 384 320c0 106-86 192-192 192zM96 336c0-8.8-7.2-16-16-16s-16 7.2-16 16c0 61.9 50.1 112 112 112c8.8 0 16-7.2 16-16s-7.2-16-16-16c-44.2 0-80-35.8-80-80z"/></svg>',
      'bacterium' => '<svg class="leaf" viewBox="0 0 512 512" fill="currentColor" aria-hidden="true"><path d="M423.1 30.6c3.6-12.7-3.7-26-16.5-29.7s-26 3.7-29.7 16.5l-4.2 14.7c-9.8-.4-19.9 .5-29.9 2.8c-12.1 2.8-23.7 5.9-34.9 9.4l-5.9-13.7c-5.2-12.2-19.3-17.8-31.5-12.6s-17.8 19.3-12.6 31.5l4.9 11.3c-22 9.4-42 20.1-60.2 31.8L196 82.7c-7.4-11-22.3-14-33.3-6.7s-14 22.3-6.7 33.3l7.8 11.6c-18 15-33.7 30.8-47.3 47.1L103 157.3c-10.4-8.3-25.5-6.6-33.7 3.7s-6.6 25.5 3.7 33.7l15 12c-2.1 3.2-4.1 6.5-6 9.7c-9.4 15.7-17 31-23.2 45.3l-9.9-3.9c-12.3-4.9-26.3 1.1-31.2 13.4s1.1 26.3 13.4 31.2l11.6 4.6c-.3 1.1-.6 2.1-.9 3.1c-3.5 12.5-5.7 23.2-7.1 31.3c-.7 4.1-1.2 7.5-1.6 10.3c-.2 1.4-.3 2.6-.4 3.6l-.1 1.4-.1 .6 0 .3 0 .1c0 0 0 .1 39.2 3.7c0 0 0 0 0 0l-39.2-3.6c-.5 5-.6 10-.4 14.9l-14.7 4.2C4.7 380.6-2.7 393.8 .9 406.6s16.9 20.1 29.7 16.5l13.8-3.9c10.6 20.7 27.6 37.8 48.5 48.5l-3.9 13.7c-3.6 12.7 3.7 26 16.5 29.7s26-3.7 29.7-16.5l4.2-14.7c23.8 1 46.3-5.5 65.1-17.6L215 473c9.4 9.4 24.6 9.4 33.9 0s9.4-24.6 0-33.9l-10.6-10.6c9.1-14.1 15.1-30.5 17-48.3l.1-.8c.3-1.7 1-5.1 2.3-9.8l.2-.8 12.6 5.4c12.2 5.2 26.3-.4 31.5-12.6s-.4-26.3-12.6-31.5l-11.3-4.8c9.9-14.9 24.9-31.6 48.6-46l2.1 7.5c3.6 12.7 16.9 20.1 29.7 16.5s20.1-16.9 16.5-29.7L371 259.2c6.9-2.2 14.3-4.3 22.2-6.1c12.9-3 24.7-8 35.2-14.8L439 249c9.4 9.4 24.6 9.4 33.9 0s9.4-24.6 0-33.9l-10.6-10.6c12.2-19 18.6-41.6 17.6-65.1l14.7-4.2c12.7-3.6 20.1-16.9 16.5-29.7s-16.9-20.1-29.7-16.5l-13.7 3.9c-10.8-21.2-28-38-48.5-48.5l3.9-13.8zM92.1 363.3s0 0 0 0L144 368l-51.9-4.7zM112 320a48 48 0 1 1 96 0 48 48 0 1 1 -96 0zM240 184a24 24 0 1 1 0 48 24 24 0 1 1 0-48z"/></svg>',
      'brain' => '<svg class="leaf" viewBox="0 0 512 512" fill="currentColor" aria-hidden="true"><path d="M184 0c30.9 0 56 25.1 56 56l0 400c0 30.9-25.1 56-56 56c-28.9 0-52.7-21.9-55.7-50.1c-5.2 1.4-10.7 2.1-16.3 2.1c-35.3 0-64-28.7-64-64c0-7.4 1.3-14.6 3.6-21.2C21.4 367.4 0 338.2 0 304c0-31.9 18.7-59.5 45.8-72.3C37.1 220.8 32 207 32 192c0-30.7 21.6-56.3 50.4-62.6C80.8 123.9 80 118 80 112c0-29.9 20.6-55.1 48.3-62.1C131.3 21.9 155.1 0 184 0zM328 0c28.9 0 52.6 21.9 55.7 49.9c27.8 7 48.3 32.1 48.3 62.1c0 6-.8 11.9-2.4 17.4c28.8 6.2 50.4 31.9 50.4 62.6c0 15-5.1 28.8-13.8 39.7C493.3 244.5 512 272.1 512 304c0 34.2-21.4 63.4-51.6 74.8c2.3 6.6 3.6 13.8 3.6 21.2c0 35.3-28.7 64-64 64c-5.6 0-11.1-.7-16.3-2.1c-3 28.2-26.8 50.1-55.7 50.1c-30.9 0-56-25.1-56-56l0-400c0-30.9 25.1-56 56-56z"/></svg>',
      'dumbbell' => '<svg class="leaf" viewBox="0 0 640 512" fill="currentColor" aria-hidden="true"><path d="M96 64c0-17.7 14.3-32 32-32l32 0c17.7 0 32 14.3 32 32l0 160 0 64 0 160c0 17.7-14.3 32-32 32l-32 0c-17.7 0-32-14.3-32-32l0-64-32 0c-17.7 0-32-14.3-32-32l0-64c-17.7 0-32-14.3-32-32s14.3-32 32-32l0-64c0-17.7 14.3-32 32-32l32 0 0-64zm448 0l0 64 32 0c17.7 0 32 14.3 32 32l0 64c17.7 0 32 14.3 32 32s-14.3 32-32 32l0 64c0 17.7-14.3 32-32 32l-32 0 0 64c0 17.7-14.3 32-32 32l-32 0c-17.7 0-32-14.3-32-32l0-160 0-64 0-160c0-17.7 14.3-32 32-32l32 0c17.7 0 32 14.3 32 32zM416 224l0 64-192 0 0-64 192 0z"/></svg>',
      'shield' => '<svg class="leaf" viewBox="0 0 512 512" fill="currentColor" aria-hidden="true"><path d="M256 0c4.6 0 9.2 1 13.4 2.9L457.7 82.8c22 9.3 38.4 31 38.3 57.2c-.5 99.2-41.3 280.7-213.6 363.2c-16.7 8-36.1 8-52.8 0C57.3 420.7 16.5 239.2 16 140c-.1-26.2 16.3-47.9 38.3-57.2L242.7 2.9C246.8 1 251.4 0 256 0zm0 66.8l0 378.1C394 378 431.1 230.1 432 141.4L256 66.8s0 0 0 0z"/></svg>',
      'heart-pulse' => '<svg class="leaf" viewBox="0 0 512 512" fill="currentColor" aria-hidden="true"><path d="M228.3 469.1L47.6 300.4c-4.2-3.9-8.2-8.1-11.9-12.4l87 0c22.6 0 43-13.6 51.7-34.5l10.5-25.2 49.3 109.5c3.8 8.5 12.1 14 21.4 14.1s17.8-5 22-13.3L320 253.7l1.7 3.4c9.5 19 28.9 31 50.1 31l104.5 0c-3.7 4.3-7.7 8.5-11.9 12.4L283.7 469.1c-7.5 7-17.4 10.9-27.7 10.9s-20.2-3.9-27.7-10.9zM503.7 240l-132 0c-3 0-5.8-1.7-7.2-4.4l-23.2-46.3c-4.1-8.1-12.4-13.3-21.5-13.3s-17.4 5.1-21.5 13.3l-41.4 82.8L205.9 158.2c-3.9-8.7-12.7-14.3-22.2-14.1s-18.1 5.9-21.8 14.8l-31.8 76.3c-1.2 3-4.2 4.9-7.4 4.9L16 240c-2.6 0-5 .4-7.3 1.1C3 225.2 0 208.2 0 190.9l0-5.8c0-69.9 50.5-129.5 119.4-141C165 36.5 211.4 51.4 244 84l12 12 12-12c32.6-32.6 79-47.5 124.6-39.9C461.5 55.6 512 115.2 512 185.1l0 5.8c0 16.9-2.8 33.5-8.3 49.1z"/></svg>',
      'spa' => '<svg class="leaf" viewBox="0 0 576 512" fill="currentColor" aria-hidden="true"><path d="M183.1 235.3c33.7 20.7 62.9 48.1 85.8 80.5c7 9.9 13.4 20.3 19.1 31c5.7-10.8 12.1-21.1 19.1-31c22.9-32.4 52.1-59.8 85.8-80.5C437.6 207.8 490.1 192 546 192l9.9 0c11.1 0 20.1 9 20.1 20.1C576 360.1 456.1 480 308.1 480L288 480l-20.1 0C119.9 480 0 360.1 0 212.1C0 201 9 192 20.1 192l9.9 0c55.9 0 108.4 15.8 153.1 43.3zM301.5 37.6c15.7 16.9 61.1 71.8 84.4 164.6c-38 21.6-71.4 50.8-97.9 85.6c-26.5-34.8-59.9-63.9-97.9-85.6c23.2-92.8 68.6-147.7 84.4-164.6C278 33.9 282.9 32 288 32s10 1.9 13.5 5.6z"/></svg>',
      'bone' => '<svg class="leaf" viewBox="0 0 576 512" fill="currentColor" aria-hidden="true"><path d="M153.7 144.8c6.9 16.3 20.6 31.2 38.3 31.2l192 0c17.7 0 31.4-14.9 38.3-31.2C434.4 116.1 462.9 96 496 96c44.2 0 80 35.8 80 80c0 30.4-17 56.9-42 70.4c-3.6 1.9-6 5.5-6 9.6s2.4 7.7 6 9.6c25 13.5 42 40 42 70.4c0 44.2-35.8 80-80 80c-33.1 0-61.6-20.1-73.7-48.8C415.4 350.9 401.7 336 384 336l-192 0c-17.7 0-31.4 14.9-38.3 31.2C141.6 395.9 113.1 416 80 416c-44.2 0-80-35.8-80-80c0-30.4 17-56.9 42-70.4c3.6-1.9 6-5.5 6-9.6s-2.4-7.7-6-9.6C17 232.9 0 206.4 0 176c0-44.2 35.8-80 80-80c33.1 0 61.6 20.1 73.7 48.8z"/></svg>',
  ];

  $columns = [
      ['label' => __('ENERGIE & VITALITATE', 'sage'), 'items' => [
          ['ttl' => __('Mai multă energie zilnică', 'sage'), 'micro' => __('B-complex, Q10, magneziu, mitocondrii', 'sage'), 'slug' => 'energie', 'cat' => 'energie', 'leaf' => $fa['bolt']],
          ['ttl' => __('Anti-aging și longevitate', 'sage'), 'micro' => __('Antioxidanți, resveratrol, NAD+', 'sage'), 'slug' => 'anti-aging', 'leaf' => $fa['hourglass']],
      ]],
      ['label' => __('CORP & METABOLISM', 'sage'), 'items' => [
          ['ttl' => __('Detoxifiere și curățare', 'sage'), 'micro' => __('Silimarină, chlorella, suport hepatic', 'sage'), 'slug' => 'detoxifiere', 'cat' => 'detoxifiere', 'leaf' => $fa['droplet']],
          ['ttl' => __('Sănătate intestinală', 'sage'), 'micro' => __('Probiotice, fibre, enzime digestive', 'sage'), 'slug' => 'sanatate-intestinala', 'cat' => 'digestiv', 'leaf' => $fa['bacterium']],
      ]],
      ['label' => __('MINTE & PERFORMANȚĂ', 'sage'), 'items' => [
          ['ttl' => __('Focus și claritate mentală', 'sage'), 'micro' => __('Lion\'s Mane, B12, omega-3, colina', 'sage'), 'slug' => 'focus', 'cat' => 'focus', 'leaf' => $fa['brain']],
          ['ttl' => __('Performanță sportivă', 'sage'), 'micro' => __('Proteină, creatină, electroliți, BCAA', 'sage'), 'slug' => 'performanta-sportiva', 'leaf' => $fa['dumbbell']],
      ]],
      ['label' => __('APĂRARE & PROTECȚIE', 'sage'), 'items' => [
          ['ttl' => __('Imunitate puternică', 'sage'), 'micro' => __('Vit C, D3, zinc, timochinonă, echinaceea', 'sage'), 'slug' => 'imunitate', 'cat' => 'imunitate', 'leaf' => $fa['shield']],
          ['ttl' => __('Sănătatea inimii', 'sage'), 'micro' => __('Omega-3, CoQ10, magneziu, K2', 'sage'), 'slug' => 'sanatatea-inimii', 'leaf' => $fa['heart-pulse']],
      ]],
      ['label' => __('ESTETIC & STRUCTURĂ', 'sage'), 'items' => [
          ['ttl' => __('Frumusețe — piele, păr, unghii', 'sage'), 'micro' => __('Colagen, biotină, seleniu, zinc', 'sage'), 'slug' => 'frumusete', 'cat' => 'frumusete', 'leaf' => $fa['spa']],
          ['ttl' => __('Oase și articulații', 'sage'), 'micro' => __('Colagen II, calciu, vitamina D, MSM', 'sage'), 'slug' => 'oase-articulatii', 'leaf' => $fa['bone']],
      ]],
  ];

  // „Pick-ul lunii" — produsul real din WooCommerce (slug `pachet-regenerare-celulara`).
  // Titlul, linia de conținut (short description) și prețul vin live din produs;
  // fallback pe textele curatate dacă produsul lipsește / e nepublicat.
  $pick = get_page_by_path('pachet-regenerare-celulara', OBJECT, 'product');
  $pick_product = ($pick && function_exists('wc_get_product')) ? wc_get_product($pick->ID) : null;
  $pick_visible = $pick_product && $pick_product->is_visible();

  $pick_url = $pick_visible ? get_permalink($pick->ID) : $shop_url;
  $pick_title = $pick_visible ? $pick_product->get_name() : __('Pachet Regenerare Celulară', 'sage');

  $pick_img = '';
  if ($pick_visible) {
      // Conținut scurt (din short description) + preț formatat WooCommerce.
      $pick_excerpt = trim(wp_strip_all_tags($pick_product->get_short_description()));
      $pick_excerpt = $pick_excerpt !== '' ? wp_trim_words($pick_excerpt, 10, '…') : '';
      // get_price_html() conține &nbsp; între sumă și „lei"; decodăm entitățile și
      // normalizăm spațiul insecabil într-un spațiu normal ca să nu apară literal „&nbsp;".
      $pick_price = trim(wp_strip_all_tags(
          str_replace("\xC2\xA0", ' ', html_entity_decode($pick_product->get_price_html(), ENT_QUOTES, 'UTF-8'))
      ));
      $pick_micro = implode(' · ', array_filter([$pick_excerpt, $pick_price]));

      // Imaginea produsului (înlocuiește glyph-ul de stea); fallback pe stea dacă lipsește.
      $pick_img_id = $pick_product->get_image_id();
      $pick_img = $pick_img_id
          ? wp_get_attachment_image($pick_img_id, 'woocommerce_thumbnail', false, [
              'class' => 'fc-img',
              'alt' => $pick_title,
              'loading' => 'lazy',
              'decoding' => 'async',
          ])
          : '';
  } else {
      $pick_micro = '';
  }
  if ($pick_micro === '') {
      $pick_micro = __('Colagen + multivitamine + Black Seed · 524 lei', 'sage');
  }
@endphp

<div class="ms-mega ms-mega--obiectiv">
  <div class="ms-grid">
    @foreach ($columns as $col)
      <div class="ms-col">
        <h4><span class="swatch"></span>{{ $col['label'] }}</h4>
        <ul>
          @foreach ($col['items'] as $it)
            <li>
              <a class="symptom" href="{{ esc_url($mo_url($it)) }}">
                {!! $it['leaf'] !!}
                <span class="body">
                  <span class="ttl">{{ $it['ttl'] }}</span>
                  <span class="micro">{{ $it['micro'] }}</span>
                </span>
                <svg class="arrow" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="m9 6 6 6-6 6"/></svg>
              </a>
            </li>
          @endforeach
        </ul>
      </div>
    @endforeach
  </div>

  {{-- Rând featured: 2 carduri --}}
  <div class="ms-fr">
    <a class="ms-fc ms-fc--pick" href="{{ esc_url($pick_url) }}">
      <span class="photo{{ $pick_img ? ' has-img' : '' }}">
        @if ($pick_img)
          {!! $pick_img !!}
        @else
          <svg viewBox="0 0 576 512" fill="currentColor" aria-hidden="true"><path d="M316.9 18C311.6 7 300.4 0 288.1 0s-23.4 7-28.8 18L195 150.3 51.4 171.5c-12 1.8-22 10.2-25.7 21.7s-.7 24.2 7.9 32.7L137.8 329 113.2 474.7c-2 12 3 24.2 12.9 31.3s23 8 33.8 2.3l128.3-68.5 128.3 68.5c10.8 5.7 23.9 4.9 33.8-2.3s14.9-19.3 12.9-31.3L438.5 329 542.7 225.9c8.6-8.5 11.7-21.2 7.9-32.7s-13.7-19.9-25.7-21.7L381.2 150.3 316.9 18z"/></svg>
        @endif
      </span>
      <span class="body">
        <span class="fc-eye">{{ __('Pick-ul lunii', 'sage') }}</span>
        <span class="fc-ttl">{{ $pick_title }}</span>
        <span class="fc-micro">{{ $pick_micro }}</span>
      </span>
      <span class="cta-terra">{{ __('Vezi', 'sage') }}
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="m9 6 6 6-6 6"/></svg>
      </span>
    </a>
    <a class="ms-fc ms-fc--quiz" href="{{ esc_url(home_url('/test/')) }}">
      <span class="photo quiz">
        <svg viewBox="0 0 512 512" fill="currentColor" aria-hidden="true"><path d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM169.8 165.3c7.9-22.3 29.1-37.3 52.8-37.3l58.3 0c34.9 0 63.1 28.3 63.1 63.1c0 22.6-12.1 43.5-31.7 54.8L280 264.4c-.2 13-10.9 23.6-24 23.6c-13.3 0-24-10.7-24-24l0-13.5c0-8.6 4.6-16.5 12.1-20.8l44.3-25.4c4.7-2.7 7.6-7.7 7.6-13.1c0-8.4-6.8-15.1-15.1-15.1l-58.3 0c-3.4 0-6.4 2.1-7.5 5.3l-.4 1.2c-4.4 12.5-18.2 19-30.6 14.6s-19-18.2-14.6-30.6l.4-1.2zM224 352a32 32 0 1 1 64 0 32 32 0 1 1 -64 0z"/></svg>
      </span>
      <span class="body">
        <span class="fc-eye">{{ __('Nu știi de unde începi?', 'sage') }}</span>
        <span class="fc-ttl">{{ __('Fă testul de 60 secunde', 'sage') }}</span>
        <span class="fc-micro">{{ __('8 întrebări, anonim, fără email', 'sage') }}</span>
      </span>
      <span class="cta-ghost">{{ __('Începe', 'sage') }}
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="m9 6 6 6-6 6"/></svg>
      </span>
    </a>
  </div>

  {{-- Bară de jos --}}
  <div class="ms-bottom">
    <div class="left">
      <span class="dot">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="M12 8v4l3 2"/></svg>
      </span>
      <span class="txt">
        <strong>{{ __('Obiectivul tău nu apare pe listă? Sau ai mai multe deodată?', 'sage') }}</strong>
        <span>{{ __('Vezi catalogul complet sau întreabă-ne pe WhatsApp, L–S 9–19.', 'sage') }}</span>
      </span>
    </div>
    <a class="ms-see-all" href="{{ esc_url(home_url('/dupa-obiectiv/')) }}">{{ __('Vezi toate obiectivele', 'sage') }}
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="m9 6 6 6-6 6"/></svg>
    </a>
  </div>
</div>
