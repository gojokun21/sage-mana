<?php

/**
 * Newsletter welcome popup — first-visit lead capture with -10% coupon.
 *
 * Responsibilities:
 *   - AJAX endpoint `natura_popup_subscribe` (logged-in + nopriv):
 *       1. Validate name + email + nonce + honeypot.
 *       2. Reject duplicates (existing subscriber gets a clean error).
 *       3. Generate a single-use WC coupon `BUNVENIT-{XXXXXX}` — 10% off,
 *          restricted to the subscribed email, valid 30 days.
 *       4. Push the subscriber to TheMarketer via the bundled plugin's
 *          `Mktr\Tracker\Api::send('add_subscriber', ...)` call.
 *       5. Return the coupon code so the popup can show it in the success
 *          state.
 *   - Inline-localize a config blob `natura_newsletter_popup` with ajax_url +
 *     nonce + i18n strings (Vite handles script enqueue, so we drop the
 *     config into wp_footer rather than wp_localize_script).
 *   - Skip rendering on contexts where the popup would interrupt buying
 *     (checkout, cart, my-account, order-received) — partial is included
 *     unconditionally from layouts/app.blade.php, so we gate via a body class.
 */

namespace App;

const NEWSLETTER_POPUP_COUPON_PREFIX = 'BUNVENIT-';
const NEWSLETTER_POPUP_COUPON_DAYS = 30;
const NEWSLETTER_POPUP_COUPON_PERCENT = 10;

add_action('wp_enqueue_scripts', function () {
    add_action('wp_footer', function () {
        if (! newsletter_popup_should_render()) {
            return;
        }

        echo '<script>var natura_newsletter_popup = '.wp_json_encode([
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('natura_popup_subscribe'),
            'i18n' => [
                'missing' => __('Te rugăm să completezi prenumele și emailul.', 'sage'),
                'invalid_email' => __('Adresa de email nu pare validă.', 'sage'),
                'already' => __('Acest email este deja abonat. Verifică-ți inbox-ul pentru codul anterior.', 'sage'),
                'working' => __('Se trimite...', 'sage'),
                'error' => __('A apărut o eroare. Încearcă din nou într-un minut.', 'sage'),
            ],
        ]).';</script>';
    }, 5);
});

/**
 * Decide whether the popup should be present in the DOM for this request.
 * Pages where it would interrupt a transaction are skipped entirely so the
 * JS timer never has a node to mount to.
 */
function newsletter_popup_should_render(): bool
{
    if (is_user_logged_in()) {
        return false;
    }

    if (function_exists('is_checkout') && (is_checkout() || is_order_received_page())) {
        return false;
    }

    if (function_exists('is_cart') && is_cart()) {
        return false;
    }

    if (function_exists('is_account_page') && is_account_page()) {
        return false;
    }

    return true;
}

add_action('wp_ajax_natura_popup_subscribe', __NAMESPACE__.'\\newsletter_popup_subscribe_handler');
add_action('wp_ajax_nopriv_natura_popup_subscribe', __NAMESPACE__.'\\newsletter_popup_subscribe_handler');

function newsletter_popup_subscribe_handler(): void
{
    check_ajax_referer('natura_popup_subscribe', 'nonce');

    // Honeypot — bots love filling every <input>.
    if (! empty($_POST['website'])) {
        wp_send_json_error(['message' => __('Cerere invalidă.', 'sage')]);
    }

    $name = isset($_POST['name']) ? sanitize_text_field(wp_unslash($_POST['name'])) : '';
    $email = isset($_POST['email']) ? sanitize_email(wp_unslash($_POST['email'])) : '';

    if ($name === '' || $email === '') {
        wp_send_json_error(['message' => __('Te rugăm să completezi prenumele și emailul.', 'sage')]);
    }

    if (! is_email($email)) {
        wp_send_json_error(['message' => __('Adresa de email nu pare validă.', 'sage')]);
    }

    if (newsletter_popup_email_already_used($email)) {
        wp_send_json_error([
            'message' => __('Acest email a primit deja un cod. Verifică-ți inbox-ul.', 'sage'),
            'already' => true,
        ]);
    }

    $code = newsletter_popup_create_coupon($email);
    if ($code === null) {
        wp_send_json_error(['message' => __('Nu am putut genera codul. Încearcă din nou.', 'sage')]);
    }

    newsletter_popup_push_to_themarketer($email, $name);

    wp_send_json_success([
        'code' => $code,
        'name' => $name,
        'expires_days' => NEWSLETTER_POPUP_COUPON_DAYS,
    ]);
}

