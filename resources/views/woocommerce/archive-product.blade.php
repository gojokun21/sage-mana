{{--
  Template: Product archive (shop) — redesign după mockup
  `preferinte/Pagina Suplimente - catalog.html`.
  Categoriile au template propriu: taxonomy-product_cat.blade.php
  @see https://docs.woocommerce.com/document/template-structure/
--}}

@extends('layouts.app')

@section('content')
  <div class="catalog-page">
    @include('partials.shop.breadcrumb')
    @include('partials.shop.hero')
    @include('partials.shop.toolbar')

    <section class="catalog">
      <div class="catalog-inner">
        @include('partials.shop.sidebar')

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
