{{--
  Template Name: Home Template
--}}

@extends('layouts.app')

@section('content')
  <div class="home-template">
    @include('partials.home.hero')
    @include('partials.home.philosophy')
    @include('partials.home.entry-points')
    @include('partials.home.quiz-strip')
    @include('partials.home.flagship-products')
    @include('partials.home.trust')
    @include('partials.home.blog')
    @include('partials.home.testimonials')
    @include('partials.home.newsletter')
  </div>
@endsection
