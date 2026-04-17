{{--
  Template Name: Contact Template
--}}

@extends('layouts.app')

@section('content')
  <main class="contact-template" id="contact-template">
    <section class="contact_sec">
      <div class="container">
        <h1 class="contact_title">{{ get_the_title() }}</h1>

        <div class="contact_row">
          {{-- ============ Left column ============ --}}
          <div class="contact_left">
            @php $description = get_field('description_section'); @endphp
            @if ($description)
              <div class="contact_description">
                {!! apply_filters('the_content', $description) !!}
              </div>
            @endif

            <div class="map-contacts">
              @if (function_exists('have_rows') && have_rows('contact_section'))
                @while (have_rows('contact_section')) @php the_row() @endphp
                  <div class="contacts">
                    <div class="phone-mail">
                      @if (have_rows('telefon'))
                        @while (have_rows('telefon')) @php the_row() @endphp
                          <div class="phone c-mini-block">
                            <span>{{ __('Telefon:', 'sage') }}</span>
                            <a href="tel:{{ esc_attr(get_sub_field('link_numar')) }}" class="c-mini-block-title">
                              {{ get_sub_field('numarul_de_telefon') }}
                            </a>
                          </div>
                        @endwhile
                      @endif

                      @php $mail = get_sub_field('mail'); @endphp
                      @if ($mail)
                        <div class="mail c-mini-block">
                          <span>{{ __('Email:', 'sage') }}</span>
                          <a href="mailto:{{ esc_attr($mail) }}" class="c-mini-block-title">
                            {{ $mail }}
                          </a>
                        </div>
                      @endif
                    </div>
                  </div>
                @endwhile
              @endif

              @php
                $banner = get_field('image');
                $banner_url = is_array($banner) ? ($banner['url'] ?? '') : $banner;
                $banner_alt = is_array($banner) ? ($banner['alt'] ?? '') : '';
              @endphp
              @if ($banner_url)
                <div class="banner_contact">
                  <img src="{{ esc_url($banner_url) }}"
                       alt="{{ esc_attr($banner_alt ?: get_the_title()) }}"
                       loading="lazy"
                       decoding="async">
                </div>
              @endif
            </div>
          </div>

          {{-- ============ Right column (form) ============ --}}
          <div class="contact_right">
            <div class="contact_form">
              @php
                $cf7_shortcode = apply_filters(
                  'natura_contact_form_shortcode',
                  '[contact-form-7 id="f9e38cb" title="Contacteaza-ne"]'
                );
              @endphp
              {!! do_shortcode($cf7_shortcode) !!}
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>
@endsection
