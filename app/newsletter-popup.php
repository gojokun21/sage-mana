<?php

/**
 * Newsletter welcome popup — first-visit lead capture.
 *
 * Responsibilities:
 *   - AJAX endpoint `natura_popup_subscribe` (logged-in + nopriv):
 *       1. Validate name + email + nonce + honeypot.
 *       2. Push the subscriber to TheMarketer via the bundled plugin's
 *          `Mktr\Tracker\Api::send('add_subscriber', ...)` call.
 *          TheMarketer handles dedupe + emails the welcome code itself.
 *   - Inline-localize a config blob `natura_newsletter_popup` with ajax_url +
 *     nonce + i18n strings (Vite handles script enqueue, so we drop the
 *     config into wp_footer rather than wp_localize_script).
 *   - Skip rendering on contexts where the popup would interrupt buying
 *     (checkout, cart, my-account, order-received) — partial is included
 *     unconditionally from layouts/app.blade.php, so we gate via a body class.
 */

namespace App;

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

    newsletter_popup_push_to_themarketer($email, $name);

    wp_send_json_success([
        'name' => $name,
    ]);
}

/**
 * Push subscriber to TheMarketer via the bundled plugin. Uses the plugin's
 * own Api class so we inherit the configured REST key + customer ID without
 * having to mirror those settings in the theme.
 *
 * Best-effort: failures are swallowed because the subscription is the only
 * thing we need to record, and the marketing-platform sync should never
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
        // Silent — marketing sync is non-critical to the popup flow.
    }
}
