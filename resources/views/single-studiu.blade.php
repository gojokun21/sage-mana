{{--
  Single — Studiu (CPT `studiu`).
  Layout: header cu meta + corp + sidebar cu CTA-ul "Sursă originală" si
  meta-detalii. Articole relationate la final daca field-ul ACF e completat.
--}}

@extends('layouts.app')

@section('content')
  @while (have_posts())
    @php
      the_post();

      $terms = get_the_terms(get_the_ID(), 'categorie_studiu');
      $cat_name = (! empty($terms) && ! is_wp_error($terms)) ? $terms[0]->name : '';
      $cat_link = (! empty($terms) && ! is_wp_error($terms)) ? get_term_link($terms[0]) : '';

      $link_sursa     = function_exists('get_field') ? (string) get_field('link_sursa') : '';
      $autori         = function_exists('get_field') ? (string) get_field('autori') : '';
      $an_publicare   = function_exists('get_field') ? (string) get_field('an_publicare') : '';
      $jurnal         = function_exists('get_field') ? (string) get_field('jurnal') : '';
      $tip_studiu     = function_exists('get_field') ? (string) get_field('tip_studiu') : '';
      $numar_pacienti = function_exists('get_field') ? (string) get_field('numar_pacienti') : '';
      $articole_rel   = function_exists('get_field') ? get_field('articole_relationate') : null;
    @endphp

    <div class="blog-page">

      {{-- Header --}}
      <section class="blog-hero blog-studiu-hero" aria-labelledby="studiu-title">
        <div class="container">
          <nav class="blog-hero__crumbs" aria-label="{{ __('Breadcrumb', 'sage') }}">
            <a href="{{ home_url('/') }}">{{ __('Acasă', 'sage') }}</a>
            <span class="blog-hero__sep" aria-hidden="true">/</span>
            <a href="{{ esc_url(get_post_type_archive_link('studiu')) }}">{{ __('Studii', 'sage') }}</a>
            @if ($cat_name && $cat_link)
              <span class="blog-hero__sep" aria-hidden="true">/</span>
              <a href="{{ esc_url($cat_link) }}">{{ $cat_name }}</a>
            @endif
          </nav>

          <div class="blog-studiu-hero__top">
            @if ($tip_studiu)
              <span class="blog-studiu-card__type">{{ $tip_studiu }}</span>
            @endif
            @if ($cat_name)
              <span class="blog-cat-pill">{{ $cat_name }}</span>
            @endif
          </div>

          <h1 id="studiu-title" class="blog-studiu-hero__title">{{ get_the_title() }}</h1>

          @if (get_the_excerpt())
            <p class="blog-studiu-hero__lede">{{ get_the_excerpt() }}</p>
          @endif

          @if ($autori || $jurnal || $an_publicare)
            <p class="blog-studiu-hero__cite">
              @if ($autori){{ $autori }}@endif
              @if ($jurnal) <em>{{ $jurnal }}</em>@endif
              @if ($an_publicare) ({{ $an_publicare }})@endif
            </p>
          @endif
        </div>
      </section>

      {{-- Body + sidebar --}}
      <section class="blog-studiu-body" aria-label="{{ __('Conținutul studiului', 'sage') }}">
        <div class="container">
          <div class="blog-studiu-body__grid">

            <article class="blog-studiu-body__main">
              @if (get_post_thumbnail_id())
                <figure class="blog-studiu-body__media">
                  {!! get_the_post_thumbnail(get_the_ID(), 'large', ['loading' => 'eager']) !!}
                </figure>
              @endif

              <div class="description_block">
                @php the_content() @endphp
              </div>
            </article>

            <aside class="blog-studiu-body__side">
              @if ($link_sursa)
                <div class="blog-studiu-side-card blog-studiu-side-card--cta">
                  <h3>{{ __('Sursă originală', 'sage') }}</h3>
                  <p>{{ __('Studiul publicat poate fi consultat integral pe platforma sursei.', 'sage') }}</p>
                  <a href="{{ esc_url($link_sursa) }}" class="blog-btn-primary" target="_blank" rel="noopener noreferrer">
                    {{ __('Deschide studiul', 'sage') }}
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M14 4 H20 V10"/><path d="M20 4 L11 13"/><path d="M19 14 V19 C19 20 18 21 17 21 H5 C4 21 3 20 3 19 V7 C3 6 4 5 5 5 H10"/></svg>
                  </a>
                </div>
              @endif

              @if ($autori || $jurnal || $an_publicare || $tip_studiu || $numar_pacienti)
                <div class="blog-studiu-side-card">
                  <h3>{{ __('Detalii', 'sage') }}</h3>
                  <dl class="blog-studiu-detail-list">
                    @if ($autori)
                      <div>
                        <dt>{{ __('Autori', 'sage') }}</dt>
                        <dd>{{ $autori }}</dd>
                      </div>
                    @endif
                    @if ($jurnal)
                      <div>
                        <dt>{{ __('Jurnal', 'sage') }}</dt>
                        <dd>{{ $jurnal }}</dd>
                      </div>
                    @endif
                    @if ($an_publicare)
                      <div>
                        <dt>{{ __('An publicare', 'sage') }}</dt>
                        <dd>{{ $an_publicare }}</dd>
                      </div>
                    @endif
                    @if ($tip_studiu)
                      <div>
                        <dt>{{ __('Tip studiu', 'sage') }}</dt>
                        <dd>{{ $tip_studiu }}</dd>
                      </div>
                    @endif
                    @if ($numar_pacienti)
                      <div>
                        <dt>{{ __('Pacienți (N)', 'sage') }}</dt>
                        <dd>{{ $numar_pacienti }}</dd>
                      </div>
                    @endif
                    @if ($cat_name && $cat_link)
                      <div>
                        <dt>{{ __('Domeniu', 'sage') }}</dt>
                        <dd><a href="{{ esc_url($cat_link) }}">{{ $cat_name }}</a></dd>
                      </div>
                    @endif
                  </dl>
                </div>
              @endif
            </aside>

          </div>
        </div>
      </section>

      {{-- Articole relationate (din ACF Relationship field) --}}
      @if (! empty($articole_rel) && is_array($articole_rel))
        <section class="blog-pillar" aria-labelledby="studiu-related-title">
          <div class="container">
            <header class="blog-sec-head">
              <div>
                <div class="blog-sec-head__eyebrow">
                  <span class="blog-sec-head__line" aria-hidden="true"></span>
                  <span>{{ __('Pe blog', 'sage') }}</span>
                </div>
                <h2 id="studiu-related-title" class="blog-sec-head__title">
                  {{ __('Articole care', 'sage') }} <em>{{ __('citează', 'sage') }}</em> {{ __('acest studiu', 'sage') }}
                </h2>
              </div>
              <p class="blog-sec-head__lede">{{ __('Conținut educațional bazat pe rezultatele de mai sus.', 'sage') }}</p>
            </header>

            <div class="blog-card-grid">
              @foreach ($articole_rel as $rel)
                @php
                  $rel_id = $rel instanceof \WP_Post ? $rel->ID : (int) $rel;
                  if (! $rel_id) continue;
                  $rel_cats = get_the_category($rel_id);
                  $rel_cat_name = (! empty($rel_cats) && ! is_wp_error($rel_cats)) ? $rel_cats[0]->name : '';
                  $rel_cat_slug = (! empty($rel_cats) && ! is_wp_error($rel_cats)) ? $rel_cats[0]->slug : '';
                  $rel_thumb = get_the_post_thumbnail_url($rel_id, 'medium_large');
                  $rel_reading = max(2, (int) ceil(str_word_count(strip_tags(get_post_field('post_content', $rel_id))) / 200));
                @endphp
                <article class="blog-card" data-cat="{{ esc_attr($rel_cat_slug) }}">
                  <a href="{{ esc_url(get_permalink($rel_id)) }}" class="blog-card__media">
                    @if ($rel_thumb)
                      <img src="{{ esc_url($rel_thumb) }}" alt="{{ esc_attr(get_the_title($rel_id)) }}" loading="lazy">
                    @else
                      <span class="blog-card__placeholder" aria-hidden="true"></span>
                    @endif
                    <span class="blog-card__reading">{{ sprintf(__('%d min', 'sage'), $rel_reading) }}</span>
                  </a>
                  <div class="blog-card__body">
                    @if ($rel_cat_name)
                      <span class="blog-cat-pill">{{ $rel_cat_name }}</span>
                    @endif
                    <h4 class="blog-card__title">
                      <a href="{{ esc_url(get_permalink($rel_id)) }}">{{ get_the_title($rel_id) }}</a>
                    </h4>
                    <p class="blog-card__excerpt">{{ wp_trim_words(get_the_excerpt($rel_id), 22, '...') }}</p>
                    <div class="blog-card__meta">
                      <time datetime="{{ get_the_date('c', $rel_id) }}">{{ get_the_date('', $rel_id) }}</time>
                      <span class="blog-meta-dot" aria-hidden="true">·</span>
                      <span>{{ sprintf(__('%d min citit', 'sage'), $rel_reading) }}</span>
                    </div>
                  </div>
                </article>
              @endforeach
            </div>
          </div>
        </section>
      @endif

    </div>
  @endwhile
@endsection
