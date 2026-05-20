{{--
  About: Hero section
  ACF page-level group `hero_section` with:
    - description (WYSIWYG)
    - image (URL or array)
--}}

@php
  if (! function_exists('have_rows') || ! have_rows('hero_section')) {
    return;
  }
@endphp

@while (have_rows('hero_section'))
  @php
    the_row();
    $description = get_sub_field('description');
    $image = get_sub_field('image');
    $image_url = is_array($image) ? ($image['url'] ?? '') : $image;
    $image_alt = is_array($image) ? ($image['alt'] ?? '') : '';
  @endphp

  <section class="about_section">
    <div class="container">
      <div class="about_row">
        <div class="about_right">
          <div class="bg_green">
            <h1 class="about_title">{{ html_entity_decode(get_the_title(), ENT_QUOTES, 'UTF-8') }}</h1>

            <div class="about_description" data-readmore>
              <div class="about_description__inner">
                {!! apply_filters('the_content', $description) !!}
              </div>
            </div>

            <button class="about_readmore_btn"
                    type="button"
                    data-readmore-toggle
                    aria-expanded="false">
              {{ __('Află mai mult', 'sage') }}
            </button>
          </div>
        </div>

        <div class="about_left">
          <div class="bg_white">
            <div class="about_img">
              @if ($image_url)
                <img src="{{ esc_url($image_url) }}"
                     alt="{{ esc_attr($image_alt ?: get_the_title()) }}"
                     loading="eager"
                     decoding="async">
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
@endwhile
