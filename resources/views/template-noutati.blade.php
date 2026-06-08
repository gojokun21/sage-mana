{{--
  Template Name: Noutăți · În curând
  Pagina filtru „Noutăți · În curând”.

  Tot conținutul e editabil din ACF (group_noutati_filtru, app/acf-noutati.php).
  Cele 3 tincturi sunt produse VIITOARE (în curs de aprobare ANSVSA) — date
  editoriale într-un repeater. Opțional, fiecare poate fi legată de un produs WC
  real (pentru imagine/link) când apare un draft. Formularul „Anunță-mă” e vizual.

  Scope CSS: `.noutati-page` (resources/css/noutati.css via noutati-bundle.css).
--}}

@extends('layouts.app')

@section('content')
  @php
    // Rezolvă tincturile din ACF; atașează imaginea produsului dacă e legat.
    $rows = \App\noutati_field('tinctures', []);
    $tinctures = [];
    foreach ($rows as $row) {
        $img_html = '';
        $link = '';
        $pid = (int) ($row['produs'] ?? 0);
        if ($pid && function_exists('wc_get_product')) {
            $product = wc_get_product($pid);
            if ($product && $product->is_visible()) {
                $link = get_permalink($pid);
                $img_id = $product->get_image_id();
                if ($img_id) {
                    $img_html = wp_get_attachment_image($img_id, 'woocommerce_thumbnail', false, [
                        'class' => 'tcard-photo', 'alt' => esc_attr($product->get_name()),
                        'loading' => 'lazy', 'decoding' => 'async',
                    ]);
                }
            }
        }
        $row['_img_html'] = $img_html;
        $row['_link'] = $link;
        $tinctures[] = $row;
    }
    $nt_count = count($tinctures);
  @endphp

  <div class="noutati-page">
    <nav class="breadcrumb" aria-label="{{ esc_attr__('Breadcrumb', 'sage') }}">
      <div class="breadcrumb-inner">
        <a href="{{ esc_url(home_url('/')) }}">{{ __('Acasă', 'sage') }}</a>
        <span class="sep" aria-hidden="true">›</span>
        <a href="{{ esc_url(get_post_type_archive_link('product') ?: home_url('/shop/')) }}">{{ __('Suplimente', 'sage') }}</a>
        <span class="sep" aria-hidden="true">›</span>
        <span class="here">{{ __('Noutăți · În curând', 'sage') }}</span>
      </div>
    </nav>

    @include('partials.noutati.hero', ['nt_count' => $nt_count])
    @include('partials.noutati.explain')
    @include('partials.noutati.tinctures', ['tinctures' => $tinctures, 'nt_count' => $nt_count])
    @include('partials.noutati.why')
    @include('partials.noutati.notify', ['tinctures' => $tinctures])
    @include('partials.noutati.faq')
    @include('partials.noutati.cta-final')
  </div>
@endsection
