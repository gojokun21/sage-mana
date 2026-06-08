{{--
  Template: Product category (taxonomy product_cat).
  Redesign: aceeași experiență ca pagina „Suplimente" (shop) — layout `.catalog-page`
  cu hero + toolbar + sidebar filtre + grid, reutilizând partials-urile de shop.
  Hero-ul e adaptat la categoria curentă (titlu, descriere, număr produse).

  Filtrarea (sidebar + sortare + paginare) e deja category-aware:
   - main query: pre_get_posts pe is_product_taxonomy() (app/shop-filters.php),
   - AJAX: scope pe `taxonomy=product_cat` + `term` (shop.js + config).
  Filtrul „Categorie" e ascuns aici (ești deja într-o categorie); form-ul postează
  pe URL-ul categoriei, deci filtrele rămân în context.
--}}

@extends('layouts.app')

@section('content')
  @php
    $term = get_queried_object();
    $cat_name = single_term_title('', false);
    $cat_count = (int) ($term->count ?? 0);
    $cat_url = function_exists('get_term_link') ? get_term_link($term) : '';
    if (is_wp_error($cat_url)) {
        $cat_url = '';
    }
    // Fără lede în hero pe paginile de categorie (cerut explicit).
    $cat_lede = '';
  @endphp

  <div class="catalog-page">
    @include('partials.shop.breadcrumb')

    @include('partials.shop.hero', [
      'hero_eyebrow' => __('Categorie', 'sage'),
      'hero_count' => $cat_count,
      'hero_h1_count_unit' => __('produse', 'sage'),
      'hero_h1_tail' => __('în categoria', 'sage'),
      'hero_h1_em' => $cat_name . '.',
      'hero_h1_fallback' => $cat_name,
      'hero_lede' => $cat_lede,
      'hero_stat_count_template' => __('%d produse', 'sage'),
    ])

    @include('partials.shop.toolbar')

    <section class="catalog">
      <div class="catalog-inner">
        @include('partials.shop.sidebar', [
          'hide_category_filter' => true,
          'filter_action' => $cat_url,
        ])

        <div class="catalog-results">
          @include('partials.shop-loop')
        </div>
      </div>
    </section>

    @include('partials.shop.cross-sell')
    @include('partials.shop.education')
    @include('partials.shop.faq')
  </div>
@endsection
