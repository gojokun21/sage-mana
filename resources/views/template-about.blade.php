{{--
  Template Name: About Template
--}}

@extends('layouts.app')

@section('content')
  <main class="about-template" id="about-template">
    @include('partials.about.hero')
    @include('partials.about.testimonials')
    @include('partials.about.statistics')
    @include('partials.about.why')
    @include('partials.about.reviews')
  </main>
@endsection
