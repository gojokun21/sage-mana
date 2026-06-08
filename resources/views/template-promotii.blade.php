{{--
  Template Name: Promoții
  Redesign după mockup `preferinte/Pagina Promotii.html`.

  Date REALE din WooCommerce: produsele aflate efectiv la reducere
  (`wc_get_product_ids_on_sale()`). Pentru fiecare calculăm % reducerea din
  preț regular vs preț redus. „Pick-ul lunii" = oferta cu cea mai mare reducere.
  Filtrele (categorie / reducere / sortare) sunt client-side (resources/js/promotii.js)
  peste cardurile randate. Dacă nu există produse la reducere → empty state.

  Scope CSS: `.promo-page` (resources/css/promotii.css via promotii-bundle.css).
--}}

@extends('layouts.app')

@section('content')
  @php
    $offers = [];
    $sale_ids = function_exists('wc_get_product_ids_on_sale') ? wc_get_product_ids_on_sale() : [];

    foreach ($sale_ids as $sid) {
        $p = wc_get_product($sid);
        if (! $p || ! $p->is_visible() || $p->is_type('variation')) {
            continue;
        }
        $reg = (float) $p->get_regular_price();
        $sale = (float) $p->get_sale_price();
        if ($reg <= 0 || $sale <= 0 || $sale >= $reg) {
            continue;
        }

        $cats = get_the_terms($p->get_id(), 'product_cat');
        $primary = (! is_wp_error($cats) && ! empty($cats)) ? $cats[0] : null;
        $img_id = $p->get_image_id();

        $offers[] = [
            'product' => $p,
            'id' => $p->get_id(),
            'name' => $p->get_name(),
            'link' => get_permalink($p->get_id()),
            'img' => $img_id ? wp_get_attachment_image_url($img_id, 'medium') : wc_placeholder_img_src(),
            'reg' => $reg,
            'sale' => $sale,
            'disc' => (int) round((($reg - $sale) / $reg) * 100),
            'save' => $reg - $sale,
            'cat_name' => $primary ? $primary->name : '',
            'cat_slug' => $primary ? $primary->slug : '',
            'short' => trim(wp_strip_all_tags($p->get_short_description())),
            'type' => $p->get_type(),
            'can_ajax' => $p->is_purchasable() && $p->is_in_stock() && $p->is_type('simple'),
            'add_url' => $p->add_to_cart_url(),
            'sku' => $p->get_sku(),
        ];
    }

    // Sortare implicită „Recomandate" = cea mai mare reducere prima.
    usort($offers, static fn ($a, $b) => $b['disc'] <=> $a['disc']);

    $offer_count = count($offers);
    $pick = $offers[0] ?? null;
    // Restul ofertelor (fără pick) în grid, ca să nu dublăm produsul evidențiat.
    $grid = $offer_count > 1 ? array_slice($offers, 1) : $offers;

    // Interval reduceri pentru hero.
    $disc_values = array_map(static fn ($o) => $o['disc'], $offers);
    $disc_min = $disc_values ? min($disc_values) : 0;
    $disc_max = $disc_values ? max($disc_values) : 0;

    // Categorii prezente în oferte (pentru chip-urile de filtrare).
    $filter_cats = [];
    foreach ($offers as $o) {
        if ($o['cat_slug'] && ! isset($filter_cats[$o['cat_slug']])) {
            $filter_cats[$o['cat_slug']] = $o['cat_name'];
        }
    }
  @endphp

  <div class="promo-page">
    <nav class="breadcrumb" aria-label="{{ esc_attr__('Breadcrumb', 'sage') }}">
      <div class="breadcrumb-inner">
        <a href="{{ esc_url(home_url('/')) }}">{{ __('Acasă', 'sage') }}</a>
        <span class="sep" aria-hidden="true">›</span>
        <span class="here">{{ __('Promoții', 'sage') }}</span>
      </div>
    </nav>

    @include('partials.promotii.hero', [
      'offer_count' => $offer_count,
      'disc_min' => $disc_min,
      'disc_max' => $disc_max,
    ])

    @if ($offer_count > 0)
      @include('partials.promotii.filter-bar', ['filter_cats' => $filter_cats])
      @include('partials.promotii.pick', ['pick' => $pick])
      @include('partials.promotii.offers', ['grid' => $grid, 'offer_count' => $offer_count])
    @else
      <section class="offers">
        <div class="offers-inner">
          <div class="offers-empty">
            {{ __('Momentan nu avem produse în promoție. Revino curând — ofertele se actualizează lunar.', 'sage') }}
          </div>
        </div>
      </section>
    @endif

    @include('partials.promotii.bundle-band')
    @include('partials.promotii.education')
    @include('partials.promotii.faq')
  </div>
@endsection
