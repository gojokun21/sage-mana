<?php

/**
 * Cart page: backend logic ported from mana-naturii.
 *
 * Covers:
 *   - Free-shipping threshold (const FREE_SHIPPING_MIN).
 *   - AJAX coupon apply/remove (single AJAX router: `natura_cart`).
 *   - Upsell discount system: the first product added from the cart
 *     recommendations slider gets ACF `cart_upsell_discount` (default 10%).
 *     Keeps legacy cart-item flag `is_cart_upsell` and meta key
 *     `_cart_upsell_discount` for compatibility with existing orders.
 *   - Recommended products resolver (ACF `recomandari_produs.recommended_products`).
 *   - Moves WC cross-sells out of cart-collaterals, into after-cart-table,
 *     matching the legacy layout.
 */

namespace App;

use Illuminate\Support\Facades\View;

const FREE_SHIPPING_MIN = 400;
const UPSELL_NONCE = 'mn_upsell_discount';
const UPSELL_DEFAULT_PERCENT = 10;

/* ---------------------------------------------------------------------------
 * Helpers
 * ------------------------------------------------------------------------- */

function cart_upsell_percent(): float
{
    $v = function_exists('get_field') ? get_field('cart_upsell_discount', 'option') : null;

    return ($v && (float) $v > 0) ? (float) $v : (float) UPSELL_DEFAULT_PERCENT;
}

function cart_has_upsell_product(): bool
{
    if (! function_exists('WC') || ! WC()->cart) {
        return false;
    }

    foreach (WC()->cart->get_cart() as $cart_item) {
        if (! empty($cart_item['is_cart_upsell'])) {
            return true;
        }
    }

    return false;
}

/**
 * Build the recommended-products list for the current cart.
 *
 * Collects ACF `recomandari_produs.recommended_products` from every cart item,
 * filters out products already in cart (including parents and variations),
 * sorts by price desc, limits to 10.
 *
 * @return \WC_Product[]
 */
function cart_recommended_products(int $limit = 10): array
{
    if (! function_exists('WC') || ! WC()->cart || WC()->cart->is_empty()) {
        return [];
    }

    $cart_items = WC()->cart->get_cart();
    $ids_in_cart = [];

    foreach ($cart_items as $item) {
        $p = $item['data'];
        $ids_in_cart[] = $p->get_id();

        if (! empty($item['product_id'])) {
            $ids_in_cart[] = (int) $item['product_id'];
        }
        if (! empty($item['variation_id'])) {
            $ids_in_cart[] = (int) $item['variation_id'];
        }
        if ($parent = $p->get_parent_id()) {
            $ids_in_cart[] = (int) $parent;
        }
    }

    $ids_in_cart = array_unique($ids_in_cart);
    $recommended = [];

    foreach ($cart_items as $item) {
        if (! function_exists('get_field')) {
            break;
        }

        $group = get_field('recomandari_produs', (int) $item['product_id']);
        if (empty($group['recommended_products']) || ! is_array($group['recommended_products'])) {
            continue;
        }

        foreach ($group['recommended_products'] as $row) {
            $rec = $row['product'] ?? null;
            if (! $rec) {
                continue;
            }

            $rec_id = is_object($rec) ? (int) $rec->ID : (int) $rec;

            if (in_array($rec_id, $ids_in_cart, true) || isset($recommended[$rec_id])) {
                continue;
            }

            $product = wc_get_product($rec_id);
            if ($product && $product->is_visible() && $product->is_in_stock()) {
                $recommended[$rec_id] = $product;
            }
        }
    }

    uasort($recommended, fn ($a, $b) => (float) $b->get_price() <=> (float) $a->get_price());

    return array_slice($recommended, 0, $limit, true);
}

/* ---------------------------------------------------------------------------
 * Upsell discount — filters
 * ------------------------------------------------------------------------- */

/**
 * Mark a cart item as an upsell when added with `upsell_discount=1` + valid nonce,
 * but only if the cart has no upsell product yet.
 */
add_filter('woocommerce_add_cart_item_data', function ($cart_item_data, $product_id, $variation_id) {
    if (($_REQUEST['upsell_discount'] ?? '') !== '1') {
        return $cart_item_data;
    }

    $nonce = isset($_REQUEST['_upsell_nonce']) ? sanitize_text_field(wp_unslash($_REQUEST['_upsell_nonce'])) : '';
    if (! wp_verify_nonce($nonce, UPSELL_NONCE)) {
        return $cart_item_data;
    }

    if (cart_has_upsell_product()) {
        return $cart_item_data;
    }

    // Remove any existing non-upsell entry for the same product/variation so we
    // don't end up with two rows reducing stock twice.
    if (function_exists('WC') && WC()->cart) {
        $target_id = $variation_id ?: $product_id;
        foreach (WC()->cart->get_cart() as $key => $existing) {
            $item_id = ! empty($existing['variation_id']) ? (int) $existing['variation_id'] : (int) $existing['product_id'];
            if ($item_id === (int) $target_id && empty($existing['is_cart_upsell'])) {
                WC()->cart->remove_cart_item($key);
                break;
            }
        }
    }

    $cart_item_data['is_cart_upsell'] = true;

    return $cart_item_data;
}, 10, 3);

