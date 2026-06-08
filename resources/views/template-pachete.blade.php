{{--
  Template Name: Pachete
  Hub-ul pachetelor — secțiuni curate (hero, grid, education, quiz strip, FAQ),
  fără toolbar/sort/pagination. Lista celor 11 pachete e curatată manual în
  partials/pachete/grid.blade.php; numele, prețul, lista produselor și permalink-ul
  vin din WooCommerce (produse de tip `bundle`).
--}}

@extends('layouts.app')

@section('content')
  <div class="pachete-page">
    <nav class="breadcrumb" aria-label="{{ esc_attr__('Breadcrumb', 'sage') }}">
      <div class="breadcrumb-inner">
        <a href="{{ esc_url(home_url('/')) }}">{{ __('Acasă', 'sage') }}</a>
        <span class="sep" aria-hidden="true">›</span>
        <span class="here">{{ __('Pachete', 'sage') }}</span>
      </div>
    </nav>

    @include('partials.pachete.hero')
    @include('partials.pachete.grid')
    @include('partials.pachete.education')
    @include('partials.pachete.quiz-strip')
    @include('partials.pachete.faq')
  </div>
@endsection
