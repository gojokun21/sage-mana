{{--
  Lista de studii — partial folosit atat de archive-studiu.blade.php
  cat si de taxonomy-categorie_studiu.blade.php. The Loop e furnizat
  de query-ul principal (have_posts/the_post).

  Variabile asteptate:
    $hero_eyebrow  — string, label de eyebrow ("Bază de date" / nume term)
    $hero_title_em — string, partea italica din titlu ("studiile" / nume term)
    $hero_lede     — string, descriere scurta
    $active_slug   — slug-ul taxonomiei active ('all' pe arhiva CPT)
--}}

@php
  $hero_eyebrow  = $hero_eyebrow  ?? __('Bază de date', 'sage');
  $hero_title_em = $hero_title_em ?? __('studiile', 'sage');
  $hero_lede     = $hero_lede     ?? __('Filtrează după domeniu sau caută un subiect specific. Click pe orice card pentru sumar și sursa originală.', 'sage');
  $active_slug   = $active_slug   ?? 'all';

  $total_studii = (int) wp_count_posts('studiu')->publish;

  // Termenii din taxonomia proprie a studiilor.
  $study_terms = get_terms([
    'taxonomy'   => 'categorie_studiu',
    'hide_empty' => true,
    'orderby'    => 'name',
    'order'      => 'ASC',
  ]);
  $study_terms = is_wp_error($study_terms) ? [] : $study_terms;

  $paged = get_query_var('paged') ? (int) get_query_var('paged') : 1;
@endphp

