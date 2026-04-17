{{--
  Hero slider.
  ACF: `hero_section` group (page-level) with a `sliders` repeater.
  Each slide: `image`, `mobile_image`, `link`.
--}}

@php
  if (! function_exists('have_rows') || ! have_rows('hero_section')) {
    return;
  }
@endphp

@while (have_rows('hero_section'))
  @php the_row() @endphp

  @if (have_rows('sliders'))
    <section class="hero-section" aria-label="{{ esc_attr__('Prezentare', 'sage') }}">
      <div class="hero-swiper swiper" data-hero-swiper>
        <div class="swiper-wrapper">
          @php $i = 0; @endphp
          @while (have_rows('sliders'))
            @php
              the_row();
              $i++;
              $image = get_sub_field('image');
              $mobile = get_sub_field('mobile_image');
              $link = get_sub_field('link');

              $image_url = is_array($image) ? ($image['url'] ?? '') : $image;
              $image_alt = is_array($image) ? ($image['alt'] ?? '') : '';
              $mobile_url = is_array($mobile) ? ($mobile['url'] ?? '') : $mobile;
              $is_first = $i === 1;
            @endphp

            <div class="swiper-slide hero-slide">
              @if ($link)
                <a href="{{ esc_url($link) }}" class="hero-slide__link">
              @endif

              <picture>
                @if ($mobile_url)
                  <source media="(max-width: 768px)" srcset="{{ esc_url($mobile_url) }}">
                @endif
                <img src="{{ esc_url($image_url) }}"
                     alt="{{ esc_attr($image_alt) }}"
                     @if ($is_first) fetchpriority="high" loading="eager" @else loading="lazy" @endif
                     decoding="async">
              </picture>

              @if ($link)
                </a>
              @endif
            </div>
          @endwhile
        </div>

        <button type="button"
                class="hero-swiper__btn hero-swiper__btn--prev"
                aria-label="{{ esc_attr__('Slide anterior', 'sage') }}"
                data-hero-prev>
          <svg width="22" height="22" viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M15 18l-6-6 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
        </button>
        <button type="button"
                class="hero-swiper__btn hero-swiper__btn--next"
                aria-label="{{ esc_attr__('Slide următor', 'sage') }}"
                data-hero-next>
          <svg width="22" height="22" viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M9 18l6-6-6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
        </button>

        <div class="hero-swiper__pagination" data-hero-pagination></div>
      </div>
    </section>
  @endif
@endwhile
