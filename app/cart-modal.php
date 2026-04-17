<?php

/**
 * Add-to-cart confirmation modal.
 *
 * Ported from mana-naturii (see functions.php `#ml-cart-modal` + `ml_cart_data`
 * fragment). Opens on WC's `added_to_cart` JS event, showing the last product
 * plus a free-shipping progress line.
 *
 * Pairs with `resources/js/cart-modal.js` and `resources/views/partials/cart-modal.blade.php`.
 */

namespace App;

use Illuminate\Support\Facades\View;

/**
 * Render modal markup once in the footer.
 */
add_action('wp_footer', function () {
    echo View::make('partials.cart-modal')->render();
});

/**
 * Inject free-shipping + last-product data into the WC AJAX fragments so the
 * JS can populate the modal after `added_to_cart` fires (covers bundles and
 * form submits where the button lacks `data-product_*` attrs).
 */
add_filter('woocommerce_add_to_cart_fragments', function (array $fragments): array {
    if (! function_exists('WC') || ! WC()->cart) {
        return $fragments;
    }

    $free_min = defined(__NAMESPACE__ . '\\FREE_SHIPPING_MIN')
        ? FREE_SHIPPING_MIN
        : 400;

    $subtotal = (float) WC()->cart->get_subtotal();
    $missing  = max(0, $free_min - $subtotal);

    // Last-added item snapshot — bundles submit a form, so button data-attrs
    // aren't always available.
    $last_product = null;
    $contents = WC()->cart->get_cart();
    if (! empty($contents)) {
        $last_item = end($contents);
        $product = wc_get_product($last_item['product_id'] ?? 0);

        if ($product) {
            $image_id = $product->get_image_id();
            $image_url = $image_id
                ? wp_get_attachment_image_url($image_id, 'medium')
                : wc_placeholder_img_src('medium');

            $packaging = wp_strip_all_tags((string) $product->get_short_description());
            $packaging = mb_substr($packaging, 0, 140);

            $last_product = [
                'name'      => $product->get_name(),
                'url'       => get_permalink($product->get_id()),
                'image'     => $image_url,
                'packaging' => $packaging,
            ];
        }
    }

    $fragments['ml_cart_data'] = wp_json_encode([
        'free_min'     => $free_min,
        'total'        => $subtotal,
        'missing'      => round($missing, 2),
        'last_product' => $last_product,
    ]);

    return $fragments;
});
