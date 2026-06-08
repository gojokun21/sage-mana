{{--
  Template: Single product content — redesign după mockup
  `preferinte/PDP - Black Seed Elixir.html`.

  Scope `.pdp-page`. Hero-ul păstrează funcțional galeria (swiper), prețul și
  add-to-cart-ul WooCommerce; restul secțiunilor sunt editoriale statice (textul
  va fi mutat în ACF ulterior). Sticky bar-ul existent e păstrat (funcțional via
  single-product.js).

  @see https://docs.woocommerce.com/document/template-structure/
--}}

@php
  global $product;

  do_action('woocommerce_before_single_product');

  if (post_password_required()) {
      echo get_the_password_form();
      return;
  }

  // Date pentru sticky bar (păstrat din varianta anterioară).
  $product_image = wp_get_attachment_image_src(get_post_thumbnail_id($product->get_id()), 'thumbnail');
  $product_image_large = wp_get_attachment_image_src(get_post_thumbnail_id($product->get_id()), 'medium');
  $regular_price = $product->get_regular_price();
  $sale_price = $product->get_sale_price();
  $price = $product->get_price();
  $short_desc = wp_strip_all_tags($product->get_short_description());
  $sticky_max = $product->get_max_purchase_quantity();
  if (empty($sticky_max) || $sticky_max <= 0) {
      $sticky_max = 99;
  }
  $sticky_available = $product->is_purchasable() && $product->is_in_stock();
  $is_bundle = $product->is_type('bundle');
  $sticky_unavailable_text = $is_bundle ? 'Pachet indisponibil' : 'Stoc epuizat';
  $page_class = $is_bundle ? 'pachet-page' : 'pdp-page';
@endphp

<div class="{{ $page_class }}">
  <div id="product-{{ get_the_ID() }}" @php wc_product_class('', $product) @endphp>

    {{-- Breadcrumb-ul vine din Rank Math (woocommerce_before_main_content),
         deasupra wrapper-ului — un singur breadcrumb, stilizat în pdp.css/single-pachet.css. --}}

    @if ($is_bundle)
      {{-- Pagină de single PACHET (produs WC de tip `bundle`) — layout dedicat
           după mockup `preferinte/Pagina Pachet - Echilibru.html`. Hero-ul e cu
           date reale (produse componente, prețuri, economisire, add-to-cart);
           restul secțiunilor sunt editoriale statice (mutabile în ACF ulterior). --}}
      @include('partials.single-pachet.hero')
      @include('partials.single-pachet.proof')
      @include('partials.single-pachet.why')
      @include('partials.single-pachet.benefits')
      @include('partials.single-pachet.math')
      @include('partials.single-pachet.timeline')
      @include('partials.single-pachet.for-who')
      @include('partials.single-pachet.faq')
      @include('partials.single-pachet.cross-sell')
      @include('partials.single-pachet.quiz')
    @else
      @include('partials.single-product.hero')
      @include('partials.single-product.ingredient')
      @include('partials.single-product.how')
      @include('partials.single-product.for-who')
      @include('partials.single-product.standards')
      @include('partials.single-product.stack')
      @include('partials.single-product.reviews')
      @include('partials.single-product.faq')
      @include('partials.single-product.quiz')
    @endif
  </div>

  {{-- Sticky price bar — funcțional via single-product.js (apare la scroll). --}}
  <div class="sticky_price_container" id="sticky-price-container">
    <div class="sticky-price container">
      <div class="sticky-product-wrapper">
        @if ($product_image)
          <img class="sticky-product-img" src="{{ esc_url($product_image[0]) }}" alt="{{ esc_attr($product->get_name()) }}">
        @endif
        <div class="sticky_product_name_wrapper">
          <div class="sticky_product_name">{{ $product->get_name() }}</div>
        </div>
      </div>
      <div class="sticky-price-wrapper">
        <div class="sticky_prices">
          <div class="sticky_price_holder">
            @if ($sale_price)
              <div class="sticky-price-new">{!! wc_price($sale_price) !!}</div>
              <div class="sticky-price-old">{!! wc_price($regular_price) !!}</div>
            @else
              <div class="sticky-price-new">{!! wc_price($price) !!}</div>
            @endif
          </div>
        </div>
        <div class="sticky-price-buttons">
          @include('partials.qty-stepper', [
            'name' => 'sticky_qty',
            'value' => 1,
            'min' => 1,
            'max' => $sticky_max,
            'size' => 'sm',
            'input_class' => 'sticky-qty-input',
          ])

          @if ($sticky_available)
            <button type="button"
                    class="sticky-add-to-cart btn-green d-none d-md-flex"
                    data-product-id="{{ esc_attr($product->get_id()) }}"
                    data-product_id="{{ esc_attr($product->get_id()) }}"
                    data-product_name="{{ esc_attr($product->get_name()) }}"
                    data-product_url="{{ esc_url(get_permalink($product->get_id())) }}"
                    data-product_img="{{ esc_url($product_image_large[0] ?? '') }}"
                    data-product_packaging="{{ esc_attr($short_desc) }}">
              <span class="sticky-btn-text">Adaugă în Coș</span>
            </button>
            <button type="button"
                    class="sticky-add-to-cart-mobile d-md-none"
                    data-product-id="{{ esc_attr($product->get_id()) }}"
                    data-product_id="{{ esc_attr($product->get_id()) }}"
                    data-product_name="{{ esc_attr($product->get_name()) }}"
                    data-product_url="{{ esc_url(get_permalink($product->get_id())) }}"
                    data-product_img="{{ esc_url($product_image_large[0] ?? '') }}"
                    data-product_packaging="{{ esc_attr($short_desc) }}">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <path d="M2 3l.265.088c1.32.44 1.98.66 2.357 1.184.377.524.378 1.22.378 2.611V9.5c0 2.828 0 4.243.879 5.121.878.879 2.293.879 5.121.879h8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                <path d="M5 6h11.45c2.055 0 3.083 0 3.528.674.444.675.04 1.619-.77 3.508l-.429 1c-.378.882-.567 1.322-.942 1.57-.376.248-.856.248-1.815.248H5" stroke="currentColor" stroke-width="1.5"/>
                <circle cx="7.5" cy="19.5" r="1.5" stroke="currentColor" stroke-width="1.5"/>
                <circle cx="16.5" cy="19.5" r="1.5" stroke="currentColor" stroke-width="1.5"/>
              </svg>
            </button>
          @else
            <span class="sticky-add-to-cart btn-green d-none d-md-flex btn-unavailable" data-product_id="{{ esc_attr($product->get_id()) }}">
              {{ $sticky_unavailable_text }}
            </span>
            <span class="sticky-add-to-cart-mobile d-md-none btn-unavailable" data-product_id="{{ esc_attr($product->get_id()) }}">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <path d="M2 3l.265.088c1.32.44 1.98.66 2.357 1.184.377.524.378 1.22.378 2.611V9.5c0 2.828 0 4.243.879 5.121.878.879 2.293.879 5.121.879h8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                <path d="M5 6h11.45c2.055 0 3.083 0 3.528.674.444.675.04 1.619-.77 3.508l-.429 1c-.378.882-.567 1.322-.942 1.57-.376.248-.856.248-1.815.248H5" stroke="currentColor" stroke-width="1.5"/>
              </svg>
            </span>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>

@php do_action('woocommerce_after_single_product') @endphp
