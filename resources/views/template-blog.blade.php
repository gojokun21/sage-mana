{{--
  Template Name: Blog Template
  Ported 1:1 from mana-naturii/templates/blog-template.php.
--}}

@extends('layouts.app')

@section('content')
  <main class="blog_template" id="blog_template">

    @php
      // Hero = newest post.
      $first_query = new \WP_Query([
        'post_type' => 'post',
        'posts_per_page' => 1,
        'orderby' => 'date',
        'order' => 'DESC',
        'no_found_rows' => true,
      ]);
    @endphp

    @if ($first_query->have_posts())
      @while ($first_query->have_posts())
        @php
          $first_query->the_post();
          $hero_img = get_the_post_thumbnail_url(get_the_ID(), 'full');
        @endphp
        <section class="hero_blog">
          <div class="container">
            <div class="blog_item_slider">
              <div class="blog_img">
                <a href="{{ esc_url(get_permalink()) }}">
                  <img src="{{ esc_url($hero_img) }}" alt="{{ esc_attr(get_the_title()) }}">
                </a>

                <div class="blog_content">
                  <span>{{ get_the_date() }}</span>
                  <a href="{{ esc_url(get_permalink()) }}">
                    <h2>{{ get_the_title() }}</h2>
                  </a>
                  <p>{{ wp_trim_words(get_the_excerpt(), 15) }}</p>
                </div>
              </div>
            </div>
          </div>
        </section>
      @endwhile
      @php wp_reset_postdata(); @endphp
    @endif

    <section class="blog_section mg-100">
      <div class="container">
        <div class="block_title">
          <h1 class="fw-bold">{{ __('Toate articolele', 'sage') }}</h1>
        </div>

        @php
          $paged = get_query_var('paged') ? (int) get_query_var('paged') : 1;
          $initial_offset = 1; // skip the hero post
          $posts_per_page = 3;
          $offset = $initial_offset + (($paged - 1) * $posts_per_page);

          $blog_query = new \WP_Query([
            'post_type'      => 'post',
            'posts_per_page' => $posts_per_page,
            'orderby'        => 'date',
            'order'          => 'DESC',
            'offset'         => $offset,
            'paged'          => $paged,
          ]);
        @endphp

        <div class="blog_grid">
          @if ($blog_query->have_posts())
            @while ($blog_query->have_posts())
              @php $blog_query->the_post(); @endphp
              <div class="blog_item">
                <a href="{{ esc_url(get_permalink()) }}">
                  <img src="{{ esc_url(get_the_post_thumbnail_url(get_the_ID(), 'medium_large')) }}" alt="{{ esc_attr(get_the_title()) }}">
                  <h3>{{ get_the_title() }}</h3>
                </a>
              </div>
            @endwhile
          @else
            <p>{{ __('Nu există articole disponibile.', 'sage') }}</p>
          @endif
        </div>

        <div class="pagination">
          @php
            $big = 999999999;
            $paginate_links = paginate_links([
              'base'      => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
              'format'    => '?paged=%#%',
              'current'   => max(1, $paged),
              'total'     => $blog_query->max_num_pages,
              'type'      => 'array',
              'end_size'  => 1,
              'mid_size'  => 2,
              'prev_text' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>',
              'next_text' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>',
            ]);
          @endphp

          @if (is_array($paginate_links))
            <ul class="pagination_list">
              @foreach ($paginate_links as $link)
                <li>{!! $link !!}</li>
              @endforeach
            </ul>
          @endif
        </div>

        @php wp_reset_postdata(); @endphp
      </div>
    </section>
  </main>
@endsection
