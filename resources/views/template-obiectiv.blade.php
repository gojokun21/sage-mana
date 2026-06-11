{{--
  Template Name: Pagina obiectiv
  Pagina de detaliu pe obiectiv — redesign după mockup `preferinte/Pagina obiectiv - *`.

  Toate paginile de obiectiv folosesc ACELEAȘI secțiuni (hero, reco, alts,
  bundle, how, reviews, edu, faq). Conținutul vine din ACF (vezi app/acf-obiectiv.php),
  cu fallback la „energie" în partiale. Paginile sunt copii sub /dupa-obiectiv/.
  Scope CSS `.obiectiv-detail` (resources/css/obiectiv.css via obiectiv-bundle.css).
  Acordeonul FAQ e în resources/js/faq-accordion.js (lazy-load pe `.faq .faq-item`).
--}}

@extends('layouts.app')

@section('content')
  <div class="obiectiv-detail" id="obiectiv-detail">
    <nav class="breadcrumb" aria-label="{{ esc_attr__('Breadcrumb', 'sage') }}">
      <a href="{{ esc_url(home_url('/')) }}">{{ __('Acasă', 'sage') }}</a>
      <span class="sep" aria-hidden="true">›</span>
      <a href="{{ esc_url(home_url('/dupa-obiectiv/')) }}">{{ __('După obiectiv', 'sage') }}</a>
      <span class="sep" aria-hidden="true">›</span>
      <span class="here">{{ html_entity_decode(get_the_title(), ENT_QUOTES, 'UTF-8') }}</span>
    </nav>

    @include('partials.obiectiv.hero')
    @include('partials.obiectiv.reco')
    @include('partials.obiectiv.alts')
    @include('partials.obiectiv.bundle')
    @include('partials.obiectiv.how')
    @include('partials.obiectiv.reviews')
    @include('partials.obiectiv.edu')
    @include('partials.obiectiv.faq')
  </div>
@endsection
