{{--
  Template Name: Blog Template
  Refacut conform machetei clientului — pastreaza paleta si tipografia temei
  (Rubik, var(--green), container 1470px).
--}}

@extends('layouts.app')

@section('content')
  @php
    /**
     * Iconita per slug — daca slug-ul categoriei se potriveste, se foloseste
     * iconita dedicata; altfel se foloseste fallback-ul generic (eticheta).
     * Cheile suporta variante cu/fara diacritice, ro/en. SVG inline,
     * stroke 2px, currentColor — consistent cu restul iconitelor temei.
     */
    $cluster_icons = [
      'imunitate'              => '<path d="M16 4 L26 8 V16 C26 22 21 27 16 28 C11 27 6 22 6 16 V8 Z"/><path d="M16 13 C18 14 19 16 19 18 C19 20 17.5 22 16 23 C14.5 22 13 20 13 18 C13 16 14 14 16 13 Z"/><path d="M16 13 V23"/>',
      'detoxifiere'            => '<path d="M16 4 C12 10 8 15 8 20 C8 24.4 11.6 28 16 28 C20.4 28 24 24.4 24 20 C24 15 20 10 16 4 Z"/><path d="M14 18 C15 16 17 16 18 17"/><path d="M16 22 C17 20.5 18.5 20 19.5 21"/>',
      'detox'                  => '<path d="M16 4 C12 10 8 15 8 20 C8 24.4 11.6 28 16 28 C20.4 28 24 24.4 24 20 C24 15 20 10 16 4 Z"/><path d="M14 18 C15 16 17 16 18 17"/><path d="M16 22 C17 20.5 18.5 20 19.5 21"/>',
      'frumusete'              => '<circle cx="16" cy="16" r="3"/><path d="M16 13 C16 9 14 6 11 6 C9 8 9 11 12 13"/><path d="M16 19 C16 23 14 26 11 26 C9 24 9 21 12 19"/><path d="M19 16 C23 16 26 14 26 11 C24 9 21 9 19 12"/><path d="M13 16 C9 16 6 14 6 11 C8 9 11 9 13 12"/>',
      'frumusețe'              => '<circle cx="16" cy="16" r="3"/><path d="M16 13 C16 9 14 6 11 6 C9 8 9 11 12 13"/><path d="M16 19 C16 23 14 26 11 26 C9 24 9 21 12 19"/><path d="M19 16 C23 16 26 14 26 11 C24 9 21 9 19 12"/><path d="M13 16 C9 16 6 14 6 11 C8 9 11 9 13 12"/>',
      'beauty'                 => '<circle cx="16" cy="16" r="3"/><path d="M16 13 C16 9 14 6 11 6 C9 8 9 11 12 13"/><path d="M16 19 C16 23 14 26 11 26 C9 24 9 21 12 19"/><path d="M19 16 C23 16 26 14 26 11 C24 9 21 9 19 12"/><path d="M13 16 C9 16 6 14 6 11 C8 9 11 9 13 12"/>',
      'focus'                  => '<path d="M11 26 V22 C7 21 5 17 6 13 C7 9 11 7 14 8 C15 6 18 6 19 8 C23 7 27 10 27 14 C27 17 25 20 22 21 V26"/><path d="M11 26 H22"/><path d="M16 4 V6"/><path d="M22 5 L21 7"/><path d="M10 5 L11 7"/>',
      'concentrare'            => '<path d="M11 26 V22 C7 21 5 17 6 13 C7 9 11 7 14 8 C15 6 18 6 19 8 C23 7 27 10 27 14 C27 17 25 20 22 21 V26"/><path d="M11 26 H22"/><path d="M16 4 V6"/><path d="M22 5 L21 7"/><path d="M10 5 L11 7"/>',
      'digestie'               => '<path d="M11 6 V11 C11 13 13 14 15 13 C17 12 18 13 18 15 C18 17 16 18 14 18 C12 18 11 19 11 21 C11 23 13 24 15 24 C18 24 21 22 21 19 V14"/><path d="M21 14 C21 11 19 9 17 9"/>',
      'sanatate-intestinala'   => '<path d="M11 6 V11 C11 13 13 14 15 13 C17 12 18 13 18 15 C18 17 16 18 14 18 C12 18 11 19 11 21 C11 23 13 24 15 24 C18 24 21 22 21 19 V14"/><path d="M21 14 C21 11 19 9 17 9"/>',
      'energie'                => '<path d="M18 4 L8 18 H15 L13 28 L24 13 H17 Z"/>',
      'vitalitate'             => '<path d="M18 4 L8 18 H15 L13 28 L24 13 H17 Z"/>',
      'somn'                   => '<path d="M11 6 C7 9 7 16 11 19 C8 24 14 28 19 25 C24 22 25 14 21 11 C25 7 19 3 14 6"/>',
      'sport'                  => '<circle cx="16" cy="16" r="11"/><path d="M16 5 V27"/><path d="M5 16 H27"/>',
    ];

    /**
     * Fallback iconita pentru categorii fara match — tag generic.
     */
    $cluster_icon_fallback = '<path d="M5 5 H14 L27 18 L18 27 L5 14 Z"/><circle cx="11" cy="11" r="2"/>';

    /**
     * Categoriile de exclus din clustere — by slug. Permite filtrarea
     * categoriei default WP "Uncategorized" plus orice altele indezirabile.
     */
    $excluded_cat_slugs = apply_filters('sage_blog_excluded_cluster_slugs', ['uncategorized', 'fara-categorie', 'necategorizat']);

    /**
     * Numarul maxim de clustere afisate — 0 = fara limita.
     */
    $cluster_limit = (int) apply_filters('sage_blog_cluster_limit', 0);

    // Categoriile reale din WP, sortate dupa name (ordine alfabetica stabila;
    // override-abil prin filter sage_blog_cluster_orderby).
    $cluster_terms = get_terms([
      'taxonomy'   => 'category',
      'hide_empty' => false,
      'orderby'    => apply_filters('sage_blog_cluster_orderby', 'name'),
      'order'      => apply_filters('sage_blog_cluster_order', 'ASC'),
    ]);

    $clusters = [];
    if (! is_wp_error($cluster_terms)) {
      $i = 0;
      foreach ($cluster_terms as $term) {
        if (in_array($term->slug, $excluded_cat_slugs, true)) {
          continue;
        }
        if ((int) $term->term_id === (int) get_option('default_category')) {
          continue;
        }
        $i++;
        if ($cluster_limit > 0 && $i > $cluster_limit) {
          break;
        }

        // ACF image field "icon" pe term (Field Location: Taxonomy = Category).
        // Returneaza in functie de Return Format al field-ului — normalizam
        // prin acf_image_id() ca sa scoatem un ID consistent.
        $icon_url = '';
        $icon_alt = $term->name;
        if (function_exists('get_field')) {
          $icon_field = get_field('icon', 'category_' . $term->term_id);
          $icon_id = \App\acf_image_id($icon_field);
          if ($icon_id) {
            $src = wp_get_attachment_image_src($icon_id, 'thumbnail');
            if ($src) {
              $icon_url = $src[0];
            }
            $alt = get_post_meta($icon_id, '_wp_attachment_image_alt', true);
            if ($alt) {
              $icon_alt = $alt;
            }
          } elseif (is_array($icon_field) && ! empty($icon_field['url'])) {
            $icon_url = $icon_field['url'];
            if (! empty($icon_field['alt'])) {
              $icon_alt = $icon_field['alt'];
            }
          } elseif (is_string($icon_field) && $icon_field !== '') {
            $icon_url = $icon_field;
          }
        }

        $desc = trim(strip_tags(term_description($term->term_id, 'category')));
        $clusters[] = [
          'key'      => $term->slug,
          'num'      => str_pad((string) $i, 2, '0', STR_PAD_LEFT),
          'title'    => $term->name,
          'desc'     => $desc !== '' ? $desc : sprintf(__('Articole din categoria %s.', 'sage'), $term->name),
          'icon_svg' => $cluster_icons[$term->slug] ?? $cluster_icon_fallback,
          'icon_url' => $icon_url,
          'icon_alt' => $icon_alt,
          'url'      => get_term_link($term),
          'count'    => (int) $term->count,
        ];
      }
    }

    /**
     * Helper: prima categorie a postului — folosit pentru pill-ul cardului
     * si pentru data-cat (filtru client-side). Slug-ul e sursa de adevar.
     */
    $primary_cat = static function (int $post_id): array {
      $cats = get_the_category($post_id);
      if (! empty($cats) && ! is_wp_error($cats)) {
        return [$cats[0]->slug, $cats[0]->name];
      }
      return ['', __('Articol', 'sage')];
    };

    // Pillar section apare doar daca exista studii in CPT-ul `studiu`. Lede-ul
    // face referire la "ghiduri bazate pe studii clinice", deci e inselator
    // daca nu exista nici un studiu indexat. Override prin filter.
    $studii_count = post_type_exists('studiu') ? (int) wp_count_posts('studiu')->publish : 0;
    $show_pillar = (bool) apply_filters('sage_blog_show_pillar', $studii_count > 0, $studii_count);

    // Hero / featured = cel mai nou articol.
    $featured_query = new \WP_Query([
      'post_type'      => 'post',
      'posts_per_page' => 1,
      'orderby'        => 'date',
      'order'          => 'DESC',
      'no_found_rows'  => true,
    ]);
    $featured_id = $featured_query->have_posts() ? $featured_query->posts[0]->ID : 0;

    // Excludem din recent doar ID-urile care SUNT efectiv afisate in alta
    // sectiune. Daca pillar (care contine featured) e ascuns, niciun ID
    // nu trebuie exclus — toate articolele apar in recent.
    $excluded_ids = [];
    $pillar_query = null;

    if ($show_pillar) {
      // Featured se afiseaza in interiorul sectiunii pillar — il excludem.
      if ($featured_id) {
        $excluded_ids[] = $featured_id;
      }

      // Pillar = urmatoarele 5 articole (fara featured).
      $pillar_query = new \WP_Query([
        'post_type'      => 'post',
        'posts_per_page' => 5,
        'orderby'        => 'date',
        'order'          => 'DESC',
        'post__not_in'   => $excluded_ids,
        'no_found_rows'  => true,
      ]);
      foreach ($pillar_query->posts as $p) {
        $excluded_ids[] = $p->ID;
      }
    }

    // Recent = paginat, fara offset manual. Folosim `post__not_in` cu ID-urile
    // deja afisate (featured + eventual pillar) — asa pagination-ul WP
    // calculeaza max_num_pages corect, iar daca pillar e ascuns, posturile
    // care altfel ar fi sarit pe offset apar in recent.
    $paged          = get_query_var('paged') ? (int) get_query_var('paged') : 1;
    $posts_per_page = 9;

    $recent_query = new \WP_Query([
      'post_type'      => 'post',
      'posts_per_page' => $posts_per_page,
      'orderby'        => 'date',
      'order'          => 'DESC',
      'post__not_in'   => $excluded_ids,
      'paged'          => $paged,
    ]);

    // Posts published in the last 30 days — pentru sub-titlul sectiunii.
    $recent_count_query = new \WP_Query([
      'post_type'      => 'post',
      'posts_per_page' => 1,
      'date_query'     => [['after' => '30 days ago']],
      'fields'         => 'ids',
    ]);
    $recent_30d = (int) $recent_count_query->found_posts;
    wp_reset_postdata();

    // Total posts publicate (pentru hero stat).
    $total_posts = (int) wp_count_posts('post')->publish;
  @endphp

  <div class="blog-page">

    {{-- =================== HERO =================== --}}
    <section class="blog-hero" aria-labelledby="blog-hero-title">
      <div class="container">
        <nav class="blog-hero__crumbs" aria-label="{{ __('Breadcrumb', 'sage') }}">
          <a href="{{ home_url('/') }}">{{ __('Acasă', 'sage') }}</a>
          <span class="blog-hero__sep" aria-hidden="true">/</span>
          <span>{{ __('Blog', 'sage') }}</span>
        </nav>

        <div class="blog-hero__grid">
          <div class="blog-hero__lead">
            <h1 id="blog-hero-title" class="blog-hero__title">
              {{ __('Blog', 'sage') }} <em>{{ __('Mâna Naturii', 'sage') }}</em>
            </h1>
            <span class="blog-hero__rule" aria-hidden="true"></span>
            <p class="blog-hero__sub">{{ __('Ghiduri de sănătate bazate pe studii clinice peer-reviewed', 'sage') }}</p>
            <p class="blog-hero__intro">
              {{ __('Articolele noastre acoperă subiecte esențiale despre suplimente naturale, detoxifiere, imunitate, frumusețe, focus, digestie și energie. Toate informațiile sunt bazate pe peste 50 de studii clinice publicate în reviste științifice peer-reviewed (PubMed, PMC, NIH), pentru a oferi conținut verificat și credibil.', 'sage') }}
            </p>
          </div>

          <aside class="blog-hero__stats" aria-label="{{ __('Statistici editoriale', 'sage') }}">
            <div class="blog-stat">
              <div class="blog-stat__num">50+</div>
              <div class="blog-stat__lbl">
                <strong>{{ __('Studii citate', 'sage') }}</strong>
                {{ __('Toate verificabile public, din PubMed, PMC și NIH', 'sage') }}
              </div>
            </div>
            <div class="blog-stat">
              <div class="blog-stat__num">{{ $total_posts ?: 42 }}</div>
              <div class="blog-stat__lbl">
                <strong>{{ __('Ghiduri publicate', 'sage') }}</strong>
                {{ __('Scrise împreună cu nutriționiști licențiați', 'sage') }}
              </div>
            </div>
            @if (count($clusters) > 0)
              <div class="blog-stat">
                <div class="blog-stat__num">{{ count($clusters) }}</div>
                <div class="blog-stat__lbl">
                  <strong>{{ __('Clustere tematice', 'sage') }}</strong>
                  {{ __('Categorii esențiale ale stării de bine', 'sage') }}
                </div>
              </div>
            @endif
          </aside>
        </div>
      </div>
    </section>

    {{-- =================== TRUST STRIP =================== --}}
    <div class="blog-trust-strip" role="region" aria-label="{{ __('Garanții editoriale', 'sage') }}">
      <div class="container">
        <ul class="blog-trust-strip__list">
          <li>
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 3 L20 6 V12 C20 17 16 21 12 22 C8 21 4 17 4 12 V6 Z"/><path d="M8 12 L11 15 L16 9"/></svg>
            <span>{{ __('Surse exclusiv peer-reviewed', 'sage') }}</span>
          </li>
          <li>
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M12 7 V12 L15 14"/></svg>
            <span>{{ __('Articole revizuite trimestrial', 'sage') }}</span>
          </li>
          <li>
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M6 4 H14 L18 8 V20 H6 Z"/><path d="M14 4 V8 H18"/><path d="M9 12 H15"/><path d="M9 15 H15"/></svg>
            <span>{{ __('Linkuri directe la fiecare studiu', 'sage') }}</span>
          </li>
          <li>
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="8" r="4"/><path d="M4 21 C4 16 8 14 12 14 C16 14 20 16 20 21"/></svg>
            <span>{{ __('Editat de nutriționiști licențiați', 'sage') }}</span>
          </li>
        </ul>
      </div>
    </div>

    {{-- =================== CLUSTERS =================== --}}
    @if (! empty($clusters))
    <section class="blog-clusters" aria-labelledby="blog-clusters-title">
      <div class="container">
        <header class="blog-sec-head">
          <div>
            <div class="blog-sec-head__eyebrow">
              <span class="blog-sec-head__line" aria-hidden="true"></span>
              <span>{{ __('Clustere tematice', 'sage') }}</span>
              <span class="blog-sec-head__num">/ 01</span>
            </div>
            <h2 id="blog-clusters-title" class="blog-sec-head__title">
              {{ __('Începe cu', 'sage') }} <em>{{ __('subiectul', 'sage') }}</em> {{ __('care îți este aproape', 'sage') }}
            </h2>
          </div>
          <p class="blog-sec-head__lede">{{ sprintf(_n('Un domeniu esențial al stării de bine, cu articole verificate și grupate după nevoia ta concretă.', '%d domenii esențiale ale stării de bine, fiecare cu articole verificate și grupate după nevoia ta concretă.', count($clusters), 'sage'), count($clusters)) }}</p>
        </header>

        <div class="blog-cluster-grid">
          @foreach ($clusters as $c)
            <a href="{{ esc_url(is_string($c['url']) ? $c['url'] : '') }}" class="blog-cluster-card" data-cat="{{ esc_attr($c['key']) }}">
              <div class="blog-cluster-card__head">
                <span class="blog-cluster-card__icon" aria-hidden="true">
                  @if (! empty($c['icon_url']))
                    <img src="{{ esc_url($c['icon_url']) }}" alt="{{ esc_attr($c['icon_alt']) }}" loading="lazy" decoding="async">
                  @else
                    <svg width="30" height="30" viewBox="0 0 32 32" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      {!! $c['icon_svg'] !!}
                    </svg>
                  @endif
                </span>
                <span class="blog-cluster-card__num">/ {{ $c['num'] }}</span>
              </div>
              <h3>{{ $c['title'] }}</h3>
              <p>{{ $c['desc'] }}</p>
              <div class="blog-cluster-card__foot">
                <span class="blog-cluster-card__cta">
                  {{ __('Vezi articolele', 'sage') }}
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12 H19"/><path d="M13 6 L19 12 L13 18"/></svg>
                </span>
                @if ($c['count'] > 0)
                  <span class="blog-cluster-card__count">{{ sprintf(_n('%d articol', '%d articole', $c['count'], 'sage'), $c['count']) }}</span>
                @endif
              </div>
            </a>
          @endforeach
        </div>
      </div>
    </section>
    @endif

    {{-- =================== PILLAR =================== --}}
    @if ($show_pillar)
    <section class="blog-pillar" aria-labelledby="blog-pillar-title">
      <div class="container">
        <header class="blog-sec-head">
          <div>
            <div class="blog-sec-head__eyebrow">
              <span class="blog-sec-head__line" aria-hidden="true"></span>
              <span>{{ __('Citește întâi', 'sage') }}</span>
              <span class="blog-sec-head__num">/ 02</span>
            </div>
            <h2 id="blog-pillar-title" class="blog-sec-head__title">
              {{ __('Articolele', 'sage') }} <em>pillar</em>
            </h2>
          </div>
          <p class="blog-sec-head__lede">{{ sprintf(_n('Ghiduri complete bazate pe %d studiu clinic. Punct de plecare pentru orice subiect.', 'Ghiduri complete bazate pe %d studii clinice. Punct de plecare pentru orice subiect.', $studii_count, 'sage'), $studii_count) }}</p>
        </header>

        @if ($featured_query->have_posts())
          @while ($featured_query->have_posts())
            @php
              $featured_query->the_post();
              [$cat_slug, $cat_name] = $primary_cat(get_the_ID());
              $thumb = get_the_post_thumbnail_url(get_the_ID(), 'large');
              $author = get_the_author();
              $initials = strtoupper(mb_substr($author, 0, 2));
              $reading_time = max(2, (int) ceil(str_word_count(strip_tags(get_the_content())) / 200));
            @endphp
            <article class="blog-featured">
              <a href="{{ esc_url(get_permalink()) }}" class="blog-featured__media">
                @if ($thumb)
                  <img src="{{ esc_url($thumb) }}" alt="{{ esc_attr(get_the_title()) }}" loading="lazy">
                @else
                  <span class="blog-featured__placeholder" aria-hidden="true"></span>
                @endif
                <span class="blog-featured__stamp">{{ __("Editor's pick", 'sage') }}</span>
              </a>
              <div class="blog-featured__body">
                @if ($cat_slug)
                  <span class="blog-cat-pill">{{ $cat_name }}</span>
                @endif
                <h3 class="blog-featured__title">
                  <a href="{{ esc_url(get_permalink()) }}">{{ get_the_title() }}</a>
                </h3>
                <p class="blog-featured__excerpt">{{ wp_trim_words(get_the_excerpt(), 38, '...') }}</p>
                <div class="blog-featured__meta">
                  <span class="blog-featured__author">
                    <span class="blog-featured__avatar" aria-hidden="true">{{ $initials }}</span>
                    <span>{{ $author }}</span>
                  </span>
                  <span class="blog-meta-dot" aria-hidden="true">·</span>
                  <time datetime="{{ get_the_date('c') }}">{{ get_the_date() }}</time>
                  <span class="blog-meta-dot" aria-hidden="true">·</span>
                  <span>{{ sprintf(__('%d min citit', 'sage'), $reading_time) }}</span>
                </div>
                <a href="{{ esc_url(get_permalink()) }}" class="blog-btn-primary">
                  {{ __('Citește ghidul complet', 'sage') }}
                  <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12 H19"/><path d="M13 6 L19 12 L13 18"/></svg>
                </a>
              </div>
            </article>
          @endwhile
          @php wp_reset_postdata(); @endphp
        @endif

        @if ($pillar_query->have_posts())
          <div class="blog-card-grid">
            @while ($pillar_query->have_posts())
              @php
                $pillar_query->the_post();
                [$cat_slug, $cat_name] = $primary_cat(get_the_ID());
                $thumb = get_the_post_thumbnail_url(get_the_ID(), 'medium_large');
                $reading_time = max(2, (int) ceil(str_word_count(strip_tags(get_the_content())) / 200));
              @endphp
              <article class="blog-card" data-cat="{{ esc_attr($cat_slug) }}">
                <a href="{{ esc_url(get_permalink()) }}" class="blog-card__media">
                  @if ($thumb)
                    <img src="{{ esc_url($thumb) }}" alt="{{ esc_attr(get_the_title()) }}" loading="lazy">
                  @else
                    <span class="blog-card__placeholder" aria-hidden="true"></span>
                  @endif
                  <span class="blog-card__reading">{{ sprintf(__('%d min', 'sage'), $reading_time) }}</span>
                </a>
                <div class="blog-card__body">
                  @if ($cat_slug)
                    <span class="blog-cat-pill">{{ $cat_name }}</span>
                  @endif
                  <h4 class="blog-card__title">
                    <a href="{{ esc_url(get_permalink()) }}">{{ get_the_title() }}</a>
                  </h4>
                  <p class="blog-card__excerpt">{{ wp_trim_words(get_the_excerpt(), 22, '...') }}</p>
                  <div class="blog-card__meta">
                    <time datetime="{{ get_the_date('c') }}">{{ get_the_date() }}</time>
                    <span class="blog-meta-dot" aria-hidden="true">·</span>
                    <span>{{ sprintf(__('%d min citit', 'sage'), $reading_time) }}</span>
                  </div>
                </div>
              </article>
            @endwhile
          </div>
          @php wp_reset_postdata(); @endphp
        @endif
      </div>
    </section>
    @endif

    {{-- =================== RECENT =================== --}}
    <section class="blog-recent" aria-labelledby="blog-recent-title">
      <div class="container">
        <header class="blog-sec-head">
          <div>
            <div class="blog-sec-head__eyebrow">
              <span class="blog-sec-head__line" aria-hidden="true"></span>
              <span>{{ __('Publicate recent', 'sage') }}</span>
              <span class="blog-sec-head__num">/ 03</span>
            </div>
            <h2 id="blog-recent-title" class="blog-sec-head__title">
              {{ __('Articole', 'sage') }} <em>{{ __('recente', 'sage') }}</em>
            </h2>
          </div>
          <p class="blog-sec-head__lede">
            {{ __('Filtrează după cluster sau caută un subiect anume.', 'sage') }}
            @if ($recent_30d > 0)
              {{ sprintf(_n('%d articol nou în ultima lună.', '%d articole noi în ultima lună.', $recent_30d, 'sage'), $recent_30d) }}
            @endif
          </p>
        </header>

        <div class="blog-toolbar" role="search">
          <div class="blog-toolbar__row">
            <div class="blog-search">
              <label for="blog-search-input" class="sr-only">{{ __('Caută articole', 'sage') }}</label>
              <input id="blog-search-input" type="search" placeholder="{{ esc_attr__('Caută articole, suplimente, simptome...', 'sage') }}" data-blog-search />
              <svg class="blog-search__icon" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="M20 20 L16.5 16.5"/></svg>
            </div>
          </div>
          <div class="blog-filters" data-blog-filters role="tablist" aria-label="{{ __('Filtre cluster', 'sage') }}">
            <button class="blog-filter is-active" data-filter="all" role="tab" aria-selected="true">{{ __('Toate', 'sage') }}</button>
            @foreach ($clusters as $c)
              <button class="blog-filter" data-filter="{{ esc_attr($c['key']) }}" role="tab" aria-selected="false">{{ $c['title'] }}</button>
            @endforeach
          </div>
        </div>

        <div class="blog-card-grid" data-blog-grid>
          @if ($recent_query->have_posts())
            @while ($recent_query->have_posts())
              @php
                $recent_query->the_post();
                [$cat_slug, $cat_name] = $primary_cat(get_the_ID());
                $thumb = get_the_post_thumbnail_url(get_the_ID(), 'medium_large');
                $reading_time = max(2, (int) ceil(str_word_count(strip_tags(get_the_content())) / 200));
              @endphp
              <article class="blog-card" data-cat="{{ esc_attr($cat_slug) }}" data-search="{{ esc_attr(strtolower(get_the_title() . ' ' . wp_strip_all_tags(get_the_excerpt()))) }}">
                <a href="{{ esc_url(get_permalink()) }}" class="blog-card__media">
                  @if ($thumb)
                    <img src="{{ esc_url($thumb) }}" alt="{{ esc_attr(get_the_title()) }}" loading="lazy">
                  @else
                    <span class="blog-card__placeholder" aria-hidden="true"></span>
                  @endif
                  <span class="blog-card__reading">{{ sprintf(__('%d min', 'sage'), $reading_time) }}</span>
                </a>
                <div class="blog-card__body">
                  @if ($cat_slug)
                    <span class="blog-cat-pill">{{ $cat_name }}</span>
                  @endif
                  <h4 class="blog-card__title">
                    <a href="{{ esc_url(get_permalink()) }}">{{ get_the_title() }}</a>
                  </h4>
                  <p class="blog-card__excerpt">{{ wp_trim_words(get_the_excerpt(), 22, '...') }}</p>
                  <div class="blog-card__meta">
                    <time datetime="{{ get_the_date('c') }}">{{ get_the_date() }}</time>
                    <span class="blog-meta-dot" aria-hidden="true">·</span>
                    <span>{{ sprintf(__('%d min citit', 'sage'), $reading_time) }}</span>
                  </div>
                </div>
              </article>
            @endwhile
          @else
            <p class="blog-empty">{{ __('Nu există articole disponibile.', 'sage') }}</p>
          @endif
        </div>

        <p class="blog-empty" data-blog-empty hidden>{{ __('Niciun articol pentru acest filtru.', 'sage') }}</p>

        @php
          $big = 999999999;
          $paginate_links = paginate_links([
            'base'      => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
            'format'    => '?paged=%#%',
            'current'   => max(1, $paged),
            'total'     => $recent_query->max_num_pages,
            'type'      => 'array',
            'end_size'  => 1,
            'mid_size'  => 2,
            'prev_text' => '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12 H5"/><path d="M11 6 L5 12 L11 18"/></svg>',
            'next_text' => '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12 H19"/><path d="M13 6 L19 12 L13 18"/></svg>',
          ]);
        @endphp

        @if (is_array($paginate_links) && count($paginate_links) > 1)
          <nav class="blog-page-nav" aria-label="{{ __('Paginare articole', 'sage') }}">
            @foreach ($paginate_links as $link)
              {!! $link !!}
            @endforeach
          </nav>
        @endif

        @php wp_reset_postdata(); @endphp
      </div>
    </section>

    {{-- =================== NEWSLETTER =================== --}}
    <section class="blog-newsletter" aria-labelledby="blog-newsletter-title">
      <div class="container">
        <div class="blog-newsletter__grid">
          <div class="blog-newsletter__lead">
            <span class="blog-newsletter__eyebrow">{{ __('Buletin lunar', 'sage') }}</span>
            <h2 id="blog-newsletter-title">
              {{ __('Primește lunar cele mai bune', 'sage') }} <em>{{ __('articole noi', 'sage') }}</em>
            </h2>
            <p class="blog-newsletter__sub">{{ __('Conținut educațional bazat pe studii, fără spam, dezabonare oricând. Ne citesc deja peste 12.000 de oameni interesați de sănătate naturală.', 'sage') }}</p>
          </div>
          <div class="blog-newsletter__card">
            <div id="omnisend-embedded-v2-69f381ac6a1280abd3800e1e"></div>
          </div>
        </div>
      </div>
    </section>

    {{-- =================== TRUST / STUDIES =================== --}}
    <section class="blog-trust" aria-labelledby="blog-trust-title">
      <div class="container">
        <div class="blog-trust__grid">
          <div class="blog-trust__lead">
            <svg class="blog-trust__shield" viewBox="0 0 80 80" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
              <path d="M40 8 L62 16 V36 C62 50 52 62 40 68 C28 62 18 50 18 36 V16 Z"/>
              <path d="M30 38 L37 46 L52 30"/>
            </svg>
            <h2 id="blog-trust-title">
              {{ __('Conținut bazat pe', 'sage') }} <em>{{ __('studii clinice reale', 'sage') }}</em>
            </h2>
            <p>{{ __('Toate articolele noastre sunt bazate pe studii clinice peer-reviewed, verificabile public. Citim și citează exclusiv surse din PubMed, PMC, NIH, Cochrane Library și alte baze de date științifice de încredere.', 'sage') }}</p>
            <a href="{{ esc_url(get_post_type_archive_link('studiu') ?: home_url('/studii/')) }}" class="blog-trust__link">
              {{ __('Vezi toate studiile noastre', 'sage') }}
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12 H19"/><path d="M13 6 L19 12 L13 18"/></svg>
            </a>
          </div>
          <div class="blog-trust__stats">
            <div class="blog-trust__stat">
              <svg viewBox="0 0 32 32" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M9 4 H19 L24 9 V27 C24 27.6 23.6 28 23 28 H9 C8.4 28 8 27.6 8 27 V5 C8 4.4 8.4 4 9 4 Z"/><path d="M19 4 V9 H24"/><path d="M12 14 H20"/><path d="M12 18 H20"/><path d="M12 22 H17"/></svg>
              <div class="blog-trust__num"><em>50+</em> {{ __('studii citate', 'sage') }}</div>
              <p>{{ __('Fiecare ghid trimite la sursa originală.', 'sage') }}</p>
            </div>
            <div class="blog-trust__stat">
              <svg viewBox="0 0 32 32" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="6" y="6" width="5" height="22" rx="1"/><rect x="13" y="4" width="5" height="24" rx="1"/><rect x="20" y="8" width="5" height="20" rx="1"/></svg>
              <div class="blog-trust__num">PubMed, PMC, NIH</div>
              <p>{{ __('Surse de top din cercetarea medicală.', 'sage') }}</p>
            </div>
            <div class="blog-trust__stat blog-trust__stat--wide">
              <svg viewBox="0 0 32 32" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M16 4 L26 8 V16 C26 22 21 27 16 28 C11 27 6 22 6 16 V8 Z"/><path d="M11 16 L15 20 L22 12"/></svg>
              <div class="blog-trust__num">{{ __('Verificabile public, ', 'sage') }}<em>100%</em></div>
              <p>{{ __('Linkuri directe la fiecare studiu citat. Editat de nutriționiști licențiați și revizuit trimestrial.', 'sage') }}</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    {{-- =================== FAQ =================== --}}
    <section class="blog-faq" aria-labelledby="blog-faq-title">
      <div class="container">
        <header class="blog-sec-head">
          <div>
            <div class="blog-sec-head__eyebrow">
              <span class="blog-sec-head__line" aria-hidden="true"></span>
              <span>{{ __('Întrebări frecvente', 'sage') }}</span>
              <span class="blog-sec-head__num">/ 04</span>
            </div>
            <h2 id="blog-faq-title" class="blog-sec-head__title">
              {{ __('Răspunsuri', 'sage') }} <em>{{ __('scurte', 'sage') }}</em>
            </h2>
          </div>
          <p class="blog-sec-head__lede">{{ __('Cele mai frecvente întrebări despre cum lucrăm, ce surse folosim și cum verificăm informațiile.', 'sage') }}</p>
        </header>

        @php
          $faqs = [
            [
              'q' => __('Pe ce surse științifice se bazează articolele Mâna Naturii?', 'sage'),
              'a' => __('Toate articolele citează exclusiv studii peer-reviewed din PubMed, PMC, NIH și Cochrane Library. Fiecare ghid trimite direct la sursa originală pentru verificare publică.', 'sage'),
            ],
            [
              'q' => __('Cât de des publicați articole noi?', 'sage'),
              'a' => __('Publicăm articole noi săptămânal. Trimitem un rezumat lunar prin newsletter, fără spam, cu cele mai relevante ghiduri și un studiu interesant comentat.', 'sage'),
            ],
            [
              'q' => __('Articolele înlocuiesc sfatul medical?', 'sage'),
              'a' => __('Nu. Conținutul are scop educațional. Consultă întotdeauna un medic înainte de a începe orice cură de suplimentare, mai ales dacă urmezi un tratament medicamentos sau ai o afecțiune cronică.', 'sage'),
            ],
            [
              'q' => __('Cine scrie articolele și cum sunt verificate?', 'sage'),
              'a' => __('Articolele sunt scrise de echipa editorială în colaborare cu nutriționiști licențiați și revizuite trimestrial.', 'sage'),
            ],
            [
              'q' => __('Cum decideți ce subiecte abordați?', 'sage'),
              'a' => __('Pornim de la întrebările reale ale comunității și de la subiectele cu cea mai solidă bază de cercetare clinică. Prioritizăm temele unde există meta-analize recente sau studii randomizate cu rezultate consistente.', 'sage'),
            ],
          ];
        @endphp

        <div class="blog-faq__list" data-blog-faq>
          @foreach ($faqs as $i => $faq)
            <details class="blog-faq__item" {{ $i === 0 ? 'open' : '' }}>
              <summary class="blog-faq__q">
                <span>{{ $faq['q'] }}</span>
                <span class="blog-faq__toggle" aria-hidden="true">
                  <svg width="14" height="14" viewBox="0 0 14 14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M2 7 H12"/><path d="M7 2 V12"/></svg>
                </span>
              </summary>
              <div class="blog-faq__a">
                <p>{{ $faq['a'] }}</p>
              </div>
            </details>
          @endforeach
        </div>
      </div>
    </section>

  </div>
@endsection
