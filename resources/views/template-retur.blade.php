{{--
  Template Name: Pagina retur
  Pagina „Retur și rambursare" (RMA) — redesign după mockup `Pagina Retur RMA`.
  Scope CSS `.retur-page` (resources/css/retur.css via retur-bundle.css).
  Formularul + logica de submit/lookup vin din plugin-ul mn-contact-form
  ([natura_rma_form] + AJAX). Acordeonul FAQ folosește <details> nativ.
--}}

@extends('layouts.app')

@section('content')
  <div class="retur-page" id="retur-page">
    <nav class="breadcrumb" aria-label="{{ esc_attr__('Breadcrumb', 'sage') }}">
      <a href="{{ esc_url(home_url('/')) }}">{{ __('Acasă', 'sage') }}</a>
      <span class="sep" aria-hidden="true">›</span>
      <a href="{{ esc_url(home_url('/contact/')) }}">{{ __('Suport', 'sage') }}</a>
      <span class="sep" aria-hidden="true">›</span>
      <span class="here">{{ __('Retur și rambursare', 'sage') }}</span>
    </nav>

    @include('partials.retur.hero')
    @include('partials.retur.alert')
    @include('partials.retur.cases')
    @include('partials.retur.steps')
    @include('partials.retur.form')
    @include('partials.retur.faq')
    @include('partials.retur.special')
  </div>
@endsection
