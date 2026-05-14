{{--
  Template: Product archive — folosit DOAR pentru pagina /magazin (shop).
  Categoriile au template propriu: taxonomy-product_cat.blade.php
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

    $hero_alt = get_the_title($shop_page_id);
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

    @include('partials.shop-loop')
  </div>{{-- /.archive-product-wrap --}}
@endsection
