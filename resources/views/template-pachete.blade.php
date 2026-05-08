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

    {{-- ============================================================ --}}
    {{-- 4 PAȘI — Cum funcționează                                     --}}
    {{-- ============================================================ --}}
    @php
      // Pași — text hardcodat, imaginile pot veni din ACF repeater `pasi_steps`
      // (sub-field: `image`) pe pagină. Daca lipsesc, cardurile arată ok fără imagine.
      $pasi_acf = function_exists('get_field') ? get_field('pasi_steps', $page_id) : null;
      $pasi_imgs = [];
      if (is_array($pasi_acf)) {
          foreach ($pasi_acf as $row) {
              $img = $row['image'] ?? null;
              $pasi_imgs[] = is_array($img) ? ($img['url'] ?? '') : (is_string($img) ? $img : '');
          }
      }

      $pasi_steps = [
          [
              'title' => __('Alegi pachetul', 'sage'),
              'desc'  => __('Alege pachetul potrivit pentru tine: Detox, Imunitate sau Energie.', 'sage'),
              'icon'  => 'bag',
          ],
          [
              'title' => __('Urmezi cura zilnic', 'sage'),
              'desc'  => __('Urmezi rutina zilnică recomandată, simplu de integrat în stilul tău de viață. Capsule și/sau shots — ușor de luat, ușor de menținut.', 'sage'),
              'icon'  => 'calendar',
          ],
          [
              'title' => __('Corpul se reglează', 'sage'),
              'desc'  => __('Ingredientele naturale lucrează în sinergie pentru a susține digestia, imunitatea, energia și claritatea mintală.', 'sage'),
              'icon'  => 'leaf',
          ],
          [
              'title' => __('Vezi rezultatele', 'sage'),
              'desc_html' => sprintf(
                  /* translators: %s: interval în zile (ex. "7–30 de zile") */
                  __('Rezultatele apar treptat și natural, în %s, cu consecvență și grijă pentru tine.', 'sage'),
                  '<strong>' . esc_html__('7–30 de zile', 'sage') . '</strong>'
              ),
              'icon'  => 'chart',
          ],
      ];

      $pasi_trust = [
          ['icon' => 'shield', 'label' => __('Ingrediente premium, formule clinic dovedite', 'sage')],
          ['icon' => 'bolt',   'label' => __('100% ingrediente naturale, fără aditivi inutili', 'sage')],
          ['icon' => 'check',  'label' => __('Suplimente sigure, testate în laborator', 'sage')],
          ['icon' => 'heart',  'label' => __('Peste 5.000 de clienți mulțumiți', 'sage')],
      ];
    @endphp

    <section class="pachete-pasi" aria-labelledby="pachete-pasi-title" style="display:none;">
      <div class="pachete-pasi__head">
        <span class="pachete-pasi__eyebrow">
          <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10z"/>
            <path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12"/>
          </svg>
          {{ __('CUM FUNCȚIONEAZĂ', 'sage') }}
        </span>
        <h2 id="pachete-pasi-title" class="pachete-pasi__title">{{ __('4 pași simpli către echilibru', 'sage') }}</h2>
        <p class="pachete-pasi__lead">{{ __('Rutina noastră este gândită să fie ușor de urmat și să aducă rezultate reale, în mod natural.', 'sage') }}</p>
      </div>

      <ol class="pachete-pasi__grid">
        @foreach ($pasi_steps as $i => $step)
          @php $img_url = $pasi_imgs[$i] ?? ''; @endphp
          <li class="pachete-pasi__card">
            <span class="pachete-pasi__num" aria-hidden="true">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
            <div class="pachete-pasi__media">
              @if ($img_url)
                <img src="{{ esc_url($img_url) }}" alt="" loading="lazy">
              @endif
            </div>

            <span class="pachete-pasi__icon" aria-hidden="true">
              @switch($step['icon'])
                @case('bag')
                  <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/>
                    <path d="M3 6h18"/>
                    <path d="M16 10a4 4 0 0 1-8 0"/>
                  </svg>
                  @break
                @case('calendar')
                  <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                    <line x1="16" y1="2" x2="16" y2="6"/>
                    <line x1="8" y1="2" x2="8" y2="6"/>
                    <line x1="3" y1="10" x2="21" y2="10"/>
                  </svg>
                  @break
                @case('leaf')
                  <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10z"/>
                    <path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12"/>
                  </svg>
                  @break
                @case('chart')
                  <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 3v18h18"/>
                    <path d="m7 14 4-4 4 4 5-6"/>
                  </svg>
                  @break
              @endswitch
            </span>

            <h3 class="pachete-pasi__step-title">{{ $step['title'] }}</h3>
            <p class="pachete-pasi__step-desc">
              @if (!empty($step['desc_html']))
                {!! $step['desc_html'] !!}
              @else
                {{ $step['desc'] }}
              @endif
            </p>

            @if ($i < count($pasi_steps) - 1)
              <span class="pachete-pasi__arrow" aria-hidden="true">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                  <polyline points="9 18 15 12 9 6"/>
                </svg>
              </span>
            @endif
          </li>
        @endforeach
      </ol>

      <ul class="pachete-pasi__trust">
        @foreach ($pasi_trust as $t)
          <li class="pachete-pasi__trust-item">
            <span class="pachete-pasi__trust-icon" aria-hidden="true">
              @switch($t['icon'])
                @case('shield')
                  <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/>
                  </svg>
                  @break
                @case('bolt')
                  <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 14a1 1 0 0 1-.78-1.63l9.9-10.2a.5.5 0 0 1 .86.46l-1.92 6.02A1 1 0 0 0 13 10h7a1 1 0 0 1 .78 1.63l-9.9 10.2a.5.5 0 0 1-.86-.46l1.92-6.02A1 1 0 0 0 11 14z"/>
                  </svg>
                  @break
                @case('check')
                  <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="20 6 9 17 4 12"/>
                  </svg>
                  @break
                @case('heart')
                  <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.29 1.51 4.04 3 5.5l7 7Z"/>
                  </svg>
                  @break
              @endswitch
            </span>
            <span class="pachete-pasi__trust-label">{{ $t['label'] }}</span>
          </li>
        @endforeach
      </ul>
    </section>
    {{-- /4 PAȘI --}}

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
