@extends('layouts.app')

@section('content')
  @while(have_posts()) @php(the_post())
    <main class="single_page">
      <div class="container">
        @include('partials.page-header')
        <article @php(post_class('page-content'))>
          @includeFirst(['partials.content-page', 'partials.content'])
        </article>
      </div>
    </main>
  @endwhile
@endsection