/**
 * Apply the discount to upsell items at calculation time. For qty > 1 we
 * compute a blended per-unit price so only the first unit is discounted.
 */
add_action('woocommerce_before_calculate_totals', function ($cart) {
    if (is_admin() && ! defined('DOING_AJAX')) {
        return;
    }
    if (did_action('woocommerce_before_calculate_totals') >= 2) {
        return;
    }

    // If the upsell item is the only thing left in the cart, drop the flag —
    // the discount is only meaningful alongside another product.
    $has_non_upsell = false;
    foreach ($cart->get_cart() as $item) {
        if (empty($item['is_cart_upsell'])) {
            $has_non_upsell = true;
            break;
        }
    }

    if (! $has_non_upsell) {
        foreach ($cart->get_cart() as $key => $item) {
            if (! empty($item['is_cart_upsell'])) {
                $cart->cart_contents[$key]['is_cart_upsell'] = false;
            }
        }

        return;
    }

    $percent = cart_upsell_percent();

    foreach ($cart->get_cart() as $cart_item) {
        if (empty($cart_item['is_cart_upsell'])) {
            continue;
        }

        $product = $cart_item['data'];
        $current = (float) $product->get_price();
        $qty = (int) $cart_item['quantity'];

        if ($qty <= 1) {
            $final = $current * (1 - $percent / 100);
        } else {
            $discounted_single = $current * (1 - $percent / 100);
            $full_total = $current * ($qty - 1);
            $final = ($discounted_single + $full_total) / $qty;
        }

        $product->set_price(round($final, 2));
    }
}, 20, 1);

/**
 * Cart line item meta: small "-X% la prima bucată" badge.
 */
add_filter('woocommerce_get_item_data', function ($item_data, $cart_item) {
    if (empty($cart_item['is_cart_upsell'])) {
        return $item_data;
    }

    $percent = cart_upsell_percent();
    $item_data[] = [
        'key' => __('Reducere', 'sage'),
        'value' => '<span class="upsell-discount-badge">-' . esc_html((string) $percent) . '% ' . esc_html__('la prima bucată', 'sage') . '</span>',
    ];

    return $item_data;
}, 10, 2);

/**
 * Show the original (non-blended) price in the cart line "price" column,
 * so customers see the base price with the discount rendered as a badge/meta.
 */
add_filter('woocommerce_cart_item_price', function ($price, $cart_item, $cart_item_key) {
    if (empty($cart_item['is_cart_upsell'])) {
        return $price;
    }

    $original = wc_get_product((int) $cart_item['product_id']);
    if ($original) {
        return wc_price($original->get_price());
    }

    return $price;
}, 10, 3);

/**
 * Persist the upsell percentage into order line-item meta at checkout.
 */
add_action('woocommerce_checkout_create_order_line_item', function ($item, $cart_item_key, $values, $order) {
    if (empty($values['is_cart_upsell'])) {
        return;
    }

    $percent = cart_upsell_percent();
    $item->add_meta_data('_cart_upsell_discount', $percent . '%', true);
}, 10, 4);

/* ---------------------------------------------------------------------------
 * Cross-sells layout: move out of cart-collaterals, into after-cart-table
 * ------------------------------------------------------------------------- */

add_action('init', function () {
    remove_action('woocommerce_cart_collaterals', 'woocommerce_cross_sell_display');
    add_action('woocommerce_after_cart_table', 'woocommerce_cross_sell_display');
});

/* ---------------------------------------------------------------------------
 * AJAX (coupon apply / remove / refresh fragments)
 * ------------------------------------------------------------------------- */

add_action('wp_enqueue_scripts', function () {
    add_action('wp_footer', function () {
        if (! function_exists('is_cart') || ! is_cart()) {
            return;
        }

        echo '<script>var natura_cart = ' . wp_json_encode([
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('natura_cart_nonce'),
            'cart_url' => function_exists('wc_get_cart_url') ? wc_get_cart_url() : home_url('/cos/'),
            'i18n' => [
                'empty_code' => __('Vă rugăm să introduceți un cod de cupon.', 'sage'),
                'applied' => __('Cuponul "%s" a fost aplicat cu succes!', 'sage'),
                'removed' => __('Cuponul "%s" a fost șters.', 'sage'),
                'applying' => __('Se aplică...', 'sage'),
                'error' => __('A apărut o eroare. Vă rugăm să încercați din nou.', 'sage'),
                'removing' => __('Se șterge...', 'sage'),
            ],
        ]) . ';</script>';
    }, 5);
});

add_action('wp_ajax_natura_cart', __NAMESPACE__ . '\\cart_handler');
add_action('wp_ajax_nopriv_natura_cart', __NAMESPACE__ . '\\cart_handler');

