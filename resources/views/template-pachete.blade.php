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

          <h1 class="pachete-hero__title">{{ $hero_title }}</h1>

          <p class="pachete-hero__desc">{{ $hero_subtitle }}</p>

          <ul class="pachete-hero__benefits">
            @foreach ($hero_benefits as $benefit)
              <li class="pachete-hero__benefit">
                <span class="pachete-hero__benefit-icon" aria-hidden="true">
                  @if ($benefit['icon'] === 'truck')
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
                  @elseif ($benefit['icon'] === 'calendar')
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                  @else
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10z"/><path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12"/></svg>
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
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
          </span>
          <span class="pachete-hero__feature-label">{{ __('Susține imunitatea', 'sage') }}</span>
        </div>
        <div class="pachete-hero__feature">
          <span class="pachete-hero__feature-icon" aria-hidden="true">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10z"/><path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12"/></svg>
          </span>
          <span class="pachete-hero__feature-label">{{ __('Detoxifiere naturală', 'sage') }}</span>
        </div>
        <div class="pachete-hero__feature">
          <span class="pachete-hero__feature-icon" aria-hidden="true">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="6"/><circle cx="12" cy="12" r="2"/></svg>
          </span>
          <span class="pachete-hero__feature-label">{{ __('Focus și concentrare', 'sage') }}</span>
        </div>
        <div class="pachete-hero__feature">
          <span class="pachete-hero__feature-icon" aria-hidden="true">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>
          </span>
          <span class="pachete-hero__feature-label">{{ __('Energie și vitalitate', 'sage') }}</span>
        </div>
      </div>
    </div>
    {{-- /HERO --}}

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
