{{--
  Template Name: Favorite
--}}

@extends('layouts.app')

@section('content')
  {!! \App\render_favorites_list(['columns' => 4]) !!}
@endsection
