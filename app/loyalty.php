<?php

/**
 * Loyalty „Cod fidelitate" — My Account tab (presentation only).
 *
 * The points engine lives in the `mn-loyalty` plugin (MN_Loyalty* classes). This
 * file just registers the WooCommerce account endpoint, adds the nav item, and
 * renders the Blade view with data from the plugin's MN_Loyalty_Account provider.
 */

namespace App;

use Illuminate\Support\Facades\View;

const LOYALTY_ENDPOINT = 'cod-fidelitate';
const LOYALTY_EP_VERSION = '1';

/* ---------------------------------------------------------------------------
 * Endpoint registration + one-time rewrite flush.
 * ------------------------------------------------------------------------- */
add_action('init', function () {
    add_rewrite_endpoint(LOYALTY_ENDPOINT, EP_ROOT | EP_PAGES);

    // Flush once when the plugin asks (on activation) or when our endpoint
    // version changes — never on every request.
    $needs_flush = get_option('mn_loyalty_flush_needed') === '1'
        || get_option('mn_loyalty_ep_version') !== LOYALTY_EP_VERSION;

    if ($needs_flush) {
        flush_rewrite_rules();
        update_option('mn_loyalty_ep_version', LOYALTY_EP_VERSION);
        update_option('mn_loyalty_flush_needed', '0');
    }
}, 10);

// Re-flush after a theme switch (endpoint must be re-registered).
add_action('after_switch_theme', function () {
    add_rewrite_endpoint(LOYALTY_ENDPOINT, EP_ROOT | EP_PAGES);
    flush_rewrite_rules();
});

/* ---------------------------------------------------------------------------
 * Account menu item — inserted right after „Comenzi".
 * ------------------------------------------------------------------------- */
add_filter('woocommerce_account_menu_items', function ($items) {
    // Only surface the tab when the loyalty engine (plugin) is active.
    if (! is_array($items) || ! class_exists('MN_Loyalty')) {
        return $items;
    }

    $new = [];
    foreach ($items as $key => $label) {
        $new[$key] = $label;
        if ($key === 'orders') {
            $new[LOYALTY_ENDPOINT] = __('Cod fidelitate', 'sage');
        }
    }

    // Fallback: if there's no `orders` item, append before logout.
    if (! isset($new[LOYALTY_ENDPOINT])) {
        $logout = $new['customer-logout'] ?? null;
        unset($new['customer-logout']);
        $new[LOYALTY_ENDPOINT] = __('Cod fidelitate', 'sage');
        if ($logout !== null) {
            $new['customer-logout'] = $logout;
        }
    }

    return $new;
}, 20);

/* ---------------------------------------------------------------------------
 * Endpoint content.
 * ------------------------------------------------------------------------- */
add_action('woocommerce_account_' . LOYALTY_ENDPOINT . '_endpoint', function () {
    if (! class_exists('MN_Loyalty_Account')) {
        echo '<div class="woocommerce-info">'
            . esc_html__('Programul de fidelitate este momentan indisponibil.', 'sage')
            . '</div>';
        return;
    }

    $data = \MN_Loyalty_Account::data(get_current_user_id());

    echo View::make('woocommerce.myaccount.cod-fidelitate', $data)->render();
});
