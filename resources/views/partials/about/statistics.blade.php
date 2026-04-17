{{--
  About: Statistics slider
  ACF page-level group `about_section`:
    - title_section (text)
    - items repeater: image, title, content
--}}

@php
  if (! function_exists('have_rows') || ! have_rows('about_section')) {
    return;
  }
@endphp

@while (have_rows('about_section'))
  @php
    the_row();
    $title = get_sub_field('title_section');
  @endphp

  <section class="about_statistics">
    <div class="container">
      @if ($title)
        <div class="block_title">
          <h2 class="block_title__heading">{{ $title }}</h2>
        </div>
      @endif

      @if (have_rows('items'))
        <div class="stats_grid swiper">
          <div class="swiper-wrapper">
            @while (have_rows('items'))
              @php
                the_row();
                $img = get_sub_field('image');
                $img_url = is_array($img) ? ($img['url'] ?? '') : $img;
                $img_alt = is_array($img) ? ($img['alt'] ?? '') : '';
                $item_title = get_sub_field('title');
                $content = get_sub_field('content');
              @endphp

              <div class="stat_item swiper-slide">
                @if ($img_url)
                  <img src="{{ esc_url($img_url) }}"
                       alt="{{ esc_attr($img_alt ?: $item_title) }}"
                       loading="lazy"
                       decoding="async">
                @endif
                @if ($item_title)
                  <h2>{{ $item_title }}</h2>
                @endif
                @if ($content)
                  <p>{!! wp_kses_post($content) !!}</p>
                @endif
              </div>
            @endwhile
          </div>
          <div class="swiper-pagination stats-pagination"></div>
        </div>
      @endif
    </div>
  </section>
@endwhile
