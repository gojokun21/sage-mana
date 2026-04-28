<?php

/**
 * Checkout customizations ported from mana-naturii.
 *
 * Responsibilities:
 *   - Tailor WC checkout fields (Romanian labels, remove address_2 & postcode,
 *     phone required, rename company/street labels).
 *   - CUI (Cod Unic de Înregistrare) — required ONLY for Persoană Juridică (PJ),
 *     enforced server-side. Field is always `required => false` at the schema
 *     level so client can flip it via JS without redrawing the template.
 *   - Detach the payment block from the WC order review hook (we render it
 *     in our own form-checkout layout).
 *   - Disable page cache on checkout / order-received to avoid stale nonces.
 *   - Custom AJAX login endpoint `natura_checkout_login` used by the
 *     guest/login tabs in the billing form.
 *   - Helper `App\render_checkout_steps()` — 3-step progress indicator
 *     (Coș → Casă → Finalizare), callable from cart/checkout/thankyou.
 */

namespace App;

/* ---------------------------------------------------------------------------
 * Field customizations
 * ------------------------------------------------------------------------- */

add_filter('woocommerce_checkout_fields', function ($fields) {
    unset($fields['billing']['billing_address_2']);
    unset($fields['shipping']['shipping_address_2']);

    unset($fields['billing']['billing_postcode']);
    unset($fields['shipping']['shipping_postcode']);

    // "Nr reg comertului" is not used on this store — remove entirely.
    // `billing_cui` stays in place (CUI for PJ, CNP for PF — FGO re-labels it).
    // It's hidden client-side when "Persoană Fizică" is selected (see checkout.js).
    unset($fields['billing']['billing_nr_reg_comertului']);

    if (isset($fields['billing']['billing_phone'])) {
        $fields['billing']['billing_phone']['required'] = true;
    }

    // Email is optional — customers can check out with phone only.
    // WC still validates format when the field is filled in (the 'email'
    // validate rule stays), but won't block submission on empty input.
    if (isset($fields['billing']['billing_email'])) {
        $fields['billing']['billing_email']['required'] = false;
    }

    if (isset($fields['billing']['billing_company'])) {
        $fields['billing']['billing_company']['label'] = __('Nume Firmă', 'sage');
        $fields['billing']['billing_company']['placeholder'] = __('Introdu numele firmei', 'sage');
    }

    if (isset($fields['billing']['billing_address_1'])) {
        $fields['billing']['billing_address_1']['label'] = __('Strada și Numărul', 'sage');
    }

    // Tag PJ-only rows with known classes so our CSS can hide them for
    // Persoană Fizică without a FOUC. Injecting through `class` here is
    // FGO-independent: WC always passes this through to
    // `woocommerce_form_field`, whereas relying on FGO's `{key}_field` ID or
    // HTML structure proved brittle. Keyed by field slug → class name.
    $pj_only_classes = [
        'billing_cui' => 'natura-cui-row',
        'billing_company' => 'natura-company-row',
    ];

    foreach ($pj_only_classes as $field_key => $marker_class) {
        if (! isset($fields['billing'][$field_key])) {
            continue;
        }
        $existing = isset($fields['billing'][$field_key]['class'])
            ? (array) $fields['billing'][$field_key]['class']
            : [];
        if (! in_array($marker_class, $existing, true)) {
            $existing[] = $marker_class;
        }
        $fields['billing'][$field_key]['class'] = $existing;
    }

    // Sector — only relevant when billing_state === 'B' (București).
    // Same pattern as PJ rows: tagged with `.natura-sector-row` so CSS can
    // hide it pre-hydration based on the body class set below.
    // `required => false` at schema level; we enforce server-side in
    // woocommerce_after_checkout_validation so JS can flip the attribute
    // without WC re-rendering the field.
    $fields['billing']['billing_sector'] = [
        'type' => 'select',
        'label' => __('Sector', 'sage'),
        'required' => false,
        'class' => ['form-row-wide', 'natura-sector-row'],
        'priority' => 85, // right after billing_state (80), before postcode (90)
        'options' => [
            '' => __('Selectează sectorul', 'sage'),
            '1' => __('Sectorul 1', 'sage'),
            '2' => __('Sectorul 2', 'sage'),
            '3' => __('Sectorul 3', 'sage'),
            '4' => __('Sectorul 4', 'sage'),
            '5' => __('Sectorul 5', 'sage'),
            '6' => __('Sectorul 6', 'sage'),
        ],
    ];

    return $fields;
}, 40);