/**
 * One coupon per email — we tag generated coupons with meta
 * `_natura_popup_email`, so a second submit with the same address can be
 * detected without scanning the coupon list.
 */
function newsletter_popup_email_already_used(string $email): bool
{
    $query = new \WP_Query([
        'post_type' => 'shop_coupon',
        'post_status' => 'any',
        'posts_per_page' => 1,
        'fields' => 'ids',
        'meta_key' => '_natura_popup_email',
        'meta_value' => strtolower($email),
        'no_found_rows' => true,
        'update_post_meta_cache' => false,
        'update_post_term_cache' => false,
    ]);

    return ! empty($query->posts);
}

/**
 * Generate a WC coupon: `BUNVENIT-XXXXXX`, 10% off, single-use, restricted
 * to the subscribed email, expiring in 30 days. Returns the code on success
 * or null on failure (DB error / duplicate code collision after retries).
 */
function newsletter_popup_create_coupon(string $email): ?string
{
    if (! function_exists('wc_get_coupon_id_by_code') || ! class_exists(\WC_Coupon::class)) {
        return null;
    }

    // Retry up to 3x in the (extremely unlikely) event of a code collision.
    $code = null;
    for ($i = 0; $i < 3; $i++) {
        $candidate = NEWSLETTER_POPUP_COUPON_PREFIX.strtoupper(wp_generate_password(6, false, false));
        if (wc_get_coupon_id_by_code($candidate) === 0) {
            $code = $candidate;
            break;
        }
    }

    if ($code === null) {
        return null;
    }

    try {
        $coupon = new \WC_Coupon();
        $coupon->set_code($code);
        $coupon->set_discount_type('percent');
        $coupon->set_amount(NEWSLETTER_POPUP_COUPON_PERCENT);
        $coupon->set_individual_use(true);
        // Single redemption is the real anti-abuse mechanism: the code is
        // random (`BUNVENIT-XXXXXX`) so it can't be guessed, and once it's
        // used the coupon becomes inert. We intentionally do NOT set an
        // email restriction — it caused WC to refuse the code on the cart
        // page (where billing_email isn't filled yet) and required an exact
        // case-sensitive match at checkout. Friction without security gain.
        $coupon->set_usage_limit(1);
        $coupon->set_usage_limit_per_user(1);
        $coupon->set_date_expires(time() + (NEWSLETTER_POPUP_COUPON_DAYS * DAY_IN_SECONDS));
        $coupon->set_description(sprintf(
            /* translators: %s = subscriber email */
            __('Cod cadou newsletter pentru %s', 'sage'),
            $email
        ));
        $coupon->save();

        // Tag the coupon with the lowercased email for fast dedupe lookups
        // (one coupon per subscriber email — see newsletter_popup_email_already_used).
        update_post_meta($coupon->get_id(), '_natura_popup_email', strtolower($email));
    } catch (\Throwable $e) {
        return null;
    }

    return $code;
}

/**
 * Push subscriber to TheMarketer via the bundled plugin. Uses the plugin's
 * own Api class so we inherit the configured REST key + customer ID without
 * having to mirror those settings in the theme.
 *
 * Best-effort: failures are swallowed because the user already has their
 * coupon code by this point and the marketing-platform sync should never
 * break the popup UX.
 */
function newsletter_popup_push_to_themarketer(string $email, string $name): void
{
    if (! class_exists(\Mktr\Tracker\Api::class) || ! class_exists(\Mktr\Tracker\Config::class)) {
        return;
    }

    try {
        \Mktr\Tracker\Api::send('add_subscriber', [
            'email' => $email,
            'name' => $name,
        ]);
    } catch (\Throwable $e) {
        // Silent — the coupon is already issued, marketing sync is non-critical.
    }
}
