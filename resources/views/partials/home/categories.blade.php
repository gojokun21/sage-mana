{{--
  "Caută după categorii" — top-level product_cat slider (Swiper).
  Data from App\View\Composers\Home::topCategories(), excluding `pachete`.
--}}

@if (! empty($top_categories ?? []))
  <section class="home-section home-categories" aria-labelledby="home-categories-title">
    <div class="home-section__header">
      <h2 id="home-categories-title" class="home-section__title">{{ __('Caută după categorii', 'sage') }}</h2>
    </div>

    <div class="home-slider">
      <div class="home-slider__swiper swiper" data-home-swiper="categories">
        <div class="swiper-wrapper">
          @foreach ($top_categories as $cat)
            @php
              $thumb_id = get_term_meta($cat->term_id, 'thumbnail_id', true);
              $image = $thumb_id ? wp_get_attachment_url($thumb_id) : wc_placeholder_img_src();
              $alt = $thumb_id ? (string) get_post_meta($thumb_id, '_wp_attachment_image_alt', true) : '';
            @endphp

            <div class="swiper-slide home-category">
              <a href="{{ esc_url(get_term_link($cat)) }}" class="home-category__link">
                <div class="home-category__thumb">
                  <img src="{{ esc_url($image) }}"
                       alt="{{ esc_attr($alt ?: $cat->name) }}"
                       loading="lazy"
                       decoding="async">
                </div>
                <h3 class="home-category__name">{{ esc_html($cat->name) }}</h3>
              </a>
            </div>
          @endforeach
        </div>
      </div>

      <button type="button"
              class="home-slider__btn home-slider__btn--prev"
              aria-label="{{ esc_attr__('Anterior', 'sage') }}"
              data-home-slider-prev="categories">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" aria-hidden="true">
          <path d="M15 18l-6-6 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
      </button>
      <button type="button"
              class="home-slider__btn home-slider__btn--next"
              aria-label="{{ esc_attr__('Următorul', 'sage') }}"
              data-home-slider-next="categories">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" aria-hidden="true">
          <path d="M9 18l6-6-6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
      </button>

      <div class="home-slider__pagination" data-home-slider-pagination="categories"></div>
    </div>
  </section>
@endif
