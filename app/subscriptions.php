<?php

/**
 * Abonamente — My Account tab (presentation only).
 *
 * The subscription engine lives in the `mn-subscriptions` plugin (MN_Subs_*
 * classes). This file registers the WooCommerce account endpoint, adds the nav
 * item, renders the Blade view, and provides the PDP option markup so it matches
 * the design system.
 */

namespace App;

use Illuminate\Support\Facades\View;

const SUBS_ENDPOINT = 'abonamente';
const SUBS_EP_VERSION = '1';

/* ---------------------------------------------------------------------------
 * Endpoint registration + one-time rewrite flush.
 * ------------------------------------------------------------------------- */
add_action('init', function () {
    add_rewrite_endpoint(SUBS_ENDPOINT, EP_ROOT | EP_PAGES);

    $needs_flush = get_option('mn_subs_flush_needed') === 'yes'
        || get_option('mn_subs_ep_version') !== SUBS_EP_VERSION;

    if ($needs_flush) {
        flush_rewrite_rules();
        update_option('mn_subs_ep_version', SUBS_EP_VERSION);
        update_option('mn_subs_flush_needed', 'no');
    }
}, 10);

add_action('after_switch_theme', function () {
    add_rewrite_endpoint(SUBS_ENDPOINT, EP_ROOT | EP_PAGES);
    flush_rewrite_rules();
});

/* ---------------------------------------------------------------------------
 * Account menu item — inserted right after „Comenzi".
 * ------------------------------------------------------------------------- */
add_filter('woocommerce_account_menu_items', function ($items) {
    if (! is_array($items) || ! class_exists('MN_Subs_Subscription')) {
        return $items;
    }

    $new = [];
    foreach ($items as $key => $label) {
        $new[$key] = $label;
        if ($key === 'orders') {
            $new[SUBS_ENDPOINT] = __('Abonamente', 'sage');
        }
    }

    if (! isset($new[SUBS_ENDPOINT])) {
        $logout = $new['customer-logout'] ?? null;
        unset($new['customer-logout']);
        $new[SUBS_ENDPOINT] = __('Abonamente', 'sage');
        if ($logout !== null) {
            $new['customer-logout'] = $logout;
        }
    }

    return $new;
}, 20);

/* ---------------------------------------------------------------------------
 * Endpoint content.
 * ------------------------------------------------------------------------- */
add_action('woocommerce_account_'.SUBS_ENDPOINT.'_endpoint', function () {
    if (! class_exists('MN_Subs_Account')) {
        echo '<div class="woocommerce-info">'
            .esc_html__('Abonamentele sunt momentan indisponibile.', 'sage')
            .'</div>';

        return;
    }

    $data = \MN_Subs_Account::data(get_current_user_id());

    echo View::make('woocommerce.myaccount.abonamente', $data)->render();
});

/* ---------------------------------------------------------------------------
 * PDP purchase-type options — rendered to match the design system.
 * Hooked by the plugin via the `mn_subs_render_pdp_options` action.
 * ------------------------------------------------------------------------- */
add_action('mn_subs_render_pdp_options', function ($data, $product) {
    echo View::make('partials.single-product.subscription-options', [
        'sub' => $data,
        'product' => $product,
    ])->render();
}, 10, 2);
