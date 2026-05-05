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

    // Hero — featured image-ul paginii (desktop) + ACF `mobile_image`
    // (acelasi field folosit de shop) pentru mobile, cu fallback intre ele.
    $bg_url = get_the_post_thumbnail_url($page_id, 'full') ?: '';

    $mobile_image = function_exists('get_field') ? get_field('mobile_image', $page_id) : null;
    $bg_url_mobile = '';
    if ($mobile_image) {
        $bg_url_mobile = is_array($mobile_image) ? ($mobile_image['url'] ?? '') : $mobile_image;
    }
    if ($bg_url_mobile === '') {
        $bg_url_mobile = $bg_url;
    }

    $hero_title = get_the_title($page_id);
    $hero_alt   = $hero_title;

    // Paginare — pe page templates WP populeaza `page`, pe arhive `paged`.
    // Acceptam ambele ca sa functioneze cu /?paged=N si cu /pachete/page/N/.
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
    <div class="header_archive">
      <div class="hero_archive">
        @if ($bg_url)
          <picture class="hero_archive_picture">
            <source media="(max-width: 768px)" srcset="{{ esc_url($bg_url_mobile) }}">
            <source media="(min-width: 769px)" srcset="{{ esc_url($bg_url) }}">
            <img src="{{ esc_url($bg_url) }}" alt="{{ esc_attr($hero_alt) }}">
          </picture>
        @endif
        <div class="hero_archive_content">
          <div class="row gy-0 gx-0">
            <div class="col-md-12">
              <h1>{{ $hero_title }}</h1>
            </div>
          </div>
        </div>
      </div>
    </div>

    <ul class="products pachete-products">
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
