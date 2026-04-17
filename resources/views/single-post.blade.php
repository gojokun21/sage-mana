{{--
  Single blog post — ported 1:1 from mana-naturii/single.php.
--}}

@extends('layouts.app')

@section('content')
  @while(have_posts())
    @php the_post() @endphp

    <section class="single_blog">
      <div class="container">
        <h1>{{ get_the_title() }}</h1>

        @php $thumbnail_url = get_the_post_thumbnail_url(get_the_ID(), 'full'); @endphp
        @if ($thumbnail_url)
          <img src="{{ esc_url($thumbnail_url) }}" alt="{{ esc_attr(get_the_title()) }}">
        @endif

        <div class="description_block">
          @php the_content() @endphp
        </div>
      </div>
    </section>
  @endwhile

  <section class="similary_posts">
    <div class="container">
      <div class="similary_posts__header">
        <div class="block_title">
          <h2>{{ __('Citiți și alte articole', 'sage') }}</h2>
        </div>

        <div class="related-nav-arrows">
          <div class="swiper-button-prev related-prev">
            <img src="{{ get_template_directory_uri() }}/assets/images/left-nav.svg" alt="">
            <svg class="swiper-navigation-icon" width="11" height="20" viewBox="0 0 11 20" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M0.38296 20.0762C0.111788 19.805 0.111788 19.3654 0.38296 19.0942L9.19758 10.2796L0.38296 1.46497C0.111788 1.19379 0.111788 0.754138 0.38296 0.482966C0.654131 0.211794 1.09379 0.211794 1.36496 0.482966L10.4341 9.55214C10.8359 9.9539 10.8359 10.6053 10.4341 11.007L1.36496 20.0762C1.09379 20.3474 0.654131 20.3474 0.38296 20.0762Z" fill="currentColor"/></svg>
          </div>
          <div class="swiper-button-next related-next">
            <img src="{{ get_template_directory_uri() }}/assets/images/right-nav.svg" alt="">
            <svg class="swiper-navigation-icon" width="11" height="20" viewBox="0 0 11 20" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M0.38296 20.0762C0.111788 19.805 0.111788 19.3654 0.38296 19.0942L9.19758 10.2796L0.38296 1.46497C0.111788 1.19379 0.111788 0.754138 0.38296 0.482966C0.654131 0.211794 1.09379 0.211794 1.36496 0.482966L10.4341 9.55214C10.8359 9.9539 10.8359 10.6053 10.4341 11.007L1.36496 20.0762C1.09379 20.3474 0.654131 20.3474 0.38296 20.0762Z" fill="currentColor"/></svg>
          </div>
        </div>
      </div>

      @php
        $related = new \WP_Query([
          'post_type'      => 'post',
          'posts_per_page' => 12,
          'orderby'        => 'date',
          'order'          => 'DESC',
          'post__not_in'   => [get_the_ID()],
          'no_found_rows'  => true,
        ]);
      @endphp

      <div class="blog_slider swiper">
        <div class="swiper-wrapper">
          @if ($related->have_posts())
            @while ($related->have_posts())
              @php
                $related->the_post();
                $img = get_the_post_thumbnail_url(get_the_ID(), 'medium_large');
              @endphp
              <div class="blog_item swiper-slide">
                <a href="{{ esc_url(get_permalink()) }}">
                  @if ($img)
                    <img src="{{ esc_url($img) }}" alt="{{ esc_attr(get_the_title()) }}">
                  @endif
                  <h3>{{ get_the_title() }}</h3>
                </a>
              </div>
            @endwhile
          @else
            <p>{{ __('Nu există articole disponibile.', 'sage') }}</p>
          @endif
        </div>

        <div class="blog-pagination swiper-pagination"></div>
      </div>

      @php wp_reset_postdata(); @endphp
    </div>
  </section>
@endsection
