{{--
  Template Name: Pachete
  Listare a tuturor produselor de tip `bundle` cu un hero similar
  paginii de catalog (archive-product). Atat hero-ul cat si grid-ul
  de produse refolosesc clasele existente (.hero_archive, .archive-product-wrap)
  ca sa pastreze paleta si responsive-ul temei.
--}}

@extends('layouts.app')

@section('content')
  @php
    $page_id = get_the_ID();

    // Hero — featured image-ul paginii (desktop) + ACF `mobile_image` pentru mobile.
    $bg_url = get_the_post_thumbnail_url($page_id, 'full') ?: '';

    $mobile_image = function_exists('get_field') ? get_field('mobile_image', $page_id) : null;
    $bg_url_mobile = '';
    if ($mobile_image) {
        $bg_url_mobile = is_array($mobile_image) ? ($mobile_image['url'] ?? '') : $mobile_image;
    }
    if ($bg_url_mobile === '') {
        $bg_url_mobile = $bg_url;
    }

    $hero_title    = __('PACHETE TEMATICE', 'sage');
    $hero_subtitle = __('Combinații sinergice de suplimente naturale, gândite pentru imunitate, detoxifiere, focus, digestie și energie.', 'sage');
    $hero_benefits = [
        ['icon' => 'truck',     'label' => __('Transport gratuit',    'sage'), 'desc' => __('peste 300 RON',            'sage')],
        ['icon' => 'calendar',  'label' => __('Cure complete',         'sage'), 'desc' => __('de 33–120 zile',           'sage')],
        ['icon' => 'leaf',      'label' => __('Ingrediente premium,',  'sage'), 'desc' => __('formule clinic dovedite',  'sage')],
    ];
    $hero_alt = get_the_title($page_id);

    // CTA "Cum funcționează" — URL din ACF sau fallback la '#'.
    $cta_how_url = function_exists('get_field') ? (get_field('cum_functioneaza_url', $page_id) ?: '#') : '#';

    // Avatare din repeaterul `reviews_section` (options).
    $review_avatars = [];
    if (function_exists('get_field')) {
        $rev_rows = get_field('reviews_section', 'options');
        if (is_array($rev_rows)) {
            foreach ($rev_rows as $rev_row) {
                if (!empty($rev_row['items']) && is_array($rev_row['items'])) {
                    foreach ($rev_row['items'] as $item) {
                        $img = $item['profile_image'] ?? null;
                        $url = is_array($img) ? ($img['url'] ?? '') : (is_string($img) ? $img : '');
                        if ($url) $review_avatars[] = $url;
                        if (count($review_avatars) >= 4) break 2;
                    }
                }
            }
        }
    }

    $review_count_text = '+5.000 clienți mulțumiți';

    // Paginare — pe page templates WP populeaza `page`, pe arhive `paged`.
    $paged = max(1, (int) (get_query_var('paged') ?: get_query_var('page') ?: 1));

    $bundles_query = new \WP_Query([
        'post_type'      => 'product',
        'post_status'    => 'publish',
        'posts_per_page' => 12,
        'paged'          => $paged,
        'orderby'        => 'date',
        'order'          => 'DESC',
        'tax_query'      => [
            [
                'taxonomy' => 'product_type',
                'field'    => 'slug',
                'terms'    => 'bundle',
            ],
        ],
    ]);
  @endphp

  <div class="archive-product-wrap pachete-archive">

    {{-- ============================================================ --}}
    {{-- HERO                                                          --}}
    {{-- ============================================================ --}}
    <div class="pachete-hero">
      <div class="pachete-hero__wrap">

        {{-- Stânga: text + CTA --}}
        <div class="pachete-hero__left">

          <div class="pachete-hero__badge">
            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10z"/><path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12"/></svg>
            {{ __('SĂNĂTATE • ENERGIE • ECHILIBRU', 'sage') }}
          </div>

          <h2 class="pachete-hero__title">{{ $hero_title }}</h2>

          <p class="pachete-hero__desc">{{ $hero_subtitle }}</p>

          <ul class="pachete-hero__benefits">
            @foreach ($hero_benefits as $benefit)
              <li class="pachete-hero__benefit">
                <span class="pachete-hero__benefit-icon" aria-hidden="true">
                  @if ($benefit['icon'] === 'truck')
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                      <path d="M12 3 3 7.5v9L12 21l9-4.5v-9L12 3Z" fill="currentColor" opacity="0.18"/>
                      <path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/>
                      <path d="m3.3 7 8.7 5 8.7-5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                      <path d="M12 22V12" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
                    </svg>
                  @elseif ($benefit['icon'] === 'calendar')
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                      <path d="M7 2h10v4.17a2 2 0 0 1-.59 1.42L12 12l4.41 4.41A2 2 0 0 1 17 17.83V22H7v-4.17a2 2 0 0 1 .59-1.42L12 12 7.59 7.59A2 2 0 0 1 7 6.17V2Z" fill="currentColor" opacity="0.18"/>
                      <path d="M5 22h14M5 2h14" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
                      <path d="M17 22v-4.172a2 2 0 0 0-.586-1.414L12 12l-4.414 4.414A2 2 0 0 0 7 17.828V22" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/>
                      <path d="M7 2v4.172a2 2 0 0 0 .586 1.414L12 12l4.414-4.414A2 2 0 0 0 17 6.172V2" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/>
                    </svg>
                  @else
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                      <circle cx="12" cy="9" r="6" fill="currentColor" opacity="0.18"/>
                      <circle cx="12" cy="9" r="6" stroke="currentColor" stroke-width="1.6"/>
                      <path d="m15.477 12.89 1.515 8.526a.5.5 0 0 1-.81.47l-3.58-2.687a1 1 0 0 0-1.197 0l-3.586 2.686a.5.5 0 0 1-.81-.469l1.514-8.526" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/>
                    </svg>
                  @endif
                </span>
                <div class="pachete-hero__benefit-text">
                  <strong>{{ $benefit['label'] }}</strong>
                  <span>{{ $benefit['desc'] }}</span>
                </div>
              </li>
            @endforeach
