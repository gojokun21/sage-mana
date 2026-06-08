{{--
  Blog — ultimele 3 postări reale.
  - Featured image când există; altfel fallback gradient + SVG (rotativ t1/t2/t3 pe index).
  - Categorie primară → chip; reading time calculat din content.
--}}
@php
  $blog_query = new \WP_Query([
    'post_type'      => 'post',
    'posts_per_page' => 3,
    'orderby'        => 'date',
    'order'          => 'DESC',
    'no_found_rows'  => true,
    'ignore_sticky_posts' => true,
  ]);

  $thumb_tones = ['t1', 't2', 't3'];

  $fallback_svgs = [
    // Ghid / carte deschisă
    '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2zM22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>',
    // Cercetare / molecule
    '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="m10 13-2 2 1.5 1.5L8 18l4 4 4-4-1.5-1.5L16 15l-2-2"/><circle cx="14" cy="6" r="4"/><path d="M14 10v3"/></svg>',
    // Mituri / scântei
    '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M12 2v6M12 16v6M2 12h6M16 12h6M5 5l4.5 4.5M14.5 14.5 19 19M5 19l4.5-4.5M14.5 9.5 19 5"/></svg>',
  ];

  $blog_archive_url = get_permalink(get_option('page_for_posts')) ?: home_url('/blog/');
@endphp

@if ($blog_query->have_posts())
  <section class="blog">
    <div class="blog-head">
      <div>
        <div class="eyebrow" style="margin-bottom:14px">{{ __('Din jurnalul nostru', 'sage') }}</div>
        <h2>{{ __('Ce scriem,', 'sage') }} <em>{{ __('când nu vindem.', 'sage') }}</em></h2>
      </div>
    </div>

    <div class="blog-grid">
      @php $i = 0; @endphp
      @while ($blog_query->have_posts())
        @php
          $blog_query->the_post();
          $post_id = get_the_ID();
          $title = html_entity_decode(get_the_title(), ENT_QUOTES, 'UTF-8');
          $permalink = get_permalink();
          $tone = $thumb_tones[$i % count($thumb_tones)];
          $fallback_svg = $fallback_svgs[$i % count($fallback_svgs)];

          $cats = get_the_category($post_id);
          $cat_name = (! empty($cats) && ! is_wp_error($cats)) ? $cats[0]->name : __('Articol', 'sage');

          $reading_time = max(2, (int) ceil(str_word_count(strip_tags(get_the_content())) / 200));

          $thumb_id = get_post_thumbnail_id($post_id);
          $thumb_html = $thumb_id
            ? wp_get_attachment_image($thumb_id, 'full', false, [
                'alt' => esc_attr($title),
                'sizes' => '(max-width: 900px) 90vw, 420px',
                'loading' => 'lazy',
                'decoding' => 'async',
              ])
            : '';
        @endphp

        <a class="blog-card" href="{{ esc_url($permalink) }}">
          <div class="blog-thumb {{ $tone }}{{ $thumb_html ? ' has-img' : '' }}">
            <span class="cat">{{ $cat_name }}</span>
            @if ($thumb_html)
              {!! $thumb_html !!}
            @else
              {!! $fallback_svg !!}
            @endif
          </div>
          <div class="meta">{{ esc_html($cat_name) }} · {{ sprintf(__('%d min citire', 'sage'), $reading_time) }}</div>
          <h3>{{ $title }}</h3>
        </a>
        @php $i++; @endphp
      @endwhile
      @php wp_reset_postdata(); @endphp
    </div>

    <div class="blog-foot">
      <a href="{{ esc_url($blog_archive_url) }}">{{ __('Toate articolele →', 'sage') }}</a>
    </div>
  </section>
@endif
