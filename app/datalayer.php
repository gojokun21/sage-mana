<?php

/**
 * GA4 dataLayer — add_to_cart event.
 *
 * Three add-to-cart paths in this theme; each gets its own delivery channel:
 *
 *   1. Non-AJAX form POST (product page reload) →
 *        woocommerce_add_to_cart action stores the payload in WC session,
 *        wp_head flushes it on the next render and clears the marker.
 *
 *   2. WC standard AJAX (`?wc-ajax=add_to_cart`) →
 *        woocommerce_add_to_cart_fragments injects `mn_atc_dl_payload`,
 *        which datalayer-atc.js reads from the `added_to_cart` event args.
 *
 *   3. Custom endpoint NaturaMiniCart.add (admin-ajax) →
 *        no fragments are emitted; datalayer-atc.js falls back to the
 *        button's `data-product_*` attrs + DOM-scraped price.
 *
 * The non-AJAX session push is gated on !is_ajax_add_to_cart() so AJAX
 * adds don't leave a stale session entry that fires on the next reload.
 */

namespace App;

use WC_Product;

/**
 * @internal
 */
function is_ajax_add_to_cart(): bool
{
    if (function_exists('wp_doing_ajax') && wp_doing_ajax()) {
        return true;
    }

    return ! empty($_REQUEST['wc-ajax']);
}

/**
 * Resolve a product's brand. Tries WC Brands taxonomy first (`product_brand`),
 * then the custom attribute `pa_brand`, then falls back to the site name.
 * Result is filterable via `mn_ga4_item_brand` at the call sites below.
 */
function resolve_product_brand(WC_Product $product): string
{
    if (taxonomy_exists('product_brand')) {
        $terms = wp_get_post_terms($product->get_id(), 'product_brand', ['fields' => 'names']);
        if (! is_wp_error($terms) && ! empty($terms)) {
            return (string) $terms[0];
        }
    }

    $brand_attr = (string) $product->get_attribute('pa_brand');
    if ($brand_attr !== '') {
        $first = trim((string) explode(',', $brand_attr)[0]);
        if ($first !== '') {
            return $first;
        }
    }

    return (string) get_bloginfo('name');
}

/**
 * Resolve a product's primary category name. Returns null when none is set.
 */
function resolve_product_category(WC_Product $product): ?string
{
    $terms = wp_get_post_terms($product->get_id(), 'product_cat', ['fields' => 'names']);
    if (is_wp_error($terms) || empty($terms)) {
        return null;
    }

    return (string) $terms[0];
}

/**
 * Build the GA4 `add_to_cart` payload for a single product line.
 */
function ga4_atc_payload(WC_Product $product, int $qty, ?int $variation_id = null, array $variation = []): array
{
    $price = (float) $product->get_price();
    $sku = $product->get_sku();

    $item = [
        'item_id' => $sku !== '' ? $sku : (string) $product->get_id(),
        'item_name' => $product->get_name(),
        'item_brand' => apply_filters('mn_ga4_item_brand', resolve_product_brand($product), $product),
        'price' => round($price, 2),
        'quantity' => max(1, $qty),
    ];

    $category = apply_filters('mn_ga4_item_category', resolve_product_category($product), $product);
    if (! empty($category)) {
        $item['item_category'] = (string) $category;
    }

    if ($variation_id && ! empty($variation)) {
        $bits = array_filter(array_map(static fn ($v) => trim((string) $v), $variation));
        if (! empty($bits)) {
            $item['item_variant'] = implode(' / ', $bits);
        }
    }

    return [
        'event' => 'add_to_cart',
        'ecommerce' => [
            'currency' => function_exists('get_woocommerce_currency') ? get_woocommerce_currency() : 'RON',
            'value' => round($price * max(1, $qty), 2),
            'items' => [$item],
        ],
    ];
}

/**
 * Decorate every WC loop add-to-cart button with the data attrs we need on
 * the JS side — price/brand/category — so bundle and variable buttons (which
 * default to no price attr) carry the same payload as our overridden cards.
 * Acts as a catch-all; templates we control set the same attrs inline.
 */
