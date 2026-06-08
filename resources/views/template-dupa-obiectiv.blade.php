{{--
  Template Name: Dupa obiectiv
  Hub „După obiectiv" — design IDENTIC cu hubul „După simptom"
  (`template-dupa-simptom.blade.php`). Reutilizează scope-ul CSS `.symptom-page`
  (resources/css/symptom.css via symptom-bundle.css) și filtrarea live din
  resources/js/symptom.js (auto-load pe `.symptom-page`, hook pe #symptomSearch).
  Conținut: cele 10 obiective grupate (vezi partials/mega-obiectiv pentru sursă),
  cu link la paginile reale de obiectiv sub /dupa-obiectiv/{slug}/.
--}}

@extends('layouts.app')

@section('content')
  <div class="symptom-page" id="obiectiv-hub">
    <nav class="breadcrumb" aria-label="{{ esc_attr__('Breadcrumb', 'sage') }}">
      <div class="breadcrumb-inner">
        <a href="{{ esc_url(home_url('/')) }}">{{ __('Acasă', 'sage') }}</a>
        <span class="sep" aria-hidden="true">›</span>
        <span class="here">{{ __('După obiectiv', 'sage') }}</span>
      </div>
    </nav>

    @include('partials.obiectiv-hub.hero')
    @include('partials.obiectiv-hub.groups')
    @include('partials.obiectiv-hub.medic')
    @include('partials.obiectiv-hub.quiz')
    @include('partials.obiectiv-hub.blog')
  </div>
@endsection
