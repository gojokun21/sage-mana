{{--
  Template Name: Pagina simptom
  Pagina de detaliu pe simptom — redesign după mockup
  `preferinte/Pagina simptom - Sindrom metabolic.html`.

  Toate paginile de simptom au ACELEAȘI secțiuni (hero, definiție, semne,
  autotest, când la medic, produse, mituri, FAQ + quiz). Conținutul curent e
  static („Sindrom metabolic"), curatat manual în partials/simptom/*.blade.php.
  Selectoarele CSS sunt scoped sub `.simptom-detail` (resources/css/simptom.css,
  livrat prin simptom-bundle.css). Autotestul + acordeonul FAQ sunt în
  resources/js/simptom.js (lazy-loaded) și resources/js/faq-accordion.js.
--}}

@extends('layouts.app')

@section('content')
  <div class="simptom-detail" id="simptom-detail">
    <nav class="breadcrumb" aria-label="{{ esc_attr__('Breadcrumb', 'sage') }}">
      <a href="{{ esc_url(home_url('/')) }}">{{ __('Acasă', 'sage') }}</a>
      <span class="sep" aria-hidden="true">›</span>
      <a href="{{ esc_url(home_url('/dupa-simptom/')) }}">{{ __('După simptom', 'sage') }}</a>
      <span class="sep" aria-hidden="true">›</span>
      <span class="here">{{ get_the_title() }}</span>
    </nav>

    @include('partials.simptom.hero')
    @include('partials.simptom.definitie')
    @include('partials.simptom.semne')
    @include('partials.simptom.autotest')
    @include('partials.simptom.medic')
    @include('partials.simptom.produse')
    @include('partials.simptom.mituri')
    @include('partials.simptom.faq')
  </div>
@endsection
