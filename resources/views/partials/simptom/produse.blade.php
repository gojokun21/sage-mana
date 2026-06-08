{{--
  Trei produse, în ordine logică (Opțiune 01 → 03).

  Sursă: repeater-ul ACF `produse_items` (Post Object → produs WC + copy
  editorial). Dacă ACF e gol, cade pe array-ul static („Sindrom metabolic").
  În ambele cazuri prețul/rating-ul/imaginea vin LIVE din WooCommerce; dacă
  produsul nu se rezolvă, cardul folosește fallback-ul static fără să se rupă.
--}}
@php
  $eyebrow = \App\simptom_field('produse_eyebrow', __('Dacă ai încercat schimbări de obicei și nu ajung', 'sage'));
  $titlu = \App\simptom_field('produse_titlu', __('Trei opțiuni, <em>în ordine logică.</em>', 'sage'));
  $intro = \App\simptom_field('produse_intro', __('Începe cu prima. Dacă după 6 săptămâni nu vezi schimbare, treci la următoarea.', 'sage'));

  $acf_items = \App\simptom_field('produse_items', null);

  // Fallback static (păstrează slug + date de rezervă pentru carduri).
  $fallback_produse = [
    [
      'product_id' => 0, 'slug' => 'black-seed-elixir',
      'opt' => __('Opțiune 01', 'sage'), 'category' => __('Metabolic · ficat', 'sage'),
      'why' => __('Ulei chimen negru egiptean și vitamina E, 240 capsule vegane, 120 doze. Pentru echilibru metabolic, suport ficat, glicemie în echilibru natural.', 'sage'),
      'cta' => __('Vezi produsul', 'sage'), 'cta_class' => 'btn-terra', 'pack_class' => '',
      'fallback' => ['name' => __('Black Seed Elixir', 'sage'), 'price' => '184 lei', 'rating' => '4,8', 'count' => 187, 'lines' => ['Black Seed<br>Elixir', '240 CPS · 120 DOZE']],
    ],
    [
      'product_id' => 0, 'slug' => 'pachet-confort-digestiv',
      'opt' => __('Opțiune 02', 'sage'), 'category' => __('Digestiv · probiotic', 'sage'),
      'why' => __('Probiotice și Detox Ficat, 2 suplimente vegan, 120 zile. Pentru microbiom dereglat, balonare după mese, digestie greoaie a grăsimilor.', 'sage'),
      'cta' => __('Vezi pachetul', 'sage'), 'cta_class' => 'btn-secondary-g', 'pack_class' => 'b2',
      'fallback' => ['name' => __('Pachet Confort Digestiv', 'sage'), 'price' => '283 lei', 'rating' => '4,7', 'count' => 204, 'lines' => ['Pachet<br>Confort Digestiv', '2 SUPL · 120 ZILE']],
    ],
    [
      'product_id' => 0, 'slug' => 'pachet-detox-plus',
      'opt' => __('Opțiune 03', 'sage'), 'category' => __('Complet · 3 suplimente', 'sage'),
      'why' => __('Curățare profundă ficat și sistem digestiv, 3 suplimente vegan, 120 zile. Pentru ficat încărcat, microbiom dereglat și digestie greoaie a grăsimilor, abordare completă.', 'sage'),
      'cta' => __('Vezi pachetul', 'sage'), 'cta_class' => 'btn-secondary-g', 'pack_class' => 'b3',
      'fallback' => ['name' => __('Pachet Detox Plus', 'sage'), 'price' => '457 lei', 'rating' => '4,8', 'count' => 89, 'lines' => ['Pachet<br>Detox Plus', '3 SUPL · 120 ZILE']],
    ],
  ];

  if (is_array($acf_items) && ! empty($acf_items)) {
      $produse = array_map(static function ($row) {
          $nume = $row['nume'] ?? '';
          $pret = $row['pret'] ?? '';

          return [
              'product_id' => (int) ($row['produs'] ?? 0),
              'slug' => '',
              'opt' => $row['opt'] ?? '',
              'category' => $row['category'] ?? '',
              'why' => $row['why'] ?? '',
              'cta' => $row['cta'] ?: __('Vezi produsul', 'sage'),
              'cta_class' => $row['cta_class'] ?: 'btn-terra',
              'pack_class' => '',
              // Fallback de afișare din ACF: nume/preț; liniile mini-pack derivă din nume.
              'fallback' => ($nume || $pret) ? [
                  'name' => $nume,
                  'price' => $pret,
                  'rating' => null,
                  'count' => null,
                  'lines' => [esc_html($nume), ''],
              ] : null,
          ];
      }, $acf_items);
  } else {
      $produse = $fallback_produse;
  }

  $fmt_lei = static fn ($v) => number_format_i18n((float) $v, 0) . ' lei';
@endphp

<section class="produse">
  <div class="prod-head">
    <div class="eyebrow">{{ $eyebrow }}</div>
    <h2>{!! wp_kses($titlu, ['em' => []]) !!}</h2>
    <p>{{ $intro }}</p>
  </div>

  <div class="prod-grid">
    @foreach ($produse as $item)
      @php
        $product = null;
        if (! empty($item['product_id'])) {
            $product = wc_get_product($item['product_id']);
        } elseif (! empty($item['slug'])) {
            $wc_post = get_page_by_path($item['slug'], OBJECT, 'product');
            $product = $wc_post ? wc_get_product($wc_post->ID) : null;
        }

        $fb = $item['fallback'] ?? null;

        $name  = $product ? $product->get_name() : ($fb['name'] ?? '');
        $url   = $product ? get_permalink($product->get_id()) : home_url('/');
        $img   = $product ? $product->get_image_id() : 0;

        $price = $fb['price'] ?? '';
        $price_old = null;
        if ($product) {
            $price = $fmt_lei($product->get_price());
            if ($product->is_on_sale() && $product->get_regular_price()) {
                $price_old = $fmt_lei($product->get_regular_price());
            }
        }

        $rating = $fb['rating'] ?? null;
        $count  = $fb['count'] ?? null;
        if ($product && $product->get_review_count() > 0) {
            $rating = number_format_i18n((float) $product->get_average_rating(), 1);
            $count  = (int) $product->get_review_count();
        }
      @endphp

      <div class="prod-card">
        <span class="opt-chip">{{ $item['opt'] }}</span>
        <a class="prod-photo" href="{{ esc_url($url) }}" aria-label="{{ esc_attr($name) }}">
          <div class="wash"></div>
          @if ($img)
            {!! wp_get_attachment_image($img, 'woocommerce_single', false, [
              'alt' => $name,
              'loading' => 'lazy',
              'decoding' => 'async',
              'sizes' => '(max-width: 600px) 90vw, 360px',
            ]) !!}
          @elseif ($fb)
            <div class="mini-pack {{ $item['pack_class'] }}" aria-hidden="true">
              <div class="lbl">
                <div class="b">{{ __('Mâna Naturii', 'sage') }}</div>
                <div class="n">{!! $fb['lines'][0] !!}</div>
                <div class="v">{{ $fb['lines'][1] }}</div>
              </div>
            </div>
          @endif
        </a>
        <div class="prod-body">
          <span class="eyebrow-mini">{{ $item['category'] }}</span>
          <h3>{{ $name }}</h3>
          <p class="why">{{ $item['why'] }}</p>
          @if ($rating)
            <div class="rating-mini">
              <div class="stars" aria-hidden="true">
                @for ($s = 0; $s < 5; $s++)
                  <svg viewBox="0 0 24 24" fill="currentColor"><path d="m12 2 3 7 7 1-5 5 1 7-6-3-6 3 1-7-5-5 7-1z"/></svg>
                @endfor
              </div>
              <strong>{{ $rating }} / 5</strong>
              <span>({{ $count }})</span>
            </div>
          @endif
          <div class="price-row">
            <span class="price">{{ $price }}</span>
            @if ($price_old)
              <span class="price-old">{{ $price_old }}</span>
            @endif
          </div>
          <a class="{{ $item['cta_class'] }}" href="{{ esc_url($url) }}" style="margin-top:14px">{{ $item['cta'] }}
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="m9 6 6 6-6 6"/></svg>
          </a>
        </div>
      </div>
    @endforeach
  </div>
</section>
