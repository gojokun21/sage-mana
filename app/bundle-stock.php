<?php

/**
 * Bundle stock: expose the plugin-computed bundle stock quantity
 * (WC_Product_Bundle::get_bundle_stock_quantity) as the max purchase
 * quantity when the bundle itself doesn't manage stock. This makes
 * the main qty input, sticky bar, cart and checkout validation all
 * respect the aggregate stock of bundled items.
 */

namespace App;

function bundle_resolved_max($fallback, $product)
{
    if (! $product instanceof \WC_Product || ! $product->is_type('bundle')) {
        return $fallback;
    }

    if ($product->managing_stock()) {
        return $fallback;
    }

    if ($product->backorders_allowed()) {
        return $fallback;
    }

    if (! method_exists($product, 'get_bundle_stock_quantity')) {
        return $fallback;
    }

    $bundle_qty = $product->get_bundle_stock_quantity();

    return $bundle_qty !== '' ? (int) $bundle_qty : $fallback;
}

add_filter('woocommerce_product_get_max_purchase_quantity', __NAMESPACE__ . '\\bundle_resolved_max', 10, 2);

add_filter('woocommerce_quantity_input_max', __NAMESPACE__ . '\\bundle_resolved_max', 10, 2);

/**
 * Validate add-to-cart and cart qty updates against the computed bundle
 * stock. WC core skips these checks when `managing_stock()` is false on
 * the bundle itself, letting users stack multiple adds past the aggregate
 * stock of components.
 */
function bundle_cart_available(\WC_Product $product, int $exclude_cart_key_qty = 0): ?int
{
    if (! $product->is_type('bundle')) return null;
    if ($product->managing_stock() || $product->backorders_allowed()) return null;
    if (! method_exists($product, 'get_bundle_stock_quantity')) return null;

    $max = $product->get_bundle_stock_quantity();
    if ($max === '') return null;

    $max = (int) $max;
    $in_cart = 0;

    if (WC()->cart) {
        foreach (WC()->cart->get_cart() as $item) {
            if ((int) $item['product_id'] === $product->get_id()) {
                $in_cart += (int) $item['quantity'];
            }
        }
    }

    return max(0, $max - $in_cart + $exclude_cart_key_qty);
}

add_filter('woocommerce_add_to_cart_validation', function ($passed, $product_id, $qty) {
    if (! $passed) return $passed;

    $product = wc_get_product($product_id);
    if (! $product) return $passed;

    $available = bundle_cart_available($product);
    if ($available === null) return $passed;

    if ($qty > $available) {
        $message = $available > 0
            ? sprintf(
                __('Poți adăuga maxim %1$d bucăți din "%2$s". Stoc disponibil: %1$d.', 'sage'),
                $available,
                $product->get_name()
            )
            : sprintf(
                __('"%s" este deja în coș la cantitatea maximă disponibilă.', 'sage'),
                $product->get_name()
            );
        wc_add_notice($message, 'error');
        return false;
    }

    return $passed;
}, 10, 3);

add_filter('woocommerce_update_cart_validation', function ($passed, $cart_item_key, $values, $quantity) {
    if (! $passed) return $passed;

    $product = $values['data'] ?? null;
    if (! $product instanceof \WC_Product) return $passed;

    $existing_qty = (int) ($values['quantity'] ?? 0);
    $available = bundle_cart_available($product, $existing_qty);
    if ($available === null) return $passed;

    if ($quantity > $available) {
        wc_add_notice(
            sprintf(
                __('Cantitatea maximă disponibilă pentru "%1$s" este %2$d.', 'sage'),
                $product->get_name(),
                $available
            ),
            'error'
        );
        return false;
    }

    return $passed;
}, 10, 4);

/**
 * Revalidate the whole cart on every cart / checkout load and before order
 * submission. Catches the case where component stock dropped between the
 * moment the bundle was added and checkout. WC core's stock revalidation
 * loop (`check_cart_item_stock`) also gates on `managing_stock()`, so
 * bundles without their own stock slip through without this.
 */
add_action('woocommerce_check_cart_items', function () {
    if (! WC()->cart) return;

    $totals = [];
    $products = [];

    foreach (WC()->cart->get_cart() as $item) {
        $product = $item['data'] ?? null;
        if (! $product instanceof \WC_Product || ! $product->is_type('bundle')) continue;
        if ($product->managing_stock() || $product->backorders_allowed()) continue;
        if (! method_exists($product, 'get_bundle_stock_quantity')) continue;

        $pid = $product->get_id();
        $totals[$pid] = ($totals[$pid] ?? 0) + (int) $item['quantity'];
        $products[$pid] = $product;
    }

    foreach ($totals as $pid => $total) {
        $product = $products[$pid];
        $max = $product->get_bundle_stock_quantity();
        if ($max === '') continue;

        $max = (int) $max;
        if ($total > $max) {
            wc_add_notice(
                sprintf(
                    __('"%1$s": cantitatea din coș (%2$d) depășește stocul disponibil (%3$d). Te rugăm să actualizezi coșul.', 'sage'),
                    $product->get_name(),
                    $total,
                    $max
                ),
                'error'
            );
        }
    }
});
