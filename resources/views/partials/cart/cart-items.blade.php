{{--
  Cart line items — randate ca <article> cards (nu <tr>) după redesign.
  Folosit atât la initial load cât și la AJAX update (natura_cart endpoint).

  Selectoarele/atributele păstrate pentru JS (cart.js):
    - data-cart-item-key   pe fiecare <article>
    - data-cart-item-remove  pe link-ul de șterge
    - .qty-stepper__input  pe input-ul de qty (din woocommerce_quantity_input)
    - .bundle_table_item / .bundled_table_item + .product-name pentru toggle bundle
--}}

@foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item)
  @php
    $_product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
    $product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);
    $product_name = apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key);

    if (! ($_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters('woocommerce_cart_item_visible', true, $cart_item, $cart_item_key))) {
        continue;
    }

    $product_permalink = apply_filters('woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink($cart_item) : '', $cart_item, $cart_item_key);
    $row_class = apply_filters('woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key);
    $thumbnail = apply_filters('woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key);

    if ($_product->is_sold_individually()) {
        $min_qty = 1;
        $max_qty = 1;
    } else {
        $min_qty = 0;
        $max_qty = $_product->get_max_purchase_quantity();
    }

    $qty_input = woocommerce_quantity_input([
        'input_name' => "cart[{$cart_item_key}][qty]",
        'input_value' => $cart_item['quantity'],
        'max_value' => $max_qty,
        'min_value' => $min_qty,
        'product_name' => $product_name,
    ], $_product, false);

    // Categorie principală pentru badge (fallback la nimic dacă lipsește).
    $primary_cat = '';
    $cats = wp_get_post_terms($product_id, 'product_cat');
    if (! is_wp_error($cats) && ! empty($cats)) {
        $primary_cat = $cats[0]->name;
    }

    // Detail line: ACF informatie_generala (forma, doze, vegan etc.) cu fallback la short description.
    $detail_parts = [];
    $info = function_exists('get_field') ? get_field('informatie_generala', $product_id) : null;
    if (is_array($info)) {
        if (! empty($info['forma'])) {
            $detail_parts[] = $info['forma'];
        }
        if (! empty($info['protocol_zile'])) {
            $detail_parts[] = $info['protocol_zile'] . ' ' . __('zile', 'sage');
        }
    }
    $detail_line = implode(' · ', $detail_parts);
    if ($detail_line === '') {
        $sd = wp_strip_all_tags((string) $_product->get_short_description());
        if ($sd !== '') {
            $detail_line = wp_trim_words($sd, 14, '…');
        }
    }

    // Cost-per-day (CPD) — afișat doar dacă există protocol_zile.
    $protocol_zile = (is_array($info) && ! empty($info['protocol_zile'])) ? (int) $info['protocol_zile'] : 0;
    $line_subtotal_raw = (float) $_product->get_price() * (int) $cart_item['quantity'];
    $cpd_amount = 0;
    if ($protocol_zile > 0) {
        $cpd_amount = round($line_subtotal_raw / ($protocol_zile * max(1, (int) $cart_item['quantity'])), 2);
    }

    $fmt_lei = static fn ($v) => number_format_i18n((float) $v, 2) . ' lei';

    $unit_price_html = apply_filters('woocommerce_cart_item_price', WC()->cart->get_product_price($_product), $cart_item, $cart_item_key);
    $line_subtotal_html = apply_filters('woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal($_product, $cart_item['quantity']), $cart_item, $cart_item_key);

    $remove_link_html = apply_filters(
        'woocommerce_cart_item_remove_link',
        sprintf(
            '<a role="button" href="%s" class="cart-item__remove" aria-label="%s" data-product_id="%s" data-product_sku="%s" data-cart-item-remove="%s">' . esc_html__('Șterge', 'sage') . '</a>',
            esc_url(wc_get_cart_remove_url($cart_item_key)),
            esc_attr(sprintf(__('Remove %s from cart', 'woocommerce'), wp_strip_all_tags($product_name))),
            esc_attr($product_id),
            esc_attr($_product->get_sku()),
            esc_attr($cart_item_key)
        ),
        $cart_item_key
    );
  @endphp

  <article class="cart-item woocommerce-cart-form__cart-item {{ esc_attr($row_class) }}"
           data-cart-item-key="{{ $cart_item_key }}">
    <div class="cart-item__img">
      @if ($product_permalink)
        <a href="{{ esc_url($product_permalink) }}">{!! $thumbnail !!}</a>
      @else
        {!! $thumbnail !!}
      @endif
    </div>

    <div class="cart-item__info product-name">
      @if ($primary_cat)
        <span class="cart-item__cat">{{ strtoupper($primary_cat) }}</span>
      @endif

      <h3 class="cart-item__title">
        @if ($product_permalink)
          <a href="{{ esc_url($product_permalink) }}">{{ $_product->get_name() }}</a>
        @else
          {{ $_product->get_name() }}
        @endif
      </h3>

      @php do_action('woocommerce_after_cart_item_name', $cart_item, $cart_item_key) @endphp

      @if ($detail_line)
        <p class="cart-item__detail">{{ $detail_line }}</p>
      @endif

      @php $item_data = wc_get_formatted_cart_item_data($cart_item); @endphp
      @if ($item_data)
        <div class="cart-item__meta">{!! $item_data !!}</div>
      @endif

      @if ($_product->backorders_require_notification() && $_product->is_on_backorder($cart_item['quantity']))
        {!! apply_filters('woocommerce_cart_item_backorder_notification', '<p class="backorder_notification">' . esc_html__('Available on backorder', 'woocommerce') . '</p>', $product_id) !!}
      @endif

      <div class="cart-item__controls">
        <div class="cart-item__qty">
          {!! apply_filters('woocommerce_cart_item_quantity', $qty_input, $cart_item_key, $cart_item) !!}
        </div>

        @if ($cpd_amount > 0 && $protocol_zile > 0)
          <span class="cart-item__cpd">
            <strong>{!! wc_price($cpd_amount) !!}/{{ __('zi', 'sage') }}</strong>
            · {{ $protocol_zile }} {{ __('zile cură', 'sage') }}
          </span>
        @endif

        {!! $remove_link_html !!}
      </div>
    </div>

    <div class="cart-item__price">
      <span class="cart-item__unit">{!! $unit_price_html !!} × {{ (int) $cart_item['quantity'] }}</span>
      <span class="cart-item__subtotal">{!! $line_subtotal_html !!}</span>
    </div>
  </article>
@endforeach