<div class="blog-page">

  {{-- =================== HERO =================== --}}
  <section class="blog-hero" aria-labelledby="studii-hero-title">
    <div class="container">
      <nav class="blog-hero__crumbs" aria-label="{{ __('Breadcrumb', 'sage') }}">
        <a href="{{ home_url('/') }}">{{ __('Acasă', 'sage') }}</a>
        <span class="blog-hero__sep" aria-hidden="true">/</span>
        <a href="{{ esc_url(get_post_type_archive_link('studiu')) }}">{{ __('Studii', 'sage') }}</a>
        @if (! empty($current_term))
          <span class="blog-hero__sep" aria-hidden="true">/</span>
          <span>{{ $current_term->name }}</span>
        @endif
      </nav>

      <div class="blog-hero__grid">
        <div class="blog-hero__lead">
          <h1 id="studii-hero-title" class="blog-hero__title">
            {{ __('Studii', 'sage') }} <em>{{ $hero_title_em }}</em>
          </h1>
          <span class="blog-hero__rule" aria-hidden="true"></span>
          <p class="blog-hero__sub">{{ __('Sursele științifice care stau la baza articolelor noastre', 'sage') }}</p>
          <p class="blog-hero__intro">{{ $hero_lede }}</p>
        </div>

        <aside class="blog-hero__stats" aria-label="{{ __('Statistici', 'sage') }}">
          <div class="blog-stat">
            <div class="blog-stat__num">{{ $total_studii ?: 0 }}</div>
            <div class="blog-stat__lbl">
              <strong>{{ __('Studii indexate', 'sage') }}</strong>
              {{ __('Citate în articolele de pe blog', 'sage') }}
            </div>
          </div>
          @if (count($study_terms) > 0)
            <div class="blog-stat">
              <div class="blog-stat__num">{{ count($study_terms) }}</div>
              <div class="blog-stat__lbl">
                <strong>{{ __('Categorii', 'sage') }}</strong>
                {{ __('De la imunitate la performanță cognitivă', 'sage') }}
              </div>
            </div>
          @endif
          <div class="blog-stat">
            <div class="blog-stat__num">100%</div>
            <div class="blog-stat__lbl">
              <strong>{{ __('Verificabile public', 'sage') }}</strong>
              {{ __('PubMed, PMC, NIH, Cochrane', 'sage') }}
            </div>
          </div>
        </aside>
      </div>
    </div>
  </section>

  {{-- =================== TRUST STRIP =================== --}}
  <div class="blog-trust-strip" role="region" aria-label="{{ __('Garanții', 'sage') }}">
    <div class="container">
      <ul class="blog-trust-strip__list">
        <li>
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 3 L20 6 V12 C20 17 16 21 12 22 C8 21 4 17 4 12 V6 Z"/><path d="M8 12 L11 15 L16 9"/></svg>
          <span>{{ __('Surse exclusiv peer-reviewed', 'sage') }}</span>
        </li>
        <li>
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M6 4 H14 L18 8 V20 H6 Z"/><path d="M14 4 V8 H18"/><path d="M9 12 H15"/><path d="M9 15 H15"/></svg>
          <span>{{ __('Linkuri directe la fiecare studiu', 'sage') }}</span>
        </li>
        <li>
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M12 7 V12 L15 14"/></svg>
          <span>{{ __('Actualizate trimestrial', 'sage') }}</span>
        </li>
      </ul>
    </div>
  </div>

  {{-- =================== STUDII GRID =================== --}}
  <section class="blog-recent" aria-labelledby="studii-list-title">
    <div class="container">
      <header class="blog-sec-head">
        <div>
          <div class="blog-sec-head__eyebrow">
            <span class="blog-sec-head__line" aria-hidden="true"></span>
            <span>{{ $hero_eyebrow }}</span>
            <span class="blog-sec-head__num">/ 01</span>
          </div>
          <h2 id="studii-list-title" class="blog-sec-head__title">
            @if (! empty($current_term))
              {{ __('Studii din', 'sage') }} <em>{{ $current_term->name }}</em>
            @else
              {{ __('Toate', 'sage') }} <em>{{ __('studiile', 'sage') }}</em>
            @endif
          </h2>
        </div>
        <p class="blog-sec-head__lede">{{ $hero_lede }}</p>
      </header>

      @if (! empty($study_terms))
        <div class="blog-toolbar">
          <div class="blog-filters" role="tablist" aria-label="{{ __('Filtre categorii', 'sage') }}">
            <a href="{{ esc_url(get_post_type_archive_link('studiu')) }}" class="blog-filter {{ $active_slug === 'all' ? 'is-active' : '' }}" role="tab">{{ __('Toate', 'sage') }}</a>
            @foreach ($study_terms as $t)
              <a href="{{ esc_url(get_term_link($t)) }}" class="blog-filter {{ $active_slug === $t->slug ? 'is-active' : '' }}" role="tab">
                {{ $t->name }} <span class="blog-filter__count">{{ (int) $t->count }}</span>
              </a>
            @endforeach
          </div>
        </div>
      @endif

      <div class="blog-card-grid blog-studii-grid">
        @if (have_posts())
          @while (have_posts())
            @php
              the_post();
              $terms = get_the_terms(get_the_ID(), 'categorie_studiu');
              $term_name = (! empty($terms) && ! is_wp_error($terms)) ? $terms[0]->name : '';
              $term_slug = (! empty($terms) && ! is_wp_error($terms)) ? $terms[0]->slug : '';

              // ACF fields (toate optionale).
              $link_sursa     = function_exists('get_field') ? (string) get_field('link_sursa') : '';
              $autori         = function_exists('get_field') ? (string) get_field('autori') : '';
              $an_publicare   = function_exists('get_field') ? (string) get_field('an_publicare') : '';
              $jurnal         = function_exists('get_field') ? (string) get_field('jurnal') : '';
              $tip_studiu     = function_exists('get_field') ? (string) get_field('tip_studiu') : '';
              $numar_pacienti = function_exists('get_field') ? (string) get_field('numar_pacienti') : '';
            @endphp
            <article class="blog-card blog-studiu-card" data-cat="{{ esc_attr($term_slug) }}">
              <a href="{{ esc_url(get_permalink()) }}" class="blog-card__body blog-studiu-card__body">
                <div class="blog-studiu-card__top">
                  @if ($tip_studiu)
                    <span class="blog-studiu-card__type">{{ $tip_studiu }}</span>
                  @endif
                  @if ($term_name)
                    <span class="blog-cat-pill">{{ $term_name }}</span>
                  @endif
                </div>

                <h4 class="blog-card__title blog-studiu-card__title">{{ get_the_title() }}</h4>

                @if (get_the_excerpt())
                  <p class="blog-card__excerpt">{{ wp_trim_words(get_the_excerpt(), 28, '...') }}</p>
                @endif

                <dl class="blog-studiu-card__meta">
                  @if ($autori)
                    <div>
                      <dt>{{ __('Autori', 'sage') }}</dt>
                      <dd>{{ $autori }}</dd>
                    </div>
                  @endif
                  @if ($jurnal)
                    <div>
                      <dt>{{ __('Jurnal', 'sage') }}</dt>
                      <dd>{{ $jurnal }}{{ $an_publicare ? ', ' . $an_publicare : '' }}</dd>
                    </div>
                  @elseif ($an_publicare)
                    <div>
                      <dt>{{ __('An', 'sage') }}</dt>
                      <dd>{{ $an_publicare }}</dd>
                    </div>
                  @endif
                  @if ($numar_pacienti)
                    <div>
                      <dt>{{ __('Pacienți', 'sage') }}</dt>
                      <dd>{{ $numar_pacienti }}</dd>
                    </div>
                  @endif
                </dl>
              </a>

              @if ($link_sursa)
                <a href="{{ esc_url($link_sursa) }}" class="blog-studiu-card__source" target="_blank" rel="noopener noreferrer">
                  {{ __('Sursă originală', 'sage') }}
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M14 4 H20 V10"/><path d="M20 4 L11 13"/><path d="M19 14 V19 C19 20 18 21 17 21 H5 C4 21 3 20 3 19 V7 C3 6 4 5 5 5 H10"/></svg>
                </a>
              @endif
            </article>
          @endwhile
        @else
          <p class="blog-empty">{{ __('Nu există studii disponibile încă.', 'sage') }}</p>
        @endif
      </div>

      @php
        global $wp_query;
        $big = 999999999;
        $paginate_links = paginate_links([
          'base'      => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
          'format'    => '?paged=%#%',
          'current'   => max(1, $paged),
          'total'     => $wp_query->max_num_pages,
          'type'      => 'array',
          'end_size'  => 1,
          'mid_size'  => 2,
          'prev_text' => '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12 H5"/><path d="M11 6 L5 12 L11 18"/></svg>',
          'next_text' => '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12 H19"/><path d="M13 6 L19 12 L13 18"/></svg>',
        ]);
      @endphp

      @if (is_array($paginate_links) && count($paginate_links) > 1)
        <nav class="blog-page-nav" aria-label="{{ __('Paginare studii', 'sage') }}">
          @foreach ($paginate_links as $link)
            {!! $link !!}
          @endforeach
        </nav>
      @endif
    </div>
  </section>

</div>
