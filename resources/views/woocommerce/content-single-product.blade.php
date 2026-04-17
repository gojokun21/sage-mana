{{--
  Template: Single product content
  @see https://docs.woocommerce.com/document/template-structure/
--}}

@php
  global $product;

  do_action('woocommerce_before_single_product');

  if (post_password_required()) {
      echo get_the_password_form();
      return;
  }
@endphp

<div id="product-{{ get_the_ID() }}" @php wc_product_class('', $product) @endphp>
  <div class="single_products_container">
    @php do_action('woocommerce_before_single_product_summary') @endphp

    <div class="summary entry-summary">
      <div class="summary_white">
        @php do_action('woocommerce_single_product_summary') @endphp
      </div>

      @php
        $bundles_output = shortcode_exists('mn_bundles') ? do_shortcode('[mn_bundles]') : '';
      @endphp

      @if (!empty(trim($bundles_output)))
        <div class="discount_boxes">
          <h2>Reducere la cantitate</h2>
          {!! $bundles_output !!}
        </div>
      @endif
    </div>
  </div>

  @php
    $upgrade_pack_id = function_exists('get_field') ? get_field('upgrade_pack') : null;
    $upgrade_product = null;

    if ($upgrade_pack_id) {
        $upgrade_product_id = is_object($upgrade_pack_id) ? $upgrade_pack_id->ID : $upgrade_pack_id;
        $upgrade_product = wc_get_product($upgrade_product_id);
    }
  @endphp

  @if ($upgrade_product && $upgrade_product->is_visible())
    @php
      $upgrade_image = wp_get_attachment_image_src(get_post_thumbnail_id($upgrade_product_id), 'large');
      $upgrade_regular_price = $upgrade_product->get_regular_price();
      $upgrade_sale_price = $upgrade_product->get_sale_price();
      $upgrade_short_desc = $upgrade_product->get_short_description();
      $savings_percent = 0;
      if ($upgrade_sale_price && $upgrade_regular_price) {
          $savings_percent = round((($upgrade_regular_price - $upgrade_sale_price) / $upgrade_regular_price) * 100);
      }
    @endphp

    <div class="upgrade_pack_section">
      <h2>Pentru un efect mai complet, recomandăm varianta de pachet</h2>
      <div class="upgrade_pack_box">
        <div class="upgrade_pack_image">
          @if ($upgrade_image)
            <a href="{{ esc_url(get_permalink($upgrade_product_id)) }}">
              <img src="{{ esc_url($upgrade_image[0]) }}" alt="{{ esc_attr($upgrade_product->get_name()) }}">
            </a>
          @endif
        </div>
        <div class="upgrade_pack_info">
          <span class="upgrade_pack_badge">Recomandat</span>
          <a href="{{ esc_url(get_permalink($upgrade_product_id)) }}" class="upgrade_pack_title">
            {{ esc_html($upgrade_product->get_name()) }}
          </a>
          @if ($upgrade_short_desc)
            <div class="upgrade_pack_desc">{!! wp_kses_post($upgrade_short_desc) !!}</div>
          @endif

          @if (function_exists('have_rows') && have_rows('beneficii_pachete', 'options'))
            @while (have_rows('beneficii_pachete', 'options')) @php the_row() @endphp
              <div class="upgrade_pack_features">
                @foreach (['beneficiu_1', 'beneficiu_2', 'beneficiu_3'] as $field)
                  <span class="upgrade_pack_feature">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                    {{ get_sub_field($field) }}
                  </span>
                @endforeach
              </div>
            @endwhile
          @endif

          <div class="upgrade_pack_prices">
            @if ($upgrade_sale_price)
              <span class="upgrade_price_new">{!! wc_price($upgrade_sale_price) !!}</span>
              <span class="upgrade_price_old">{!! wc_price($upgrade_regular_price) !!}</span>
              @if ($savings_percent > 0)
                <span class="upgrade_pack_savings">-{{ $savings_percent }}%</span>
              @endif
            @else
              <span class="upgrade_price_new">{!! wc_price($upgrade_product->get_price()) !!}</span>
            @endif
          </div>
        </div>
        <div class="upgrade_pack_action">
          @php
            $upgrade_classes = ['btn-primary', 'upgrade_add_btn', 'button', 'product_type_' . $upgrade_product->get_type()];
            $upgrade_ajax = $upgrade_product->is_purchasable()
                && $upgrade_product->is_in_stock()
                && $upgrade_product->supports('ajax_add_to_cart');
            if ($upgrade_ajax) {
                $upgrade_classes[] = 'add_to_cart_button';
                $upgrade_classes[] = 'ajax_add_to_cart';
            }
            woocommerce_template_loop_add_to_cart(['class' => implode(' ', $upgrade_classes)], $upgrade_product);
          @endphp
        </div>
      </div>
    </div>
  @endif

  @php do_action('woocommerce_after_single_product_summary') @endphp
</div>

@php do_action('woocommerce_after_single_product') @endphp

@php
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
  $sticky_unavailable_text = $product->get_type() === 'bundle' ? 'Pachet indisponibil' : 'Stoc epuizat';
@endphp

<div class="sticky_price_container" id="sticky-price-container">
  <div class="sticky-price container">
    <div class="sticky-product-wrapper">
      @if ($product_image)
        <img class="sticky-product-img" src="{{ esc_url($product_image[0]) }}" alt="{{ esc_attr($product->get_name()) }}">
      @endif
      <div class="sticky_product_name_wrapper">
        <div class="sticky_product_name">{{ esc_html($product->get_name()) }}</div>
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
            {{ esc_html($sticky_unavailable_text) }}
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
