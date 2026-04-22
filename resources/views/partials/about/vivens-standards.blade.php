{{--
  About: Vivens Standards section
  ACF page-level group `vivens_standards_section`:
    - subtitle (text)
    - title_section (text)
    - description (wysiwyg)
    - certificates repeater: image
    - image (image)
--}}

@php
  if (! function_exists('have_rows') || ! have_rows('vivens_standards_section')) {
    return;
  }
@endphp

@while (have_rows('vivens_standards_section'))
  @php
    the_row();
    $subtitle = get_sub_field('subtitle');
    $title = get_sub_field('title_section');
    $description = get_sub_field('description');
    $image = get_sub_field('image');
    $image_url = is_array($image) ? ($image['url'] ?? '') : $image;
    $image_alt = is_array($image) ? ($image['alt'] ?? '') : '';
  @endphp

  <section class="vivens_standards">
    <div class="container">
      <div class="standard_wrapper">
        <div class="standard_description">
          <div class="standard_description__inner">
            @if ($subtitle)
              <div class="brand_title">
                <h3>{{ $subtitle }}</h3>
              </div>
            @endif

            @if ($title)
              <h2>{{ $title }}</h2>
            @endif

            @if ($description)
              <div class="decription">
                {!! wp_kses_post($description) !!}
              </div>
            @endif

            @if (have_rows('certificates'))
              <div class="certificates_standards">
                @while (have_rows('certificates'))
                  @php
                    the_row();
                    $cert = get_sub_field('image');
                    $cert_url = is_array($cert) ? ($cert['url'] ?? '') : $cert;
                    $cert_alt = is_array($cert) ? ($cert['alt'] ?? '') : '';
                  @endphp
                  @if ($cert_url)
                    <span class="certificate_chip">
                      <img src="{{ esc_url($cert_url) }}"
                           alt="{{ esc_attr($cert_alt) }}"
                           loading="lazy"
                           decoding="async">
                    </span>
                  @endif
                @endwhile
              </div>
            @endif
          </div>
        </div>

        @if ($image_url)
          <div class="image_standard">
            <img src="{{ esc_url($image_url) }}"
                 alt="{{ esc_attr($image_alt ?: $title) }}"
                 loading="lazy"
                 decoding="async">
          </div>
        @endif
      </div>
    </div>
  </section>
@endwhile