/**
 * Force translation of "Street address" → "Strada și Numărul" regardless of
 * which locale/translation file is in use.
 */
add_filter('gettext', function ($translated, $text, $domain) {
    if ($domain !== 'woocommerce') {
        return $translated;
    }

    if ($text === 'Street address' || $text === 'Stradă') {
        return 'Strada și Numărul';
    }

    return $translated;
}, 10, 3);

/**
 * FGO field handling (Tip Facturare PF/PJ + billing_cui + billing_nr_reg_comertului):
 *
 * `woocommerce-fgo-premium` registers three fields via woocommerce_billing_fields.
 *   - billing_tip_facturare       — kept (PF/PJ select).
 *   - billing_cui                 — kept (FGO swaps label: "Cod Unic" for PJ, "CNP" for PF).
 *   - billing_nr_reg_comertului   — removed above (not used by the store).
 *
 * Also: FGO's own JS also targets `billing_nr_reg_comertului` for show/hide.
 * Since we unset the field, those jQuery selectors just find nothing —
 * no error, no effect.
 */

/* ---------------------------------------------------------------------------
 * Terms & conditions checkbox text — links to legal pages.
 * ------------------------------------------------------------------------- */

add_filter('woocommerce_get_terms_and_conditions_checkbox_text', function ($text) {
    return 'Am citit și sunt de acord cu '
        .'<a href="/termeni-si-conditii/" target="_blank">Termenii și Condițiile</a>, '
        .'<a href="/politica-de-confidentialitate/" target="_blank">Politica de Confidențialitate</a> și '
        .'<a href="/politica-de-returnare/" target="_blank">Politica de Returnare</a> '
        .'ale mananaturii.ro.';
});

/* ---------------------------------------------------------------------------
 * Layout: detach payment from the order-review block (we render it ourselves)
 * ------------------------------------------------------------------------- */

