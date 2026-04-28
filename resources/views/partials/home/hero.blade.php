{{--
  Hero slider.
  ACF: `hero_section` group (page-level) with a `sliders` repeater.
  Each slide: `image`, `mobile_image`, `link`.

  LCP-sensitive: the first slide image is preloaded from app/perf.php.
  Here we emit intrinsic width/height and use `wp_get_attachment_image_src`
  with a concrete size so mobiles don't download the full desktop asset.
--}}

@php
  if (! function_exists('have_rows') || ! have_rows('hero_section')) {
    return;
  }

  /**
   * Normalize ACF image value to [id, url, width, height]. Uses
   * `\App\acf_image_id()` so Image URL / Image Array / Image ID return
   * formats all resolve to a usable ID — critical for emitting srcset.
   */
  $normalize_image = function ($image, string $size = 'full'): array {
    $id = \App\acf_image_id($image);
    $url = '';
    $width = null;
    $height = null;

    if ($id) {
      $src = wp_get_attachment_image_src($id, $size);
      if ($src) {
        [$url, $width, $height] = $src;
      }
    }

    if (! $url) {
      if (is_array($image)) {
        $url = $image['url'] ?? '';
        $width = $image['width'] ?? null;
        $height = $image['height'] ?? null;
      } elseif (is_string($image)) {
        $url = $image;
      }
    }

    return [$id, $url, $width, $height];
  };
@endphp

@while (have_rows('hero_section'))
  @php the_row() @endphp

  @if (have_rows('sliders'))
    <section class="hero-section" aria-label="{{ esc_attr__('Prezentare', 'sage') }}">
      <h1 class="sr-only">{{ __('Suplimente Naturale Premium Vivens Genetica', 'sage') }}</h1>
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

              [$image_id, $image_url, $image_w, $image_h] = $normalize_image($image, 'full');
              [$mobile_id, $mobile_url, $mobile_w, $mobile_h] = $normalize_image($mobile, 'large');

              // Auto-generated srcset from every registered size (150/300/600/768/1024).
              // Lets the browser pick the smallest variant that covers the viewport × DPR.
              $image_srcset = $image_id ? wp_get_attachment_image_srcset($image_id, 'full') : '';
              $mobile_srcset = $mobile_id ? wp_get_attachment_image_srcset($mobile_id, 'large') : '';

              $image_alt = is_array($image) ? ($image['alt'] ?? '') : '';
              $is_first = $i === 1;
            @endphp

            <div class="swiper-slide hero-slide">
              @if ($link)
                <a href="{{ esc_url($link) }}" class="hero-slide__link">
              @endif

              <picture>
                @if ($mobile_url)
                  <source media="(max-width: 768px)"
                          srcset="{{ $mobile_srcset ?: esc_url($mobile_url) }}"
                          sizes="100vw"
                          @if ($mobile_w) width="{{ $mobile_w }}" @endif
                          @if ($mobile_h) height="{{ $mobile_h }}" @endif>
                @endif
                <img src="{{ esc_url($image_url) }}"
                     @if ($image_srcset) srcset="{{ $image_srcset }}" sizes="100vw" @endif
                     alt="{{ esc_attr($image_alt) }}"
                     @if ($image_w) width="{{ $image_w }}" @endif
                     @if ($image_h) height="{{ $image_h }}" @endif
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
      </div>
    </section>
  @endif
@endwhile