</ul>
        </div>

        {{-- Dreapta: imaginea paginii --}}
        @if ($bg_url)
          <div class="pachete-hero__right">
            <picture class="pachete-hero__picture">
              <source media="(max-width: 768px)" srcset="{{ esc_url($bg_url_mobile) }}">
              <source media="(min-width: 769px)" srcset="{{ esc_url($bg_url) }}">
              <img src="{{ esc_url($bg_url) }}" alt="{{ esc_attr($hero_alt) }}" loading="eager">
            </picture>
          </div>
        @endif
      </div>

      {{-- Bara de features de jos --}}
      <div class="pachete-hero__features">
        <div class="pachete-hero__feature">
          <span class="pachete-hero__feature-icon" aria-hidden="true">
            <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none">
              <path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z" fill="currentColor" opacity="0.18"/>
              <path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/>
              <path d="m9 12 2 2 4-4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </span>
          <span class="pachete-hero__feature-label">{{ __('Susține imunitatea', 'sage') }}</span>
        </div>
        <div class="pachete-hero__feature">
          <span class="pachete-hero__feature-icon" aria-hidden="true">
            <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none">
              <path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z" fill="currentColor" opacity="0.18"/>
              <path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/>
              <path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
            </svg>
          </span>
          <span class="pachete-hero__feature-label">{{ __('Detoxifiere naturală', 'sage') }}</span>
        </div>
        <div class="pachete-hero__feature">
          <span class="pachete-hero__feature-icon" aria-hidden="true">
            <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none">
              <circle cx="12" cy="12" r="10" fill="currentColor" opacity="0.18"/>
              <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="1.6"/>
              <circle cx="12" cy="12" r="6" stroke="currentColor" stroke-width="1.6"/>
              <circle cx="12" cy="12" r="2.2" fill="currentColor"/>
            </svg>
          </span>
          <span class="pachete-hero__feature-label">{{ __('Focus și concentrare', 'sage') }}</span>
        </div>
        <div class="pachete-hero__feature">
          <span class="pachete-hero__feature-icon" aria-hidden="true">
            <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none">
              <path d="M4 14a1 1 0 0 1-.78-1.63l9.9-10.2a.5.5 0 0 1 .86.46l-1.92 6.02A1 1 0 0 0 13 10h7a1 1 0 0 1 .78 1.63l-9.9 10.2a.5.5 0 0 1-.86-.46l1.92-6.02A1 1 0 0 0 11 14z" fill="currentColor" opacity="0.18"/>
              <path d="M4 14a1 1 0 0 1-.78-1.63l9.9-10.2a.5.5 0 0 1 .86.46l-1.92 6.02A1 1 0 0 0 13 10h7a1 1 0 0 1 .78 1.63l-9.9 10.2a.5.5 0 0 1-.86-.46l1.92-6.02A1 1 0 0 0 11 14z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/>
            </svg>
          </span>
          <span class="pachete-hero__feature-label">{{ __('Energie și vitalitate', 'sage') }}</span>
        </div>
      </div>
    </div>
    {{-- /HERO --}}

    <header class="pachete-section-header">
      <h1 class="pachete-section-title">{{ __('Pachete complete pentru un corp în echilibru', 'sage') }}</h1>
    </header>

    <ul id="pachete-products" class="products pachete-products">
      @if ($bundles_query->have_posts())
        @while ($bundles_query->have_posts())
          @php
            $bundles_query->the_post();
            wc_get_template_part('content', 'product');
          @endphp
        @endwhile
      @else
        <li class="pachete-empty">
          {{ __('Momentan nu există pachete disponibile.', 'sage') }}
        </li>
      @endif
    </ul>

    @php
      $big = 999999999;
      $paginate_links = paginate_links([
          'base'      => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
          'format'    => '?paged=%#%',
          'current'   => $paged,
          'total'     => (int) $bundles_query->max_num_pages,
          'type'      => 'array',
          'end_size'  => 1,
          'mid_size'  => 2,
          'prev_text' => '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12 H5"/><path d="M11 6 L5 12 L11 18"/></svg>',
          'next_text' => '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12 H19"/><path d="M13 6 L19 12 L13 18"/></svg>',
      ]);
    @endphp

    @if (is_array($paginate_links) && count($paginate_links) > 1)
      <nav class="pachete-pagination" aria-label="{{ __('Paginare pachete', 'sage') }}">
        @foreach ($paginate_links as $link)
          {!! $link !!}
        @endforeach
      </nav>
    @endif

    @php wp_reset_postdata(); @endphp
  </div>
@endsection