add_filter('woocommerce_loop_add_to_cart_args', function ($args, $product) {
    if (! $product instanceof WC_Product) {
        return $args;
    }

    $args['attributes'] = isset($args['attributes']) && is_array($args['attributes']) ? $args['attributes'] : [];

    $defaults = [
        'data-product_price' => (string) wc_format_decimal($product->get_price(), wc_get_price_decimals()),
        'data-product_name' => $product->get_name(),
        'data-product_brand' => apply_filters('mn_ga4_item_brand', resolve_product_brand($product), $product),
    ];

    $category = apply_filters('mn_ga4_item_category', resolve_product_category($product), $product);
    if (! empty($category)) {
        $defaults['data-product_category'] = (string) $category;
    }

    // Don't clobber attrs WC core or other filters already set.
    $args['attributes'] += $defaults;

    return $args;
}, 10, 2);

/**
 * Expose the configured brand to datalayer-atc.js so the AJAX fallback path
 * (custom mini-cart endpoint, form POST) uses the same brand string as the
 * server-rendered push. Filter `mn_ga4_item_brand` is also applied here so
 * a single override point covers both rails.
 */
add_action('wp_footer', function () {
    $brand = apply_filters('mn_ga4_item_brand', get_bloginfo('name'), null);
    echo '<script>window.mn_ga4 = ' . wp_json_encode([
        'brand' => (string) $brand,
        'currency' => function_exists('get_woocommerce_currency') ? get_woocommerce_currency() : 'RON',
    ]) . ';</script>';
}, 5);

/**
 * Path 1 — non-AJAX form POST. Stash payload for the next page render.
 *
 * @param  string  $cart_item_key
 * @param  int  $product_id
 * @param  int  $quantity
 * @param  int  $variation_id
 * @param  array  $variation
 */
add_action('woocommerce_add_to_cart', function ($cart_item_key, $product_id, $quantity, $variation_id, $variation) {
    if (is_ajax_add_to_cart()) {
        return;
    }
    if (! function_exists('WC') || ! WC()->session) {
        return;
    }

    $product = wc_get_product($variation_id ?: $product_id);
    if (! $product instanceof WC_Product) {
        return;
    }

    WC()->session->set('mn_pending_add_to_cart', ga4_atc_payload(
        $product,
        (int) $quantity,
        $variation_id ? (int) $variation_id : null,
        is_array($variation) ? $variation : []
    ));
}, 10, 5);

/**
 * Path 1 — flush stashed payload server-side and set the JS dedupe marker
 * so any in-flight AJAX listener won't push the same event again.
 */
add_action('wp_head', function () {
    if (! function_exists('WC') || ! WC()->session) {
        return;
    }

    $payload = WC()->session->get('mn_pending_add_to_cart');
    if (! $payload) {
        return;
    }

    WC()->session->set('mn_pending_add_to_cart', null);
    ?>
    <script>
      window.dataLayer = window.dataLayer || [];
      window.dataLayer.push({ ecommerce: null });
      window.dataLayer.push(<?php echo wp_json_encode($payload); ?>);
      window.__mn_atc_just_pushed = true;
      setTimeout(function () { window.__mn_atc_just_pushed = false; }, 1500);
    </script>
    <?php
}, 1);

/**
 * Path 2 — WC standard AJAX. Computed from $_POST (matches WC_AJAX::add_to_cart
 * input shape) so this fires regardless of whether the session hook above ran.
 */
add_filter('woocommerce_add_to_cart_fragments', function (array $fragments): array {
    if (! function_exists('WC') || ! WC()->cart) {
        return $fragments;
    }

    $product_id = isset($_POST['product_id']) ? absint(wp_unslash($_POST['product_id'])) : 0;
    if (! $product_id) {
        return $fragments;
    }

    $variation_id = isset($_POST['variation_id']) ? absint(wp_unslash($_POST['variation_id'])) : 0;
    $quantity = isset($_POST['quantity']) ? max(1, absint(wp_unslash($_POST['quantity']))) : 1;
    $variation = isset($_POST['variation']) && is_array($_POST['variation'])
        ? array_map('sanitize_text_field', wp_unslash($_POST['variation']))
        : [];

    $product = wc_get_product($variation_id ?: $product_id);
    if (! $product instanceof WC_Product) {
        return $fragments;
    }

    $fragments['mn_atc_dl_payload'] = wp_json_encode(ga4_atc_payload(
        $product,
        $quantity,
        $variation_id ?: null,
        $variation
    ));

    return $fragments;
});
