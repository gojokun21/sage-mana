{{--
  Customer reviews — About page.
  Same ACF Options source as home (`reviews_section`), but rendered
  inside its own `.container`.
--}}

@php
  if (! function_exists('have_rows') || ! have_rows('reviews_section', 'options')) {
    return;
  }
@endphp

@while (have_rows('reviews_section', 'options'))
  @php
    the_row();
    $hero_image = get_sub_field('image');
    $hero_image_url = is_array($hero_image) ? ($hero_image['url'] ?? '') : $hero_image;
    $hero_image_alt = is_array($hero_image) ? ($hero_image['alt'] ?? '') : '';
  @endphp

  @if (have_rows('items'))
    <section class="home-section home-reviews about-reviews" aria-label="{{ esc_attr__('Recenzii clienți', 'sage') }}">
      <div class="container">
        <div class="home-reviews__grid">
          @if ($hero_image_url)
            <div class="home-reviews__left">
              <img src="{{ esc_url($hero_image_url) }}"
                   alt="{{ esc_attr($hero_image_alt) }}"
                   loading="lazy"
                   decoding="async">
            </div>
          @endif

          <div class="home-reviews__right">
            <div class="home-slider">
              <div class="home-slider__swiper swiper" data-home-swiper="reviews">
                <div class="swiper-wrapper">
                  @while (have_rows('items'))
                    @php
                      the_row();
                      $profile_image = get_sub_field('profile_image');
                      $profile_name = get_sub_field('profile_name');
                      $content = get_sub_field('content');
                      $link = get_sub_field('link_product');
                      $product_image = get_sub_field('product_image');
                      $product_title = get_sub_field('product_title');
                      $product_price = get_sub_field('product_price');

                      $profile_url = is_array($profile_image) ? ($profile_image['url'] ?? '') : $profile_image;
                      $profile_alt = is_array($profile_image) ? ($profile_image['alt'] ?? '') : $profile_name;
                      $product_img_url = is_array($product_image) ? ($product_image['url'] ?? '') : $product_image;
                      $product_img_alt = is_array($product_image) ? ($product_image['alt'] ?? '') : $product_title;
                    @endphp

                    <div class="swiper-slide home-review">
                      <div class="home-review__head">
                        @if ($profile_url)
                          <img class="home-review__avatar"
                               src="{{ esc_url($profile_url) }}"
                               alt="{{ esc_attr($profile_alt) }}"
                               loading="lazy"
                               decoding="async"
                               width="56"
                               height="56">
                        @endif
                        @if ($profile_name)
                          <h3 class="home-review__name">{{ esc_html($profile_name) }}</h3>
                        @endif
                      </div>

                      @if ($content)
                        <div class="home-review__content">
                          {!! apply_filters('the_content', $content) !!}
                        </div>
                      @endif

                      @if ($product_img_url || $product_title)
                        <div class="home-review__divider"></div>

                        <div class="home-review__product">
                          @if ($link && $product_img_url)
                            <a href="{{ esc_url($link) }}" class="home-review__product-thumb">
                              <img src="{{ esc_url($product_img_url) }}"
                                   alt="{{ esc_attr($product_img_alt) }}"
                                   loading="lazy"
                                   decoding="async">
                            </a>
                          @elseif ($product_img_url)
                            <div class="home-review__product-thumb">
                              <img src="{{ esc_url($product_img_url) }}"
                                   alt="{{ esc_attr($product_img_alt) }}"
                                   loading="lazy"
                                   decoding="async">
                            </div>
                          @endif

                          <div class="home-review__product-info">
                            @if ($product_title)
                              @if ($link)
                                <a href="{{ esc_url($link) }}" class="home-review__product-title">
                                  {{ esc_html($product_title) }}
                                </a>
                              @else
                                <span class="home-review__product-title">{{ esc_html($product_title) }}</span>
                              @endif
                            @endif
                            @if ($product_price)
                              <span class="home-review__product-price">{!! wp_kses_post($product_price) !!}</span>
                            @endif
                          </div>

                          @if ($link)
                            <a class="btn-primary home-review__cta" href="{{ esc_url($link) }}">
                              {{ __('Vezi produs', 'sage') }}
                            </a>
                          @endif
                        </div>
                      @endif
                    </div>
                  @endwhile
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  @endif
@endwhile
