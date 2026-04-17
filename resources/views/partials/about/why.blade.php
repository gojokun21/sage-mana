{{--
  About: Why Natura section
  ACF page-level group `why_section`:
    - title_section (text)
    - content_section (wysiwyg)
    - button group: title, link
    - image_1, image_2
--}}

@php
  if (! function_exists('have_rows') || ! have_rows('why_section')) {
    return;
  }
@endphp

@while (have_rows('why_section'))
  @php
    the_row();
    $title = get_sub_field('title_section');
    $content = get_sub_field('content_section');

    $image_1 = get_sub_field('image_1');
    $image_1_url = is_array($image_1) ? ($image_1['url'] ?? '') : $image_1;
    $image_1_alt = is_array($image_1) ? ($image_1['alt'] ?? '') : '';

    $image_2 = get_sub_field('image_2');
    $image_2_url = is_array($image_2) ? ($image_2['url'] ?? '') : $image_2;
    $image_2_alt = is_array($image_2) ? ($image_2['alt'] ?? '') : '';

    $button_title = '';
    $button_link = '';
    if (have_rows('button')) {
      while (have_rows('button')) {
        the_row();
        $button_title = get_sub_field('title');
        $button_link = get_sub_field('link');
      }
    }
  @endphp

  <div class="why_section">
    <div class="container">
      <div class="why_row">
        <div class="why_left">
          <div class="why_content">
            <div class="why_texts">
              @if ($title)<h2>{{ $title }}</h2>@endif
              @if ($content)
                <div class="why_content__body">
                  {!! apply_filters('the_content', $content) !!}
                </div>
              @endif
            </div>
            @if ($button_title && $button_link)
              <a class="btn main_btn why_btn" href="{{ esc_url($button_link) }}">
                {{ $button_title }}
              </a>
            @endif
          </div>
        </div>

        <div class="why_right">
          @if ($image_1_url)
            <div class="why_right__col">
              <img src="{{ esc_url($image_1_url) }}"
                   alt="{{ esc_attr($image_1_alt ?: $title) }}"
                   loading="lazy"
                   decoding="async">
            </div>
          @endif
          @if ($image_2_url)
            <div class="why_right__col">
              <img src="{{ esc_url($image_2_url) }}"
                   alt="{{ esc_attr($image_2_alt ?: $title) }}"
                   loading="lazy"
                   decoding="async">
            </div>
          @endif
        </div>
      </div>
    </div>
  </div>
@endwhile
