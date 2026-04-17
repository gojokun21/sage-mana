{{--
  Green banner with content on the left and image on the right.
  ACF: `banner_section` group (page-level) with
    - content_section (WYSIWYG)
    - cta_button repeater: name, link
    - image
--}}

@php
  if (! function_exists('have_rows') || ! have_rows('banner_section')) {
    return;
  }
@endphp

@while (have_rows('banner_section'))
  @php
    the_row();
    $content = get_sub_field('content_section');
    $image = get_sub_field('image');
    $image_url = is_array($image) ? ($image['url'] ?? '') : $image;
    $image_alt = is_array($image) ? ($image['alt'] ?? '') : '';
  @endphp

  <section class="home-section home-banner">
    <div class="home-banner__inner">
      <div class="home-banner__left">
        <div class="home-banner__content">
          @if ($content)
            {!! apply_filters('the_content', $content) !!}
          @endif

          @if (have_rows('cta_button'))
            @while (have_rows('cta_button'))
              @php
                the_row();
                $cta_name = get_sub_field('name');
                $cta_link = get_sub_field('link');
              @endphp
              @if ($cta_link && $cta_name)
                <a class="home-banner__cta" href="{{ esc_url($cta_link) }}">
                  {{ esc_html($cta_name) }}
                </a>
              @endif
            @endwhile
          @endif
        </div>
      </div>

      @if ($image_url)
        <div class="home-banner__right">
          <img src="{{ esc_url($image_url) }}"
               alt="{{ esc_attr($image_alt) }}"
               loading="lazy"
               decoding="async">
        </div>
      @endif
    </div>
  </section>
@endwhile