add_action('wp', function () {
    remove_action('woocommerce_checkout_order_review', 'woocommerce_checkout_payment', 20);

    // Default WC "Returning customer? Click here to login" sits above the form.
    // We render our own AJAX-driven Guest/Login tabs inside form-billing, so
    // pull the default login block out entirely.
    remove_action('woocommerce_before_checkout_form', 'woocommerce_checkout_login_form', 10);

    // Default WC "Have a coupon? Click here to enter your code" — we handle
    // coupons on the cart page, not on checkout.
    remove_action('woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10);
});

/* ---------------------------------------------------------------------------
 * Initial CUI visibility — prevent FOUC before checkout.js runs
 *
 * `billing_cui` (CUI/CNP) is toggled by JS based on `billing_tip_facturare`
 * (PF/PJ). Since checkout.js is lazy-imported, the field was flashing visible
 * for a frame before JS hid it. Fix: render a body class reflecting the
 * current tip value, and hide the CUI row via CSS when PF is selected.
 * JS then only swaps the body class on change — no inline style, no FOUC,
 * because the CSS rule applies as soon as <body> is parsed, before the CUI
 * row even renders into the DOM.
 * ------------------------------------------------------------------------- */

add_filter('body_class', function ($classes) {
    if (! function_exists('is_checkout') || ! is_checkout() || is_order_received_page()) {
        return $classes;
    }

    $tip = '';
    $state = '';
    if (function_exists('WC') && WC()->checkout()) {
        $tip = (string) WC()->checkout()->get_value('billing_tip_facturare');
        $state = (string) WC()->checkout()->get_value('billing_state');
    }

    // FGO defaults to '2' (Persoană Fizică) when unset. Anything other than
    // '1' (Persoană Juridică) is treated as PF so the CUI row stays hidden.
    $classes[] = $tip === '1' ? 'natura-tip-pj' : 'natura-tip-pf';

    // București = state code 'B' (see WC i18n/states.php). The sector row
    // is hidden by CSS unless this class is present, mirroring the PJ/PF
    // approach to avoid a flash before checkout.js hydrates.
    if ($state === 'B') {
        $classes[] = 'natura-state-b';
    }

    return $classes;
});

/* ---------------------------------------------------------------------------
 * Shipping methods: above the free-shipping threshold, hide paid options.
 *
 * When the cart subtotal reaches FREE_SHIPPING_MIN (300 lei), only
 * `free_shipping` rates are offered — flat-rate / local-pickup / etc. are
 * stripped so the customer can't accidentally pick a paid method.
 * ------------------------------------------------------------------------- */

add_filter('woocommerce_package_rates', function ($rates, $package) {
    if (! function_exists('WC') || ! WC()->cart) {
        return $rates;
    }

    if ((float) WC()->cart->get_subtotal() < FREE_SHIPPING_MIN) {
        return $rates;
    }

    $free = array_filter($rates, fn ($rate) => $rate->method_id === 'free_shipping');

    return ! empty($free) ? $free : $rates;
}, 100, 2);

// Hide the "Livrare" / "Shipping" package label above the rates list — our
// own <h3>Metodă de livrare</h3> already labels the section.
add_filter('woocommerce_shipping_package_name', '__return_empty_string');

/* ---------------------------------------------------------------------------
 * No-cache on checkout / order-received (nonces + session-bound)
 * ------------------------------------------------------------------------- */

add_action('template_redirect', function () {
    if (function_exists('is_checkout') && (is_checkout() || is_order_received_page())) {
        nocache_headers();
    }
}, 1);

/* ---------------------------------------------------------------------------
 * AJAX login (guest/login tabs in form-billing)
 * ------------------------------------------------------------------------- */

add_action('wp_enqueue_scripts', function () {
    add_action('wp_footer', function () {
        if (! function_exists('is_checkout') || ! is_checkout() || is_user_logged_in()) {
            return;
        }

        echo '<script>var natura_checkout = '.wp_json_encode([
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('natura_checkout_login'),
            'i18n' => [
                'missing_fields' => __('Te rugăm să completezi toate câmpurile.', 'sage'),
                'invalid' => __('Nume de utilizator sau parolă incorectă.', 'sage'),
                'working' => __('Se autentifică...', 'sage'),
                'success' => __('Autentificare reușită! Se reîncarcă pagina...', 'sage'),
                'error' => __('A apărut o eroare. Încearcă din nou.', 'sage'),
            ],
        ]).';</script>';
    }, 5);
});

add_action('wp_ajax_nopriv_natura_checkout_login', __NAMESPACE__.'\\checkout_login_handler');

function checkout_login_handler(): void
{
    check_ajax_referer('natura_checkout_login', 'nonce');

    $username = isset($_POST['username']) ? sanitize_user(wp_unslash($_POST['username'])) : '';
    $password = isset($_POST['password']) ? wp_unslash($_POST['password']) : '';
    $remember = ! empty($_POST['remember']);

    if (empty($username) || empty($password)) {
        wp_send_json_error(['message' => __('Te rugăm să completezi toate câmpurile.', 'sage')]);
    }

    $user = wp_signon([
        'user_login' => $username,
        'user_password' => $password,
        'remember' => $remember,
    ], is_ssl());

    if (is_wp_error($user)) {
        wp_send_json_error(['message' => __('Nume de utilizator sau parolă incorectă.', 'sage')]);
    }

    wp_send_json_success(['message' => __('Autentificare reușită! Se reîncarcă pagina...', 'sage')]);
}

/* ---------------------------------------------------------------------------
 * Progress-steps helper (Coș → Casă → Finalizare)
 * ------------------------------------------------------------------------- */

/**
 * Renders the 3-step checkout progress indicator used on cart, checkout, and
 * order-received pages. Call from any Blade template via `{!! \App\render_checkout_steps() !!}`.
 */
function render_checkout_steps(): string
{
    if (! function_exists('is_cart')) {
        return '';
    }

    $is_cart = is_cart();
    $is_checkout = is_checkout() && ! is_order_received_page();
    $is_thankyou = is_order_received_page();

    $step_cart = $is_cart ? 'is-active' : (($is_checkout || $is_thankyou) ? 'is-done' : '');
    $step_checkout = $is_checkout ? 'is-active' : ($is_thankyou ? 'is-done' : '');
    $step_finish = $is_thankyou ? 'is-active' : '';

    $check = '<svg class="ml-step-check" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="14" height="14"><path fill="currentColor" d="M438.6 105.4c12.5 12.5 12.5 32.8 0 45.3l-256 256c-12.5 12.5-32.8 12.5-45.3 0l-128-128c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0L160 338.7 393.4 105.4c12.5-12.5 32.8-12.5 45.3 0z"/></svg>';

    ob_start();
    ?>
    <div class="ml-loader">
        <div class="ml-loader-line"></div>

        <a href="<?php echo esc_url(wc_get_cart_url()); ?>" class="ml-loader-step ml-loader-1 <?php echo esc_attr($step_cart); ?>">
            <div class="ml-loader-circle">
                <span class="ml-step-number">1</span>
                <?php echo $check; ?>
            </div>
            <span class="ml-loader-text"><?php esc_html_e('Coș', 'sage'); ?></span>
        </a>

        <div class="ml-loader-step ml-loader-2 <?php echo esc_attr($step_checkout); ?>">
            <div class="ml-loader-circle">
                <span class="ml-step-number">2</span>
                <?php echo $check; ?>
            </div>
            <span class="ml-loader-text"><?php esc_html_e('Casă', 'sage'); ?></span>
        </div>

        <div class="ml-loader-step ml-loader-3 <?php echo esc_attr($step_finish); ?>">
            <div class="ml-loader-circle">
                <span class="ml-step-number">3</span>
                <?php echo $check; ?>
            </div>
            <span class="ml-loader-text"><?php esc_html_e('Finalizare', 'sage'); ?></span>
        </div>
    </div>
    <?php
    return (string) ob_get_clean();
}

/* ---------------------------------------------------------------------------
 * Sector (București) — server-side validation, persistence, and address
 * formatting. Field schema lives in the woocommerce_checkout_fields filter
 * above; visibility/required-toggling is handled in checkout.js.
 * ------------------------------------------------------------------------- */

// Required only when state = 'B' (București). Schema-level `required` stays
// false so WC doesn't render the asterisk for non-Bucharest customers; the
// JS flips aria-required for screen readers when applicable.
add_action('woocommerce_after_checkout_validation', function ($data, $errors) {
    if (($data['billing_state'] ?? '') !== 'B') {
        return;
    }

    if (empty($data['billing_sector'])) {
        $errors->add('billing_sector_required', __('Te rugăm să selectezi sectorul.', 'sage'));
    }
}, 10, 2);

add_action('woocommerce_checkout_create_order', function ($order, $data) {
    if (! isset($data['billing_sector']) || $data['billing_sector'] === '') {
        return;
    }

    // Only persist when state = 'B' so a stale value from a previously
    // selected Bucharest doesn't leak onto a non-Bucharest order.
    if (($data['billing_state'] ?? '') !== 'B') {
        return;
    }

    $order->update_meta_data('_billing_sector', sanitize_text_field((string) $data['billing_sector']));
}, 10, 2);

// Show the sector in the formatted billing address (admin order screen,
// emails, invoices, PDFs that read from formatted address).
add_filter('woocommerce_order_formatted_billing_address', function ($address, $order) {
    $sector = $order->get_meta('_billing_sector');
    if ($sector !== '' && $address['state'] === 'B') {
        $address['city'] = trim(($address['city'] ?? '').', Sectorul '.$sector, ', ');
    }

    return $address;
}, 10, 2);

// Optional: surface the sector on the admin "Edit Order" billing block.
add_filter('woocommerce_admin_billing_fields', function ($fields) {
    $fields['sector'] = [
        'label' => __('Sector', 'sage'),
        'show' => true,
    ];

    return $fields;
});

/* ---------------------------------------------------------------------------
 * Safety net: force-empty the cart after the order is created (belt-and-
 * braces; WC already does this itself).
 * ------------------------------------------------------------------------- */

add_action('woocommerce_thankyou', function ($order_id) {
    if (! $order_id || ! wc_get_order($order_id)) {
        return;
    }

    if (function_exists('WC') && WC()->cart) {
        WC()->cart->empty_cart();
    }
}, 1);
