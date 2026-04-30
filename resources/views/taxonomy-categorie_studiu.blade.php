{{--
  Archive pentru taxonomia proprie a studiilor: `categorie_studiu`.
  Refoloseste partial-ul listei, cu eyebrow + lede contextuale termenului.
--}}

@extends('layouts.app')

@section('content')
  @php
    $current_term = get_queried_object();
    $term_desc = ($current_term && ! is_wp_error($current_term))
      ? trim(strip_tags(term_description($current_term->term_id, 'categorie_studiu')))
      : '';
  @endphp

  @include('partials.studii.list', [
    'hero_eyebrow'  => $current_term?->name ?? __('Categorie', 'sage'),
    'hero_title_em' => $current_term?->name ?? __('clinice', 'sage'),
    'hero_lede'     => $term_desc !== ''
      ? $term_desc
      : sprintf(__('Studii din categoria %s — peer-reviewed și verificabile public.', 'sage'), $current_term?->name ?? ''),
    'active_slug'   => $current_term?->slug ?? 'all',
    'current_term'  => $current_term,
  ])
@endsection
