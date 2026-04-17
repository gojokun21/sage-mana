{{--
  Template Name: Home Template
--}}

@extends('layouts.app')

@section('content')
  <div class="home-template">
    @include('partials.home.hero')
    @include('partials.home.categories')
    @include('partials.home.benefits')
    @include('partials.home.products-slider', [
      'id' => 'popular-packages',
      'title' => __('Pachete populare', 'sage'),
      'products' => $popular_packages,
    ])
    @include('partials.home.testimonials')
    @include('partials.home.products-slider', [
      'id' => 'new-products',
      'title' => __('Produse Noi', 'sage'),
      'products' => $new_products,
    ])
    @include('partials.home.banner')
    @include('partials.home.products-slider', [
      'id' => 'promo-products',
      'title' => __('Cele mai vândute produse', 'sage'),
      'products' => $promo_products,
      'badge' => 'promo',
      'cta_url' => function_exists('wc_get_page_id') ? get_permalink(wc_get_page_id('shop')) : home_url('/magazin/'),
      'cta_text' => __('Vezi produsele VivensGenetica', 'sage'),
    ])
    @include('partials.home.reviews')
  </div>
@endsection
