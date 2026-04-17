{{--
  Template: Product archive (shop & product categories)
  @see https://docs.woocommerce.com/document/template-structure/
--}}

@extends('layouts.app')

@section('content')
  @php
    $shop_page_id = wc_get_page_id('shop');
    $fallback_url = get_the_post_thumbnail_url($shop_page_id, 'full');

    $bg_url = $fallback_url;
    $bg_url_mobile = $fallback_url;

    $shop_mobile_image = get_field('mobile_image', $shop_page_id);
    if ($shop_mobile_image) {
        $bg_url_mobile = is_array($shop_mobile_image) ? $shop_mobile_image['url'] : $shop_mobile_image;
    }

    if (is_product_category()) {
        $term = get_queried_object();

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
    }

    $hero_alt = is_product_category() ? single_term_title('', false) : get_the_title($shop_page_id);
  @endphp

  <div class="archive-product-wrap">
    <div class="header_archive">
      @php do_action('woocommerce_before_main_content') @endphp

      <div class="hero_archive">
        <picture class="hero_archive_picture">
          <source media="(max-width: 768px)" srcset="{{ esc_url($bg_url_mobile) }}">
          <source media="(min-width: 769px)" srcset="{{ esc_url($bg_url) }}">
          <img src="{{ esc_url($bg_url) }}" alt="{{ esc_attr($hero_alt) }}">
        </picture>
        <div class="hero_archive_content">
          <div class="row gy-0 gx-0">
            <div class="col-md-12">
              @php do_action('woocommerce_shop_loop_header') @endphp
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

    <div class="fe_chips_container">
      {!! do_shortcode('[fe_chips]') !!}
    </div>

    <div class="shop_wrapper">
      <button class="filter-toggle-btn" id="filterToggle">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
        Categorii
      </button>

      <aside class="shop_filter" id="shopFilter">
        {!! do_shortcode('[fe_widget]') !!}
      </aside>

      <div class="shop_products">
        <div id="wpc-products" class="wpc-products-container">
          @if (woocommerce_product_loop())
            @php woocommerce_product_loop_start() @endphp

            @while (have_posts())
              @php the_post() @endphp
              @php wc_get_template_part('content', 'product') @endphp
            @endwhile

            @php
              woocommerce_product_loop_end();
              do_action('woocommerce_after_shop_loop');
            @endphp
          @else
            @php do_action('woocommerce_no_products_found') @endphp
          @endif
        </div>

        @php do_action('woocommerce_after_main_content') @endphp
      </div>
    </div>
  </div>{{-- /.archive-product-wrap --}}
@endsection
