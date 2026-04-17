{{--
  Partial: favorites list (rendered by [natura_favorites_list] shortcode).

  Vars:
    $ids         int[]  — favorite product IDs
    $columns     int    — desktop columns (1..4)
    $empty_text  string — message shown when the list is empty
--}}

@php
  $count = count($ids);
  $shop_url = function_exists('wc_get_page_permalink') ? wc_get_page_permalink('shop') : home_url('/magazin/');
@endphp

<section class="natura-favorites">
  <div class="container">
  <header class="natura-favorites__header">
    <h1 class="natura-favorites__title">{{ __('Produse favorite', 'sage') }}</h1>

    @if ($count > 0)
      <p class="natura-favorites__count">
        {{ sprintf(_n('%d produs salvat', '%d produse salvate', $count, 'sage'), $count) }}
      </p>
    @endif
  </header>

  @if ($count === 0)
    <div class="natura-favorites-empty">
      <svg width="64" height="64" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
        <path d="M12 21s-7.5-4.35-9.5-9.04C1.2 8.16 3.4 4.5 7.13 4.5c2.02 0 3.57 1.03 4.87 2.73 1.3-1.7 2.85-2.73 4.87-2.73 3.73 0 5.93 3.66 4.63 7.46C19.5 16.65 12 21 12 21Z" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round"/>
      </svg>

      <p class="natura-favorites-empty__text">{{ $empty_text }}</p>

      <a href="{{ esc_url($shop_url) }}" class="btn-primary natura-favorites-empty__cta">
        {{ __('Descoperă produsele', 'sage') }}
      </a>
    </div>
  @else
    @php
      $q = new \WP_Query([
        'post_type' => 'product',
        'posts_per_page' => -1,
        'post__in' => $ids,
        'orderby' => 'post__in',
        'post_status' => 'publish',
        'ignore_sticky_posts' => true,
      ]);
    @endphp

    <ul class="products natura-favorites-list"
        data-columns="{{ $columns }}"
        style="--natura-fav-cols: {{ $columns }}">
      @if ($q->have_posts())
        @while ($q->have_posts())
          @php $q->the_post() @endphp
          @php wc_get_template_part('content', 'product') @endphp
        @endwhile
      @endif
    </ul>

    @php wp_reset_postdata() @endphp
  @endif
  </div>
</section>
