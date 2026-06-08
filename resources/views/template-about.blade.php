{{--
  Template Name: About Template
  Redesign după mockup `preferinte/Pagina Despre - Povestea brandului.html`.
  Toate selectoarele CSS sunt scoped sub `.about-page` (vezi resources/css/about.css,
  livrat prin about-bundle.css). Conținut static, fără JS (fără slidere).
--}}

@extends('layouts.app')

@section('content')
  <div class="about-page" id="about-template">
    <nav class="breadcrumb" aria-label="{{ esc_attr__('Breadcrumb', 'sage') }}">
      <div class="breadcrumb-inner">
        <a href="{{ esc_url(home_url('/')) }}">{{ __('Acasă', 'sage') }}</a>
        <span class="sep" aria-hidden="true">›</span>
        <span class="here">{{ __('Despre noi', 'sage') }}</span>
      </div>
    </nav>

    @include('partials.about.story-hero')
    @include('partials.about.story')
    @include('partials.about.values')
    @include('partials.about.not-doing')
    @include('partials.about.standards')
    @include('partials.about.selection')
    @include('partials.about.testimonials')
    @include('partials.about.team')
    @include('partials.about.cta-final')
  </div>
@endsection
