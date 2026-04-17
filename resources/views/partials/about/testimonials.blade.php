{{--
  Video testimonials — About page.
  Same ACF Options source as home (`testimonials_section`), but rendered
  inside its own `.container` so it doesn't stretch with the about-template.
--}}

@php
  if (! function_exists('have_rows') || ! have_rows('testimonials_section', 'options')) {
    return;
  }

  $catalog_url = apply_filters('natura_home_catalog_url', home_url('/catalog/'));
@endphp

@while (have_rows('testimonials_section', 'options'))
  @php the_row() @endphp

  @php
    $title = get_sub_field('title_section');
    $description = get_sub_field('description_section');
  @endphp

  @if (have_rows('items'))
    <section class="home-section home-testimonials about-testimonials" aria-labelledby="about-testimonials-title">
      <div class="container">
        @if ($title || $description)
          <div class="home-section__header">
            @if ($title)
              <h2 id="about-testimonials-title" class="home-section__title">{{ $title }}</h2>
            @endif
            @if ($description)
              <p class="home-section__subtitle">{!! wp_kses_post($description) !!}</p>
            @endif
          </div>
        @endif

        <div class="home-slider">
          <div class="home-slider__swiper swiper" data-home-swiper="testimonials">
            <div class="swiper-wrapper">
              @while (have_rows('items'))
                @php
                  the_row();
                  $image = get_sub_field('image');
                  $product_name = get_sub_field('product_name');
                  $video = get_sub_field('video_file');

                  $image_url = is_array($image) ? ($image['url'] ?? '') : $image;
                  $image_alt = is_array($image) ? ($image['alt'] ?? '') : '';
                  $video_url = is_array($video) ? ($video['url'] ?? '') : $video;
                @endphp

                <div class="swiper-slide home-testimonial">
                  <div class="home-testimonial__thumb">
                    @if ($image_url)
                      <img src="{{ esc_url($image_url) }}"
                           alt="{{ esc_attr($image_alt ?: $product_name) }}"
                           loading="lazy"
                           decoding="async">
                    @endif

                    @if ($video_url)
                      <a class="home-testimonial__play"
                         data-fancybox="about-testimonials"
                         data-thumb="{{ esc_url($image_url) }}"
                         @if ($product_name) data-caption="{{ esc_attr($product_name) }}" @endif
                         href="{{ esc_url($video_url) }}"
                         aria-label="{{ esc_attr__('Vezi recenzia clienților noștri', 'sage') }}">
                        <img src="https://mananaturii.ro/wp-content/themes/mana-naturii/assets/images/icons/play-solid.svg" alt="{{ esc_attr__('Vezi recenzia clienților noștri', 'sage') }}">
                      </a>
                    @endif

                    <div class="home-testimonial__overlay">
                      <a class="btn-primary" href="{{ esc_url($catalog_url) }}">
                        {{ __('Vezi Catalog', 'sage') }}
                      </a>
                    </div>
                  </div>
                </div>
              @endwhile
            </div>
          </div>

          <button type="button"
                  class="home-slider__btn home-slider__btn--prev"
                  aria-label="{{ esc_attr__('Anterior', 'sage') }}"
                  data-home-slider-prev="testimonials">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" aria-hidden="true">
              <path d="M15 18l-6-6 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </button>
          <button type="button"
                  class="home-slider__btn home-slider__btn--next"
                  aria-label="{{ esc_attr__('Următorul', 'sage') }}"
                  data-home-slider-next="testimonials">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" aria-hidden="true">
              <path d="M9 18l6-6-6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </button>

          <div class="home-slider__pagination" data-home-slider-pagination="testimonials"></div>
        </div>
      </div>
    </section>
  @endif
@endwhile
