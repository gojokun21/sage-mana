<?php

/**
 * Custom Mini Cart (WooCommerce drawer).
 *
 * Exposes a single AJAX endpoint `natura_mini_cart` that handles
 * get / add / remove / update operations and always responds with
 * the rendered fragment + count + subtotal.
 */

namespace App;

use Illuminate\Support\Facades\View;

/**
 * Localize endpoint data for the frontend script.
 */
add_action('wp_enqueue_scripts', function () {
    add_action('wp_footer', function () {
        echo '<script>var natura_mini_cart = ' . wp_json_encode([
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('natura_mini_cart_nonce'),
            'cart_url' => function_exists('wc_get_cart_url') ? wc_get_cart_url() : home_url('/cos/'),
            'checkout_url' => function_exists('wc_get_checkout_url') ? wc_get_checkout_url() : home_url('/finalizare-comanda/'),
            'i18n' => [
                'error' => __('A apărut o eroare. Încearcă din nou.', 'sage'),
                'removing' => __('Se elimină...', 'sage'),
                'updating' => __('Se actualizează...', 'sage'),
            ],
        ]) . ';</script>';
    }, 5);
});

/**
 * AJAX router.
 */
add_action('wp_ajax_natura_mini_cart', __NAMESPACE__ . '\\mini_cart_handler');
add_action('wp_ajax_nopriv_natura_mini_cart', __NAMESPACE__ . '\\mini_cart_handler');

function mini_cart_handler(): void
{
    check_ajax_referer('natura_mini_cart_nonce', 'nonce');

    if (! function_exists('WC') || ! WC()->cart) {
        wp_send_json_error(['message' => 'WooCommerce unavailable']);
    }

    $cart = WC()->cart;
    $op = isset($_POST['op']) ? sanitize_key(wp_unslash($_POST['op'])) : 'get';

    try {
        switch ($op) {
            case 'add':
                $product_id = isset($_POST['product_id']) ? absint($_POST['product_id']) : 0;
                $qty = isset($_POST['qty']) ? max(1, absint($_POST['qty'])) : 1;
                $variation_id = isset($_POST['variation_id']) ? absint($_POST['variation_id']) : 0;
                $variation = isset($_POST['variation']) && is_array($_POST['variation'])
                    ? array_map('sanitize_text_field', wp_unslash($_POST['variation']))
                    : [];

                if (! $product_id) {
                    wp_send_json_error(['message' => __('Produs invalid', 'sage')]);
                }

                // Pre-check bundle aggregate stock explicitly — WC's filter
                // chain can be bypassed by third-party plugins when the
                // bundle itself doesn't manage stock, so we run the same
                // logic here before add_to_cart() runs.
                if (function_exists(__NAMESPACE__ . '\\bundle_cart_available')) {
                    $product_for_check = wc_get_product($product_id);
                    if ($product_for_check) {
                        $available = bundle_cart_available($product_for_check);
                        if ($available !== null && $qty > $available) {
                            $message = $available > 0
                                ? sprintf(
                                    __('Poți adăuga maxim %1$d bucăți din "%2$s". Stoc disponibil: %1$d.', 'sage'),
                                    $available,
                                    $product_for_check->get_name()
                                )
                                : sprintf(
                                    __('"%s" este deja în coș la cantitatea maximă disponibilă.', 'sage'),
                                    $product_for_check->get_name()
                                );
                            wp_send_json_error(['message' => $message]);
                        }
                    }
                }

                $added = $cart->add_to_cart($product_id, $qty, $variation_id, $variation);
                if (! $added) {
                    $notices = wc_get_notices('error');
                    wc_clear_notices();

                    $raw = ! empty($notices)
                        ? ($notices[0]['notice'] ?? '')
                        : '';
                    $message = $raw
                        ? html_entity_decode(wp_strip_all_tags($raw), ENT_QUOTES, 'UTF-8')
                        : __('Nu s-a putut adăuga produsul', 'sage');

                    wp_send_json_error(['message' => $message]);
                }
                break;

            case 'remove':
                $key = isset($_POST['key']) ? sanitize_text_field(wp_unslash($_POST['key'])) : '';
                if ($key && isset($cart->cart_contents[$key])) {
                    $cart->remove_cart_item($key);
                }
                break;

            case 'update':
                $key = isset($_POST['key']) ? sanitize_text_field(wp_unslash($_POST['key'])) : '';
                $qty = isset($_POST['qty']) ? absint($_POST['qty']) : 0;
                if ($key && isset($cart->cart_contents[$key])) {
                    if ($qty <= 0) {
                        $cart->remove_cart_item($key);
                    } else {
                        $values = $cart->cart_contents[$key];
                        $product_for_check = $values['data'] ?? null;

                        // Explicit bundle-stock pre-check for updates (same
                        // reasoning as the 'add' branch above).
                        if ($product_for_check instanceof \WC_Product
                            && function_exists(__NAMESPACE__ . '\\bundle_cart_available')) {
                            $existing_qty = (int) ($values['quantity'] ?? 0);
                            $available = bundle_cart_available($product_for_check, $existing_qty);
                            if ($available !== null && $qty > $available) {
                                wp_send_json_error([
                                    'message' => sprintf(
                                        __('Cantitatea maximă disponibilă pentru "%1$s" este %2$d.', 'sage'),
                                        $product_for_check->get_name(),
                                        $available
                                    ),
                                ]);
                            }
                        }

                        $passed = apply_filters('woocommerce_update_cart_validation', true, $key, $values, $qty);
                        if (! $passed) {
                            $notices = wc_get_notices('error');
                            wc_clear_notices();
                            $raw = ! empty($notices) ? ($notices[0]['notice'] ?? '') : '';
                            $message = $raw
                                ? html_entity_decode(wp_strip_all_tags($raw), ENT_QUOTES, 'UTF-8')
                                : __('Cantitatea nu poate fi actualizată.', 'sage');
                            wp_send_json_error(['message' => $message]);
                        }
                        $cart->set_quantity($key, $qty, true);
                    }
                }
                break;

            case 'get':
            default:
                break;
        }

        $cart->calculate_totals();

        wp_send_json_success(mini_cart_payload());
    } catch (\Throwable $e) {
        wp_send_json_error(['message' => $e->getMessage()]);
    }
}

/**
 * Build the full payload consumed by the frontend.
 *
 * @return array{html:string,count:int,subtotal:string,is_empty:bool}
 */
function mini_cart_payload(): array
{
    $cart = WC()->cart;

    return [
        'html' => View::make('partials.mini-cart-items')->render(),
        'count' => (int) $cart->get_cart_contents_count(),
        'subtotal' => $cart->get_cart_subtotal(),
        'is_empty' => $cart->is_empty(),
    ];
}
