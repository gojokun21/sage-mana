{{--
  Template Name: Suplimente sub 200 lei
  Pagina filtru „Suplimente sub 200 lei" — redesign după mockup
  `preferinte/Pagina filtru - Sub 200 lei.html`.

  DATE LIVE din WooCommerce:
  - Grila + tabelul cost/zi = produse `simple` vizibile cu preț < 200 lei,
    ordonate crescător după preț. Per produs: nume, imagine, link, preț, categorie
    (native WC) + ACF `informatie_generala` (forma, protocol_zile, beneficii).
    Cost/zi = preț ÷ protocol_zile (aceeași logică ca în coș — cart-items.blade.php).
  - Bridge = produsele `bundle` reale, ordonate după preț.
  - Hero (număr produse, interval cost/zi, contoare chip-uri) = derivate.

  Editorial static: textele explain, FAQ, CTA, titlurile de secțiune.
  „Vegan" nu e câmp WC structurat → afișat doar dacă produsul are tag/atribut vegan.

  Scope CSS: `.sub200-page` (resources/css/sub200.css via sub200-bundle.css).
--}}

@extends('layouts.app')

@section('content')
  @php
    $price_cap = 200.0; // „sub 200 lei"

    // Mapare slug categorie → clasă temă (gradientul cardului din mockup).
    $theme_map = [
        'focus' => 't-focus',
        'detoxifiere' => 't-detox', 'detox' => 't-detox', 'ficat' => 't-detox',
        'digestiv' => 't-dig', 'sanatate-intestinala' => 't-dig', 'digestie' => 't-dig',
        'imunitate' => 't-imun',
        'frumusete' => 't-art', 'oase-articulatii' => 't-art', 'articulatii' => 't-art', 'colagen' => 't-art',
        'energie' => 't-energ', 'vitalitate' => 't-energ', 'multivitamine' => 't-energ',
    ];
    $theme_cycle = ['t-focus', 't-detox', 't-dig', 't-imun', 't-art', 't-energ'];

    // „Vegan?" — tag de produs `vegan`, atribut pa_vegan, sau cuvântul în nume.
    $is_vegan = static function (\WC_Product $p): bool {
        if (has_term('vegan', 'product_tag', $p->get_id())) {
            return true;
        }
        $attr = strtolower((string) $p->get_attribute('pa_vegan'));
        if ($attr !== '' && ! in_array($attr, ['nu', 'no', 'false', '0'], true)) {
            return true;
        }
        return stripos($p->get_name(), 'vegan') !== false;
    };

    // Cost/zi formatat „X,YZ lei/zi" (zecimale cu virgulă, ca în restul temei).
    $fmt_cpd = static function (float $v): string {
        return number_format($v, 2, ',', '.') . ' ' . __('lei/zi', 'sage');
    };

    // --- Interogare produse simple, vizibile, < 200 lei, preț crescător ---
    $raw = function_exists('wc_get_products') ? wc_get_products([
        'status' => 'publish',
        'type' => ['simple'],
        'limit' => -1,
        'orderby' => 'meta_value_num',
        'meta_key' => '_price',
        'order' => 'ASC',
    ]) : [];

    $products = [];
    foreach ($raw as $p) {
        if (! $p->is_visible()) {
            continue;
        }
        $price = (float) $p->get_price();
        if ($price <= 0 || $price >= $price_cap) {
            continue;
        }

        $pid = $p->get_id();
        $info = function_exists('get_field') ? get_field('informatie_generala', $pid) : [];
        $info = is_array($info) ? $info : [];

        $forma = ! empty($info['forma']) ? trim((string) $info['forma']) : '';
        $days = ! empty($info['protocol_zile']) ? (int) $info['protocol_zile'] : 0;
        $beneficii = (! empty($info['beneficii']) && is_array($info['beneficii']))
            ? array_slice($info['beneficii'], 0, 3)
            : [];
        $benefits = array_values(array_filter(array_map(
            static fn ($b) => trim((string) ($b['denumire_beneficiu'] ?? '')),
            $beneficii
        )));

        // Linie „sub": formă · N zile (din date reale); fallback pe short description.
        $sub_parts = array_filter([
            $forma,
            $days > 0 ? sprintf(_n('%d zi', '%d zile', $days, 'sage'), $days) : '',
        ]);
        $sub = implode(' · ', $sub_parts);
        if ($sub === '') {
            $sub = wp_trim_words(wp_strip_all_tags($p->get_short_description()), 10, '…');
        }

        // Categorie primară (nume + slug pentru temă).
        $terms = get_the_terms($pid, 'product_cat');
        $primary = (! is_wp_error($terms) && ! empty($terms)) ? $terms[0] : null;
        $cat_name = $primary ? $primary->name : '';
        $cat_slug = $primary ? $primary->slug : '';
        $theme = $theme_map[$cat_slug] ?? $theme_cycle[count($products) % count($theme_cycle)];

        $img_id = $p->get_image_id();
        $img_html = $img_id
            ? wp_get_attachment_image($img_id, 'woocommerce_thumbnail', false, [
                'class' => 'pcard-photo',
                'alt' => esc_attr($p->get_name()),
                'loading' => 'lazy',
                'decoding' => 'async',
            ])
            : '<img class="pcard-photo" src="' . esc_url(wc_placeholder_img_src()) . '" alt="' . esc_attr($p->get_name()) . '" loading="lazy" decoding="async">';

        $cpd = $days > 0 ? $price / $days : 0.0;

        $products[] = [
            'id' => $pid,
            'name' => $p->get_name(),
            'link' => get_permalink($pid),
            'img_html' => $img_html,
            'cat_name' => $cat_name,
            'theme' => $theme,
            'sub' => $sub,
            'benefits' => $benefits,
            'days' => $days,
            'duration_label' => $days > 0 ? sprintf(_n('%d zi', '%d zile', $days, 'sage'), $days) : '',
            'price_html' => $p->is_on_sale() && $p->get_sale_price() !== ''
                ? wc_price($p->get_sale_price()) . ' <del>' . wc_price($p->get_regular_price()) . '</del>'
                : wc_price($price),
            'price_raw' => $price,
            'cpd' => $cpd,
            'cpd_label' => $cpd > 0 ? $fmt_cpd($cpd) : '—',
            'vegan' => $is_vegan($p),
        ];
    }

    $product_count = count($products);

    // --- Statistici hero (derivate) ---
    $with_cpd = array_filter($products, static fn ($x) => $x['cpd'] > 0);
    $cheapest = null;
    $dearest = null;
    foreach ($with_cpd as $x) {
        if ($cheapest === null || $x['cpd'] < $cheapest['cpd']) { $cheapest = $x; }
        if ($dearest === null || $x['cpd'] > $dearest['cpd']) { $dearest = $x; }
    }
    $vegan_count = count(array_filter($products, static fn ($x) => $x['vegan']));
    $long_count = count(array_filter($products, static fn ($x) => $x['days'] >= 120));
    $short_count = count(array_filter($products, static fn ($x) => $x['days'] > 0 && $x['days'] < 120));

    // --- Tabel: același set, sortat crescător după cost/zi; „best" = cură lungă (120+ zile) ---
    $table = $products;
    usort($table, static fn ($a, $b) => ($a['cpd'] <=> $b['cpd']));

    // --- Bridge: pachete (bundle) reale, preț crescător, max 3 ---
    $bundles_raw = function_exists('wc_get_products') ? wc_get_products([
        'status' => 'publish',
        'type' => ['bundle'],
        'limit' => 3,
        'orderby' => 'meta_value_num',
        'meta_key' => '_price',
        'order' => 'ASC',
    ]) : [];
    $bundles = [];
    foreach ($bundles_raw as $b) {
        if (! $b->is_visible()) {
            continue;
        }
        $bid = $b->get_id();
        $b_img = $b->get_image_id()
            ? wp_get_attachment_image($b->get_image_id(), 'thumbnail', false, ['alt' => esc_attr($b->get_name()), 'loading' => 'lazy'])
            : '';
        $bundles[] = [
            'name' => $b->get_name(),
            'link' => get_permalink($bid),
            'img_html' => $b_img,
            'desc' => wp_trim_words(wp_strip_all_tags($b->get_short_description()), 9, '…'),
            'price_html' => wc_price($b->get_price()),
        ];
    }

    $shop_url = function_exists('wc_get_page_permalink') ? wc_get_page_permalink('shop') : home_url('/');
    $pachete_url = home_url('/pachete/');
  @endphp

  <div class="sub200-page">
    <nav class="breadcrumb" aria-label="{{ esc_attr__('Breadcrumb', 'sage') }}">
      <div class="breadcrumb-inner">
        <a href="{{ esc_url(home_url('/')) }}">{{ __('Acasă', 'sage') }}</a>
        <span class="sep" aria-hidden="true">›</span>
        <a href="{{ esc_url($shop_url) }}">{{ __('Suplimente', 'sage') }}</a>
        <span class="sep" aria-hidden="true">›</span>
        <span class="here">{{ __('Sub 200 lei', 'sage') }}</span>
      </div>
    </nav>

    @include('partials.sub-200.hero', [
      'product_count' => $product_count,
      'cheapest' => $cheapest,
      'dearest' => $dearest,
      'vegan_count' => $vegan_count,
      'long_count' => $long_count,
      'short_count' => $short_count,
    ])

    @include('partials.sub-200.explain', ['pachete_url' => $pachete_url])

    @include('partials.sub-200.products', [
      'products' => $products,
      'product_count' => $product_count,
    ])

    @if ($product_count > 0)
      @include('partials.sub-200.table', ['table' => $table])
    @endif

    @include('partials.sub-200.bridge', ['bundles' => $bundles, 'pachete_url' => $pachete_url])

    @include('partials.sub-200.faq')
    @include('partials.sub-200.cta-final', ['product_count' => $product_count])
  </div>
@endsection
