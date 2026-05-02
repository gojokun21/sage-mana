{{--
  Category archive — articole de blog dintr-o categorie.
  Reuseaza CSS-ul din blog-bundle.css (hero, sec-head, card grid, paginare,
  cat-pill). Header personalizat cu breadcrumb, descrierea termenului si
  iconita ACF (field "icon" pe taxonomy = Category, vezi template-blog.blade.php).
--}}

@extends('layouts.app')

@section('content')
  @php
    $current_term = get_queried_object();
    $term_id      = $current_term?->term_id ?? 0;
    $term_name    = $current_term?->name ?? '';
    $term_slug    = $current_term?->slug ?? '';
    $term_desc    = $term_id
      ? trim(strip_tags(term_description($term_id, 'category')))
      : '';
    $term_count   = (int) ($current_term?->count ?? 0);

    // Iconita ACF pe term (consistent cu template-blog.blade.php).
    $icon_url = '';
    $icon_alt = $term_name;
    if ($term_id && function_exists('get_field')) {
      $icon_field = get_field('icon', 'category_' . $term_id);
      $icon_id    = \App\acf_image_id($icon_field);
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

    // Lista celorlalte categorii — pentru pill-urile de navigare.
    $excluded_cat_slugs = apply_filters('sage_blog_excluded_cluster_slugs', ['uncategorized', 'fara-categorie', 'necategorizat']);
    $other_terms = get_terms([
      'taxonomy'   => 'category',
      'hide_empty' => true,
      'orderby'    => 'name',
      'order'      => 'ASC',
      'exclude'    => [(int) get_option('default_category')],
    ]);
    if (is_wp_error($other_terms)) {
      $other_terms = [];
    } else {
      $other_terms = array_values(array_filter($other_terms, fn ($t) => ! in_array($t->slug, $excluded_cat_slugs, true)));
    }

    $blog_url   = get_post_type_archive_link('post') ?: home_url('/blog/');
    $primary_cat = static function (int $post_id): array {
      $cats = get_the_category($post_id);
      if (! empty($cats) && ! is_wp_error($cats)) {
        return [$cats[0]->slug, $cats[0]->name];
      }
      return ['', __('Articol', 'sage')];
    };

    $paged = get_query_var('paged') ? (int) get_query_var('paged') : 1;
  @endphp

  <div class="blog-page blog-page--category">

    {{-- =================== HERO =================== --}}
    <section class="blog-hero" aria-labelledby="blog-cat-title">
      <div class="container">
        <nav class="blog-hero__crumbs" aria-label="{{ __('Breadcrumb', 'sage') }}">
          <a href="{{ esc_url(home_url('/')) }}">{{ __('Acasă', 'sage') }}</a>
          <span class="blog-hero__sep" aria-hidden="true">/</span>
          <a href="{{ esc_url($blog_url) }}">{{ __('Blog', 'sage') }}</a>
          <span class="blog-hero__sep" aria-hidden="true">/</span>
          <span>{{ $term_name }}</span>
        </nav>

        <div class="blog-hero__grid">
          <div class="blog-hero__lead">
            @if ($icon_url)
              <span class="blog-hero__icon" aria-hidden="true">
                <img src="{{ esc_url($icon_url) }}" alt="{{ esc_attr($icon_alt) }}" loading="lazy" decoding="async">
              </span>
            @endif
            <h1 id="blog-cat-title" class="blog-hero__title">
              {{ __('Articole din', 'sage') }} <em>{{ $term_name }}</em>
            </h1>
            <span class="blog-hero__rule" aria-hidden="true"></span>
            @if ($term_desc !== '')
              <p class="blog-hero__intro">{{ $term_desc }}</p>
            @else
              <p class="blog-hero__intro">{{ sprintf(__('Toate articolele din categoria %s — verificate, bazate pe studii clinice peer-reviewed.', 'sage'), $term_name) }}</p>
            @endif
          </div>

          <aside class="blog-hero__stats" aria-label="{{ __('Statistici categorie', 'sage') }}">
            <div class="blog-stat">
              <div class="blog-stat__num">{{ $term_count }}</div>
              <div class="blog-stat__lbl">
                <strong>{{ _n('Articol', 'Articole', $term_count, 'sage') }}</strong>
                {{ __('Publicate în această categorie', 'sage') }}
              </div>
            </div>
            <div class="blog-stat">
              <div class="blog-stat__num">{{ count($other_terms) }}</div>
              <div class="blog-stat__lbl">
                <strong>{{ __('Alte categorii', 'sage') }}</strong>
                {{ __('Explorează și subiecte adiacente', 'sage') }}
              </div>
            </div>
          </aside>
        </div>
      </div>
    </section>

    {{-- =================== POSTS =================== --}}
    <section class="blog-recent" aria-labelledby="blog-cat-list-title">
      <div class="container">
        <header class="blog-sec-head">
          <div>
            <div class="blog-sec-head__eyebrow">
              <span class="blog-sec-head__line" aria-hidden="true"></span>
              <span>{{ __('Categorie', 'sage') }}</span>
              <span class="blog-sec-head__num">/ {{ str_pad((string) max(1, $paged), 2, '0', STR_PAD_LEFT) }}</span>
            </div>
            <h2 id="blog-cat-list-title" class="blog-sec-head__title">
              {{ __('Toate articolele din', 'sage') }} <em>{{ $term_name }}</em>
            </h2>
          </div>
          <p class="blog-sec-head__lede">
            {{ sprintf(_n('%d articol disponibil în această categorie.', '%d articole disponibile în această categorie.', $term_count, 'sage'), $term_count) }}
          </p>
        </header>

        @if (! empty($other_terms))
          <div class="blog-toolbar">
            <div class="blog-filters" role="tablist" aria-label="{{ __('Categorii blog', 'sage') }}">
              <a class="blog-filter" href="{{ esc_url($blog_url) }}" role="tab" aria-selected="false">{{ __('Toate', 'sage') }}</a>
              @foreach ($other_terms as $t)
                <a class="blog-filter {{ $t->slug === $term_slug ? 'is-active' : '' }}"
                   href="{{ esc_url(get_term_link($t)) }}"
                   role="tab"
                   aria-selected="{{ $t->slug === $term_slug ? 'true' : 'false' }}">
                  {{ $t->name }}
                </a>
              @endforeach
            </div>
          </div>
        @endif

        <div class="blog-card-grid">
          @if (have_posts())
            @while (have_posts())
              @php
                the_post();
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
          @else
            <p class="blog-empty">{{ __('Nu există articole în această categorie deocamdată.', 'sage') }}</p>
          @endif
        </div>

        @php
          $big = 999999999;
          $paginate_links = paginate_links([
            'base'      => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
            'format'    => '?paged=%#%',
            'current'   => max(1, $paged),
            'total'     => $GLOBALS['wp_query']->max_num_pages,
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
      </div>
    </section>

    {{-- =================== NEWSLETTER =================== --}}
    <section class="blog-newsletter" aria-labelledby="blog-cat-newsletter-title">
      <div class="container">
        <div class="blog-newsletter__grid">
          <div class="blog-newsletter__lead">
            <span class="blog-newsletter__eyebrow">{{ __('Buletin lunar', 'sage') }}</span>
            <h2 id="blog-cat-newsletter-title">
              {{ __('Primește lunar cele mai bune', 'sage') }} <em>{{ __('articole noi', 'sage') }}</em>
            </h2>
            <p class="blog-newsletter__sub">{{ __('Conținut educațional bazat pe studii, fără spam, dezabonare oricând.', 'sage') }}</p>
          </div>
          <div class="blog-newsletter__card">
            <div id="omnisend-embedded-v2-69f381ac6a1280abd3800e1e"></div>
          </div>
        </div>
      </div>
    </section>

  </div>
@endsection
