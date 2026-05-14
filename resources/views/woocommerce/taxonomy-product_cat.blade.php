{{--
  Template: Product category (taxonomy product_cat).
  Picked up automatically by WP/WC hierarchy when is_product_category() is true.
  Shop page (/magazin) ramane pe archive-product.blade.php.
--}}

@extends('layouts.app')

@section('content')
  @php
    $term = get_queried_object();
    $shop_page_id = wc_get_page_id('shop');
    $fallback_url = get_the_post_thumbnail_url($shop_page_id, 'full');

    $bg_url = $fallback_url;
    $bg_url_mobile = $fallback_url;

    $header_image = get_field('header_category', $term);
    if ($header_image) {
        $bg_url = is_array($header_image) ? $header_image['url'] : $header_image;
    }

    $mobile_header = get_field('mobile_category', $term);
    if ($mobile_header) {
        $bg_url_mobile = is_array($mobile_header) ? $mobile_header['url'] : $mobile_header;
    } else {
        $bg_url_mobile = $bg_url;
    }

    $hero_alt = single_term_title('', false);
  @endphp

  <div class="archive-product-wrap is-category">
    <div class="header_archive">
      @php do_action('woocommerce_before_main_content') @endphp

      @php
        // Toate datele hero din ACF (group_categorie_continut_pagina, tab Hero).
        // Daca NIMENI din campurile hero nu e completat → fallback la WC nativ
        // (`woocommerce_shop_loop_header`) ca pagina sa arate identic cu shop-ul
        // pe categoriile neconfigurate inca.
        $hero_pill = get_field('hero_pill_tag', $term);
        $hero_l1_raw = get_field('hero_title_line_1', $term);
        $hero_l2 = get_field('hero_title_line_2_underlined', $term);
        $hero_lead = get_field('hero_lead', $term);
        $hero_chips_data = get_field('hero_chips', $term);
        $hero_callout_label = get_field('hero_callout_label', $term);
        $hero_callout_text = get_field('hero_callout_text', $term);

        $hero_has_custom = $hero_pill
            || $hero_l1_raw
            || $hero_l2
            || $hero_lead
            || $hero_callout_label
            || ! empty($hero_chips_data);

        $hero_l1 = $hero_l1_raw ?: single_term_title('', false);
      @endphp

      <div class="hero_archive{{ $hero_has_custom ? ' has-custom-hero' : '' }}">
        <picture class="hero_archive_picture">
          <source media="(max-width: 768px)" srcset="{{ esc_url($bg_url_mobile) }}">
          <source media="(min-width: 769px)" srcset="{{ esc_url($bg_url) }}">
          <img src="{{ esc_url($bg_url) }}" alt="{{ esc_attr($hero_alt) }}">
        </picture>
        <div class="hero_archive_content">
          <div class="row gy-0 gx-0">
            <div class="col-md-12">
              @if ($hero_has_custom)
                <div class="hero">
                  @if ($hero_pill)
                    <span class="pill-tag">{!! wp_kses($hero_pill, ['span' => ['class' => true]]) !!}</span>
                  @endif

                  <h1>
                    {{ $hero_l1 }}@if ($hero_l2)<br><span class="underlined">{{ $hero_l2 }}</span>@endif
                  </h1>

                  @if ($hero_lead)
                    <p class="lead">{!! nl2br(wp_kses_post($hero_lead)) !!}</p>
                  @endif

                  @if (function_exists('have_rows') && have_rows('hero_chips', $term))
                    <div class="chips">
                      @while (have_rows('hero_chips', $term)) @php the_row() @endphp
                        @php
                          $chip_icon = get_sub_field('icon');
                          $chip_label = get_sub_field('label');
                        @endphp
                        @if ($chip_label)
                          <span class="chip-pill">
                            @if ($chip_icon)<span class="ico">{{ $chip_icon }}</span>@endif
                            {{ $chip_label }}
                          </span>
                        @endif
                      @endwhile
                    </div>
                  @endif

                  @if ($hero_callout_label)
                    <div class="hero-callout">
                      <div class="label">{{ $hero_callout_label }}</div>
                      @if ($hero_callout_text)
                        <p>{!! nl2br(wp_kses_post($hero_callout_text)) !!}</p>
                      @endif
                    </div>
                  @endif
                </div>
              @else
                {{-- Fallback la randarea WC nativa (titlu + descriere categorie). --}}
                @php do_action('woocommerce_shop_loop_header') @endphp
              @endif
            </div>
          </div>
        </div>
      </div>
      <div class="breadcrumb_archive">
        <div class="sort_wrapper">
          @php do_action('woocommerce_before_shop_loop') @endphp
        </div>
      </div>
    </div>

    {{-- Feature product — bloc editorial sub hero. Datele vin din ACF
         (grup `group_feature_product_categorie`, pe term `product_cat`).
         Daca produsul nu e selectat sau nu e vizibil, blocul nu se randeaza.
         Pattern: identic cu upgrade_pack din content-single-product.blade.php. --}}
    @php
      $feat_product_field = function_exists('get_field') ? get_field('feature_product', $term) : null;
      $feat_product = null;
      $feat_product_id = null;

      if ($feat_product_field) {
          $feat_product_id = is_object($feat_product_field) ? $feat_product_field->ID : (int) $feat_product_field;
          $feat_product = wc_get_product($feat_product_id);
      }
    @endphp

    @if ($feat_product && $feat_product->is_visible())
      @php
        $feat_image = wp_get_attachment_image_src(get_post_thumbnail_id($feat_product_id), 'large');
        $feat_ribbon = get_field('feature_ribbon_text', $term);
        $feat_kicker = get_field('feature_kicker_text', $term);
        $feat_price_meta = get_field('feature_price_meta', $term);
        $feat_cta = get_field('feature_cta_label', $term) ?: 'ADAUGA IN COS';
        $feat_desc_override = get_field('feature_description_override', $term);
        $feat_desc = $feat_desc_override ?: $feat_product->get_short_description();
        $feat_permalink = get_permalink($feat_product_id);

        // AJAX add-to-cart marker classes — same triple-check WC uses
        // internally (purchasable + in stock + product type supports it).
        $feat_classes = ['btn-primary', 'feature_atc_btn', 'product_type_' . $feat_product->get_type()];
        if ($feat_product->is_purchasable() && $feat_product->is_in_stock() && $feat_product->supports('ajax_add_to_cart')) {
            $feat_classes[] = 'add_to_cart_button';
            $feat_classes[] = 'ajax_add_to_cart';
        }
      @endphp

      <section class="feature-product">
        @if ($feat_ribbon)
          <div class="ribbon">{{ $feat_ribbon }}</div>
        @endif
        <div class="image">
          <a href="{{ esc_url($feat_permalink) }}" aria-label="{{ esc_attr($feat_product->get_name()) }}">
            @if ($feat_image)
              <img src="{{ esc_url($feat_image[0]) }}"
                   alt="{{ esc_attr($feat_product->get_name()) }}"
                   loading="lazy"
                   width="{{ $feat_image[1] }}"
                   height="{{ $feat_image[2] }}">
            @endif
          </a>
        </div>
        <div class="body">
          @if ($feat_kicker)
            <div class="kicker">{{ $feat_kicker }}</div>
          @endif
          <h2><a href="{{ esc_url($feat_permalink) }}">{{ $feat_product->get_name() }}</a></h2>
          @if ($feat_desc)
            <div class="desc">{!! wp_kses_post($feat_desc) !!}</div>
          @endif
          @if (function_exists('have_rows') && have_rows('feature_features_list', $term))
            <ul class="features">
              @while (have_rows('feature_features_list', $term)) @php the_row() @endphp
                <li>{{ get_sub_field('feature_item') }}</li>
              @endwhile
            </ul>
          @endif
          <div class="price-row">
            <div>
              <div class="price-big">{!! $feat_product->get_price_html() !!}</div>
              @if ($feat_price_meta)
                <div class="price-meta">{!! wp_kses_post(str_replace('•', '<span class="dot">•</span>', esc_html($feat_price_meta))) !!}</div>
              @endif
            </div>
            <a href="{{ esc_url($feat_product->add_to_cart_url()) }}"
               data-product_id="{{ $feat_product_id }}"
               data-product_sku="{{ esc_attr($feat_product->get_sku()) }}"
               data-quantity="1"
               class="{{ implode(' ', $feat_classes) }}"
               rel="nofollow">
              <svg class="cart-shopping" width="15" height="15" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <path d="M0.5625 1.125C0.250781 1.125 0 1.37578 0 1.6875C0 1.99922 0.250781 2.25 0.5625 2.25H1.62422C1.71563 2.25 1.79297 2.31562 1.80937 2.40469L3.03047 9.11484C3.17578 9.91641 3.87422 10.5 4.68984 10.5H10.6875C10.9992 10.5 11.25 10.2492 11.25 9.9375C11.25 9.62578 10.9992 9.375 10.6875 9.375H4.68984C4.41797 9.375 4.18594 9.18047 4.13672 8.91328L4.01719 8.25H11.1328C11.8547 8.25 12.4734 7.73672 12.607 7.02656L13.3336 3.13828C13.4203 2.67656 13.0664 2.25 12.5953 2.25H2.92266L2.91328 2.20312C2.80078 1.57969 2.25703 1.125 1.62187 1.125H0.5625ZM4.875 13.5C5.49609 13.5 6 12.9961 6 12.375C6 11.7539 5.49609 11.25 4.875 11.25C4.25391 11.25 3.75 11.7539 3.75 12.375C3.75 12.9961 4.25391 13.5 4.875 13.5ZM10.125 13.5C10.7461 13.5 11.25 12.9961 11.25 12.375C11.25 11.7539 10.7461 11.25 10.125 11.25C9.50391 11.25 9 11.7539 9 12.375C9 12.9961 9.50391 13.5 10.125 13.5Z" fill="currentColor"/>
              </svg>
              <span>{{ $feat_cta }}</span>
            </a>
          </div>
        </div>
      </section>
    @endif

    <div class="fe_chips_container">
      {!! do_shortcode('[fe_chips]') !!}
    </div>

    @include('partials.shop-loop')

    {{-- Stats / trust strip — repeater ACF `stats_items`. Sectiunea dispare
         daca nu sunt items. `lbl` foloseste new_lines=br in ACF, deci <br>-urile
         vin gata generate; doar le filtram cu wp_kses pentru siguranta. --}}
    @if (function_exists('have_rows') && have_rows('stats_items', $term))
      <div class="container">
        <section class="stats">
          @while (have_rows('stats_items', $term)) @php the_row() @endphp
            <div class="item">
              <div class="num">{{ get_sub_field('num') }}</div>
              <div class="lbl">{!! wp_kses(get_sub_field('lbl'), ['br' => []]) !!}</div>
            </div>
          @endwhile
        </section>
      </div>
    @endif

    {{-- Phases — ACF `phases_items` repeater. Sectiunea apare doar daca
         exista cel putin un item. Headingul + intro sunt si ele optionale. --}}
    @if (function_exists('have_rows') && have_rows('phases_items', $term))
      @php
        $phases_heading = get_field('phases_heading', $term);
        $phases_intro = get_field('phases_intro', $term);
      @endphp
      <div class="container">
        <section class="phases">
          @if ($phases_heading)
            <h2>{{ $phases_heading }}</h2>
          @endif
          @if ($phases_intro)
            <p class="intro">{!! nl2br(wp_kses_post($phases_intro)) !!}</p>
          @endif
          <div class="phase-grid">
            @while (have_rows('phases_items', $term)) @php the_row() @endphp
              @php
                $p_num = get_sub_field('num');
                $p_title = get_sub_field('title');
                $p_desc = get_sub_field('description');
                $p_tag = get_sub_field('tag');
              @endphp
              <div class="phase-card">
                @if ($p_num)<div class="phase-num">{{ $p_num }}</div>@endif
                @if ($p_title)<h3>{{ $p_title }}</h3>@endif
                @if ($p_desc)<p>{!! wp_kses_post($p_desc) !!}</p>@endif
                @if ($p_tag)<span class="tag-light">{{ $p_tag }}</span>@endif
              </div>
            @endwhile
          </div>
        </section>
      </div>
    @endif

    {{-- Daily habits — ACF `zilnic_items` repeater. Fiecare item are doua
         sub-campuri (bold + description) pe care le compunem in formatul
         <strong>{bold}</strong><span class="sep">—</span>{description}. --}}
    @if (function_exists('have_rows') && have_rows('zilnic_items', $term))
      @php $zilnic_heading = get_field('zilnic_heading', $term); @endphp
      <div class="container">
        <section class="zilnic">
          @if ($zilnic_heading)
            <h3>{{ $zilnic_heading }}</h3>
          @endif
          <ul>
            @while (have_rows('zilnic_items', $term)) @php the_row() @endphp
              @php
                $z_bold = get_sub_field('bold');
                $z_desc = get_sub_field('description');
              @endphp
              @if ($z_bold || $z_desc)
                <li>
                  <span class="chk">✓</span>
                  <span>
                    @if ($z_bold)<strong>{{ $z_bold }}</strong>@endif
                    @if ($z_bold && $z_desc)<span class="sep">—</span>@endif
                    {{ $z_desc }}
                  </span>
                </li>
              @endif
            @endwhile
          </ul>
        </section>
      </div>
    @endif

    {{-- Testimonials — ACF `testimonials_items`. Apare doar daca exista
         items. `verified` boolean controleaza pill-ul "✓ CUMPARATURA VERIFICATA". --}}
    @if (function_exists('have_rows') && have_rows('testimonials_items', $term))
      @php
        $testi_heading = get_field('testimonials_heading', $term);
        $testi_intro = get_field('testimonials_intro', $term);
      @endphp
      <div class="container">
        <section class="testimonials">
          @if ($testi_heading)<h2>{{ $testi_heading }}</h2>@endif
          @if ($testi_intro)<p>{!! nl2br(wp_kses_post($testi_intro)) !!}</p>@endif
          <div class="testi-grid">
            @while (have_rows('testimonials_items', $term)) @php the_row() @endphp
              @php
                $t_verified = get_sub_field('verified');
                $t_quote = get_sub_field('quote');
                $t_name = get_sub_field('name');
                $t_sub = get_sub_field('sub');
                $t_initials = get_sub_field('avatar_initials');
                // Auto-derive initials from name daca admin n-a completat.
                if (! $t_initials && $t_name) {
                    $parts = preg_split('/\s+/', trim((string) $t_name));
                    $first = mb_substr($parts[0] ?? '', 0, 1);
                    $last = mb_substr($parts[count($parts) - 1] ?? '', 0, 1);
                    $t_initials = mb_strtoupper($first . $last);
                }
              @endphp
              <div class="testi-card">
                @if ($t_verified)
                  <span class="verified">✓ CUMPARATURA VERIFICATA</span>
                @endif
                @if ($t_quote)
                  <p class="quote">{!! wp_kses_post($t_quote) !!}</p>
                @endif
                @if ($t_name)
                  <div class="author">
                    @if ($t_initials)<div class="avatar">{{ $t_initials }}</div>@endif
                    <div class="meta">
                      <div class="name">{{ $t_name }}</div>
                      @if ($t_sub)<div class="sub">{{ $t_sub }}</div>@endif
                    </div>
                  </div>
                @endif
              </div>
            @endwhile
          </div>
        </section>
      </div>
    @endif

    {{-- FAQ — ACF `faq_items` repeater. Primul item se deschide automat
         (folosim get_row_index() == 1, ACF foloseste indexing 1-based).
         Toggle-ul +/− din span e ignorat de CSS (chevron rotativ vine din
         pseudo-element, JS-ul gestioneaza animatia smooth). --}}
    @if (function_exists('have_rows') && have_rows('faq_items', $term))
      @php
        $faq_heading = get_field('faq_heading', $term);
        $faq_intro = get_field('faq_intro', $term);
      @endphp
      <div class="container">
        <section class="faq">
          @if ($faq_heading)<h2>{{ $faq_heading }}</h2>@endif
          @if ($faq_intro)<p>{!! nl2br(wp_kses_post($faq_intro)) !!}</p>@endif

          @while (have_rows('faq_items', $term)) @php the_row() @endphp
            @php
              $q = get_sub_field('question');
              $a = get_sub_field('answer');
              $is_open = get_row_index() === 1;
            @endphp
            @if ($q && $a)
              <details class="faq-item" @if ($is_open) open @endif>
                <summary class="faq-q">{{ $q }}<span class="faq-toggle">{{ $is_open ? '−' : '+' }}</span></summary>
                <div class="faq-a">{!! wp_kses_post($a) !!}</div>
              </details>
            @endif
          @endwhile
        </section>
      </div>
    @endif

    {{-- Blog — heading/intro editoriale + relationship ACF `related_articles`.
         Daca admin alege articole manual, suprascriu „ultimele 3 publicate”. --}}
    @php
      $blog_heading = get_field('blog_heading', $term);
      $blog_intro = get_field('blog_intro', $term);

      $blog_args = [
          'post_type' => 'post',
          'posts_per_page' => 3,
          'post_status' => 'publish',
          'no_found_rows' => true,
          'ignore_sticky_posts' => true,
      ];

      $related = function_exists('get_field') ? get_field('related_articles', $term) : null;
      if (! empty($related)) {
          $ids = array_map(static fn ($p) => is_object($p) ? $p->ID : (int) $p, (array) $related);
          $blog_args = [
              'post_type' => 'post',
              'posts_per_page' => 3,
              'post__in' => $ids,
              'orderby' => 'post__in',
              'no_found_rows' => true,
              'ignore_sticky_posts' => true,
          ];
      }

      $blog_posts = new \WP_Query($blog_args);
    @endphp

    @if ($blog_posts->have_posts())
      <div class="container">
        <section class="blog">
          @if ($blog_heading)<h2>{{ $blog_heading }}</h2>@endif
          @if ($blog_intro)<p>{!! nl2br(wp_kses_post($blog_intro)) !!}</p>@endif
          <div class="blog-grid">
            @while ($blog_posts->have_posts())
              @php
                $blog_posts->the_post();
                $cats = get_the_category();
                $primary_tag = $cats ? strtoupper($cats[0]->name) : '';
                $date_formatted = wp_date('j M Y');
              @endphp
              <a class="blog-card" href="{{ esc_url(get_permalink()) }}">
                <div class="image">
                  @if (has_post_thumbnail())
                    {!! get_the_post_thumbnail(get_the_ID(), 'medium_large', [
                      'loading' => 'lazy',
                      'alt' => esc_attr(get_the_title()),
                    ]) !!}
                  @else
                    <span class="emoji" aria-hidden="true">📰</span>
                  @endif
                </div>
                <div class="body">
                  @if ($primary_tag)
                    <div class="tag">{{ $primary_tag }}</div>
                  @endif
                  <h3>{{ get_the_title() }}</h3>
                  <div class="meta">{{ $date_formatted }}</div>
                </div>
              </a>
            @endwhile
            @php wp_reset_postdata(); @endphp
          </div>
        </section>
      </div>
    @endif

    {{-- Legal disclaimer — ACF `legal_label` + `legal_body` (wysiwyg basic).
         Apare doar daca legal_body e completat. Separatorul "•" din label e
         convertit in <span class="sep"> pentru contrast vizual. --}}
    @php
      $legal_label = get_field('legal_label', $term);
      $legal_body = get_field('legal_body', $term);
    @endphp
    @if ($legal_body)
      <div class="container">
        <div class="legal-box">
          @if ($legal_label)
            <div class="label">
              {!! str_replace('•', '<span class="sep">•</span>', esc_html($legal_label)) !!}
            </div>
          @endif
          {!! wp_kses_post($legal_body) !!}
        </div>
      </div>
    @endif
  </div>{{-- /.archive-product-wrap --}}
@endsection
