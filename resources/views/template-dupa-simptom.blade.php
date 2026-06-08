{{--
  Template Name: Dupa simptom
  Hub „După simptom" — redesign după mockup `preferinte/Pagina hub - Dupa simptom.html`.
  Selectoarele CSS sunt scoped sub `.symptom-page` (vezi resources/css/symptom.css,
  livrat prin symptom-bundle.css). Conținut static, în română. Filtrarea live a
  cardurilor după textul din hero e în resources/js/symptom.js.
--}}

@extends('layouts.app')

@section('content')
  <div class="symptom-page" id="symptom-hub">
    <nav class="breadcrumb" aria-label="{{ esc_attr__('Breadcrumb', 'sage') }}">
      <div class="breadcrumb-inner">
        <a href="{{ esc_url(home_url('/')) }}">{{ __('Acasă', 'sage') }}</a>
        <span class="sep" aria-hidden="true">›</span>
        <span class="here">{{ __('După simptom', 'sage') }}</span>
      </div>
    </nav>

    @include('partials.symptom.hero')
    @include('partials.symptom.groups')
    @include('partials.symptom.medic')
    @include('partials.symptom.quiz')
    @include('partials.symptom.blog')
  </div>
@endsection
