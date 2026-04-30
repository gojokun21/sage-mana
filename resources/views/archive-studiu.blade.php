{{--
  Archive — CPT `studiu`. Toate studiile.
  Markup-ul efectiv e in partials/studii/list.blade.php (refolosit si de
  taxonomy-categorie_studiu.blade.php).
--}}

@extends('layouts.app')

@section('content')
  @include('partials.studii.list', [
    'hero_eyebrow'  => __('Bază de date', 'sage'),
    'hero_title_em' => __('clinice', 'sage'),
    'hero_lede'     => __('Toate studiile sunt peer-reviewed și verificabile public. Pentru fiecare studiu găsești linkul direct către PubMed, PMC, NIH sau Cochrane Library.', 'sage'),
    'active_slug'   => 'all',
  ])
@endsection
