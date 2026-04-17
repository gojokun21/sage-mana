{{--
  Cart table rows (<tr> list). Rendered both by cart.blade.php on initial load
  and by the AJAX update endpoint to refresh the tbody after qty changes.
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
  @endphp

  <tr class="woocommerce-cart-form__cart-item {{ esc_attr($row_class) }}" data-cart-item-key="{{ $cart_item_key }}">
    <td class="product-thumbnail">
      @if ($product_permalink)
        <a href="{{ esc_url($product_permalink) }}">{!! $thumbnail !!}</a>
      @else
        {!! $thumbnail !!}
      @endif
    </td>

    <td class="product-name" data-title="{{ esc_attr__('Product', 'woocommerce') }}">
      @if ($product_permalink)
        {!! apply_filters('woocommerce_cart_item_name', sprintf('<a href="%s">%s</a>', esc_url($product_permalink), $_product->get_name()), $cart_item, $cart_item_key) !!}
      @else
        {!! wp_kses_post($product_name . '&nbsp;') !!}
      @endif

      @php do_action('woocommerce_after_cart_item_name', $cart_item, $cart_item_key) @endphp

      {!! wc_get_formatted_cart_item_data($cart_item) !!}

      @if ($_product->backorders_require_notification() && $_product->is_on_backorder($cart_item['quantity']))
        {!! apply_filters('woocommerce_cart_item_backorder_notification', '<p class="backorder_notification">' . esc_html__('Available on backorder', 'woocommerce') . '</p>', $product_id) !!}
      @endif
    </td>

    <td class="product-price" data-title="{{ esc_attr__('Price', 'woocommerce') }}">
      {!! apply_filters('woocommerce_cart_item_price', WC()->cart->get_product_price($_product), $cart_item, $cart_item_key) !!}
    </td>

    <td class="product-quantity" data-title="{{ esc_attr__('Quantity', 'woocommerce') }}">
      {!! apply_filters('woocommerce_cart_item_quantity', $qty_input, $cart_item_key, $cart_item) !!}
    </td>

    <td class="product-subtotal" data-title="{{ esc_attr__('Subtotal', 'woocommerce') }}">
      {!! apply_filters('woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal($_product, $cart_item['quantity']), $cart_item, $cart_item_key) !!}
    </td>

    <td class="product-remove">
      {!! apply_filters(
        'woocommerce_cart_item_remove_link',
        sprintf(
          '<a role="button" href="%s" class="remove" aria-label="%s" data-product_id="%s" data-product_sku="%s" data-cart-item-remove="%s">&times;</a>',
          esc_url(wc_get_cart_remove_url($cart_item_key)),
          esc_attr(sprintf(__('Remove %s from cart', 'woocommerce'), wp_strip_all_tags($product_name))),
          esc_attr($product_id),
          esc_attr($_product->get_sku()),
          esc_attr($cart_item_key)
        ),
        $cart_item_key
      ) !!}
    </td>
  </tr>
@endforeach
