{{--
  Template Name: Cele mai vândute
  Pagina filtru „Cele mai vândute”.

  Tot conținutul editorial e EDITABIL din ACF (group_bestseller_filtru,
  app/acf-bestseller.php). Cele din top sunt PRODUSE REALE alese în repeater-ul
  ACF `bestsellers` (post_object) — numele, imaginea, prețul, beneficiile (ACF
  informatie_generala) și durata/cost-zi vin LIVE din WooCommerce; doar textul
  „de ce e best-seller” + rating-ul de tabel sunt editoriale.

  Seed: link/Unelte/CLI (vezi app/bestseller-seed.php, SEED-LINKS.md).
  Scope CSS: `.bestseller-page` (resources/css/cele-mai-vandute.css).
--}}

@extends('layouts.app')

@section('content')
  @php
    // „Vegan?” — tag de produs `vegan`, atribut pa_vegan, sau cuvântul în nume.
    $bs_is_vegan = static function (\WC_Product $p): bool {
        if (has_term('vegan', 'product_tag', $p->get_id())) {
            return true;
        }
        $attr = strtolower((string) $p->get_attribute('pa_vegan'));
        if ($attr !== '' && ! in_array($attr, ['nu', 'no', 'false', '0'], true)) {
            return true;
        }
        return stripos($p->get_name(), 'vegan') !== false;
    };
    $bs_fmt_cpd = static fn (float $v): string => number_format($v, 2, ',', '.').' '.__('lei/zi', 'sage');

    // Construiește lista din repeater-ul ACF, rezolvând fiecare produs din WooCommerce.
    $rows = \App\bestseller_field('bestsellers', []);
    $bestsellers = [];
    foreach ($rows as $i => $row) {
        $pid = (int) ($row['produs'] ?? 0);
        $product = ($pid && function_exists('wc_get_product')) ? wc_get_product($pid) : null;
        if (! $product || ! $product->is_visible()) {
            continue;
        }

        $info = function_exists('get_field') ? get_field('informatie_generala', $pid) : [];
        $info = is_array($info) ? $info : [];
        $forma = ! empty($info['forma']) ? trim((string) $info['forma']) : '';
        $days = ! empty($info['protocol_zile']) ? (int) $info['protocol_zile'] : 0;
        $beneficii = (! empty($info['beneficii']) && is_array($info['beneficii']))
            ? array_slice($info['beneficii'], 0, 3) : [];
        $benefits = array_values(array_filter(array_map(
            static fn ($b) => trim((string) ($b['denumire_beneficiu'] ?? '')),
            $beneficii
        )));

        // Categorie primară (override din ACF dacă e setat).
        $terms = get_the_terms($pid, 'product_cat');
        $primary = (! is_wp_error($terms) && ! empty($terms)) ? $terms[0] : null;
        $cat = ! empty($row['cat_label']) ? $row['cat_label'] : ($primary ? $primary->name : '');

        // Sub-linie (override din ACF, altfel formă · durată).
        $sub_parts = array_filter([$forma, $days > 0 ? sprintf(_n('%d zi', '%d zile', $days, 'sage'), $days) : '']);
        $sub = ! empty($row['sub_override']) ? $row['sub_override'] : implode(' · ', $sub_parts);

        $price = (float) $product->get_price();
        $cpd = $days > 0 ? $price / $days : 0.0;

        $img_id = $product->get_image_id();
        $img_html = $img_id
            ? wp_get_attachment_image($img_id, 'woocommerce_thumbnail', false, [
                'class' => 'pcard-photo', 'alt' => esc_attr($product->get_name()),
                'loading' => 'lazy', 'decoding' => 'async',
            ])
            : '<img class="pcard-photo" src="'.esc_url(wc_placeholder_img_src()).'" alt="'.esc_attr($product->get_name()).'" loading="lazy" decoding="async">';

        $bestsellers[] = [
            'rank' => '#'.($i + 1),
            'name' => $product->get_name(),
            'link' => get_permalink($pid),
            'img_html' => $img_html,
            'cat' => $cat,
            'vegan' => $bs_is_vegan($product),
            'sub' => $sub,
            'benefits' => $benefits,
            'why' => (string) ($row['why'] ?? ''),
            'cta_label' => ! empty($row['cta_label']) ? $row['cta_label'] : __('Vezi produsul', 'sage'),
            'price_html' => $product->is_on_sale() && $product->get_sale_price() !== ''
                ? wc_price($product->get_sale_price()).' <del>'.wc_price($product->get_regular_price()).'</del>'
                : wc_price($price),
            'cpd_label' => $cpd > 0 ? $bs_fmt_cpd($cpd) : '',
            'days_label' => $days > 0 ? sprintf(_n('%d zi', '%d zile', $days, 'sage'), $days) : '',
            'rating' => (int) ($row['rating'] ?? 5),
            'rating_label' => (string) ($row['rating_label'] ?? ''),
        ];
    }

    $bs_count = count($bestsellers);
  @endphp

  <div class="bestseller-page">
    <nav class="breadcrumb" aria-label="{{ esc_attr__('Breadcrumb', 'sage') }}">
      <div class="breadcrumb-inner">
        <a href="{{ esc_url(home_url('/')) }}">{{ __('Acasă', 'sage') }}</a>
        <span class="sep" aria-hidden="true">›</span>
        <a href="{{ esc_url(get_post_type_archive_link('product') ?: home_url('/shop/')) }}">{{ __('Suplimente', 'sage') }}</a>
        <span class="sep" aria-hidden="true">›</span>
        <span class="here">{{ __('Cele mai vândute', 'sage') }}</span>
      </div>
    </nav>

    @include('partials.cele-mai-vandute.hero', ['bs_count' => $bs_count])
    @include('partials.cele-mai-vandute.explain')
    @include('partials.cele-mai-vandute.products', ['bestsellers' => $bestsellers, 'bs_count' => $bs_count])
    @if ($bs_count > 0)
      @include('partials.cele-mai-vandute.table', ['bestsellers' => $bestsellers])
    @endif
    @include('partials.cele-mai-vandute.quiz')
    @include('partials.cele-mai-vandute.faq')
    @include('partials.cele-mai-vandute.cta-final')
  </div>
@endsection