/**
 * Build the HTML fragments the AJAX response swaps on the cart page.
 *
 * @return array{
 *   is_empty: bool,
 *   count: int,
 *   items_html: string,
 *   totals_html: string,
 *   shipping_html: string,
 * }
 */
function cart_fragments(): array
{
    $cart = WC()->cart;
    $is_empty = $cart->is_empty();

    if ($is_empty) {
        return [
            'is_empty' => true,
            'count' => 0,
            'items_html' => '',
            'totals_html' => '',
            'shipping_html' => '',
        ];
    }

    $subtotal = (float) $cart->get_subtotal();
    $missing = max(0, FREE_SHIPPING_MIN - $subtotal);
    $applied = $cart->get_applied_coupons();

    return [
        'is_empty' => false,
        'count' => (int) $cart->get_cart_contents_count(),
        'items_html' => View::make('partials.cart.cart-items')->render(),
        'totals_html' => View::make('woocommerce.cart.cart-totals')->render(),
        'shipping_html' => View::make('partials.cart.free-shipping-box', ['missing' => $missing])->render(),
        'coupon_html' => View::make('partials.cart.coupon-form', [
            'has_coupon' => ! empty($applied),
            'applied_coupon' => $applied[0] ?? '',
        ])->render(),
    ];
}

function cart_handler(): void
{
    check_ajax_referer('natura_cart_nonce', 'nonce');

    if (! function_exists('WC') || ! WC()->cart) {
        wp_send_json_error(['message' => __('Coșul este indisponibil.', 'sage')]);
    }

    $op = isset($_POST['op']) ? sanitize_key(wp_unslash($_POST['op'])) : '';
    $code = isset($_POST['coupon_code']) ? wc_format_coupon_code(wp_unslash($_POST['coupon_code'])) : '';

    try {
        switch ($op) {
            case 'apply_coupon':
                if (! $code) {
                    wp_send_json_error(['message' => __('Vă rugăm să introduceți un cod de cupon.', 'sage')]);
                }

                if (WC()->cart->has_discount($code)) {
                    wp_send_json_error(['message' => __('Acest cupon este deja aplicat.', 'sage')]);
                }

                foreach (WC()->cart->get_applied_coupons() as $existing) {
                    WC()->cart->remove_coupon($existing);
                }
                wc_clear_notices();

                if (! WC()->cart->apply_coupon($code)) {
                    $notices = wc_get_notices('error');
                    wc_clear_notices();

                    $raw = ! empty($notices) ? ($notices[0]['notice'] ?? '') : '';
                    $msg = $raw
                        ? html_entity_decode(wp_strip_all_tags($raw), ENT_QUOTES, 'UTF-8')
                        : __('Cuponul nu a putut fi aplicat.', 'sage');

                    wp_send_json_error(['message' => $msg]);
                }

                wp_send_json_success(array_merge(
                    [
                        'message' => sprintf(__('Cuponul "%s" a fost aplicat cu succes!', 'sage'), strtoupper($code)),
                        'coupon_code' => strtoupper($code),
                    ],
                    cart_fragments()
                ));

                break;

            case 'remove_coupon':
                if (! $code) {
                    wp_send_json_error(['message' => __('Cuponul nu a putut fi șters.', 'sage')]);
                }

                WC()->cart->remove_coupon($code);
                wc_clear_notices();

                wp_send_json_success(array_merge(
                    ['message' => sprintf(__('Cuponul "%s" a fost șters.', 'sage'), strtoupper($code))],
                    cart_fragments()
                ));

                break;

            case 'update_qty':
                $key = isset($_POST['key']) ? sanitize_text_field(wp_unslash($_POST['key'])) : '';
                $qty = isset($_POST['qty']) ? (int) $_POST['qty'] : -1;

                if (! $key || ! isset(WC()->cart->cart_contents[$key])) {
                    wp_send_json_error(['message' => __('Produs inexistent în coș.', 'sage')]);
                }

                if ($qty <= 0) {
                    WC()->cart->remove_cart_item($key);
                } else {
                    WC()->cart->set_quantity($key, $qty, true);
                }

                WC()->cart->calculate_totals();

                wp_send_json_success(cart_fragments());

                break;

            case 'remove_item':
                $key = isset($_POST['key']) ? sanitize_text_field(wp_unslash($_POST['key'])) : '';
                if (! $key || ! isset(WC()->cart->cart_contents[$key])) {
                    wp_send_json_error(['message' => __('Produs inexistent în coș.', 'sage')]);
                }

                WC()->cart->remove_cart_item($key);
                WC()->cart->calculate_totals();

                wp_send_json_success(cart_fragments());

                break;

            case 'get':
                // Fresh fragments only — no mutation. Used to re-sync the cart
                // page after the mini-cart (or any other source) changed the cart.
                WC()->cart->calculate_totals();
                wp_send_json_success(cart_fragments());

                break;

            default:
                wp_send_json_error(['message' => __('Operație necunoscută.', 'sage')]);
        }
    } catch (\Throwable $e) {
        wp_send_json_error(['message' => $e->getMessage()]);
    }
}
