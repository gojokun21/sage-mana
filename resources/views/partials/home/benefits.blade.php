{{--
  Benefits strip.
  ACF: `benefits_section` group (page-level) with an `items` repeater.
  Each item: `icon` (image), `title` (text), `description` (textarea).
--}}

@php
  if (! function_exists('have_rows') || ! have_rows('benefits_section')) {
    return;
  }
@endphp

@while (have_rows('benefits_section'))
  @php the_row() @endphp

  @if (have_rows('items'))
    <section class="home-section home-benefits" aria-label="{{ esc_attr__('Beneficii', 'sage') }}">
      <div class="home-slider">
        <div class="home-slider__swiper swiper" data-home-swiper="benefits">
          <div class="swiper-wrapper">
            @while (have_rows('items'))
              @php
                the_row();
                $icon = get_sub_field('icon');
                $title = get_sub_field('title');
                $description = get_sub_field('description');

                $icon_url = is_array($icon) ? ($icon['url'] ?? '') : $icon;
                $icon_alt = is_array($icon) ? ($icon['alt'] ?? '') : '';
              @endphp

              <div class="swiper-slide home-benefit">
                @if ($icon_url)
                  <img class="home-benefit__icon"
                       src="{{ esc_url($icon_url) }}"
                       alt="{{ esc_attr($icon_alt ?: $title) }}"
                       loading="lazy"
                       decoding="async"
                       width="40"
                       height="40">
                @endif
                <div class="home-benefit__content">
                  @if ($title)
                    <h3 class="home-benefit__title">{{ $title }}</h3>
                  @endif
                  @if ($description)
                    <p class="home-benefit__description">{!! wp_kses_post($description) !!}</p>
                  @endif
                </div>
              </div>
            @endwhile
          </div>
        </div>

        <button type="button"
                class="home-slider__btn home-slider__btn--prev"
                aria-label="{{ esc_attr__('Anterior', 'sage') }}"
                data-home-slider-prev="benefits">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M15 18l-6-6 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
        </button>
        <button type="button"
                class="home-slider__btn home-slider__btn--next"
                aria-label="{{ esc_attr__('Următorul', 'sage') }}"
                data-home-slider-next="benefits">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M9 18l6-6-6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
        </button>

        <div class="home-slider__pagination" data-home-slider-pagination="benefits"></div>
      </div>
    </section>
  @endif
@endwhile
