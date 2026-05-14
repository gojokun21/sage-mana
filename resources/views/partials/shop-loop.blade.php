{{--
  Shared shop loop: filter sidebar + product grid + pagination hook.
  Used by archive-product (shop) and taxonomy-product_cat (categorii).
--}}

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
