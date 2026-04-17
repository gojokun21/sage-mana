<?php

/**
 * Header login modal (ported from mana-naturii).
 *
 *  - Renders the modal markup at `wp_footer` for guests only.
 *  - AJAX login: `natura_login` (nonce `natura-login`).
 *  - AJAX nonce refresh: `natura_login_nonce` (for cached pages).
 *
 *  Trigger: any element with class `.open-login-modal` opens the modal.
 */

namespace App;

use Illuminate\Support\Facades\View;

const LOGIN_NONCE = 'natura-login';

/* ---------------------------------------------------------------------------
 * AJAX handlers
 * ------------------------------------------------------------------------- */

add_action('wp_ajax_nopriv_natura_login', __NAMESPACE__ . '\\login_handler');

function login_handler(): void
{
    check_ajax_referer(LOGIN_NONCE, 'security');

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

    wp_send_json_success(['message' => __('Autentificare reușită!', 'sage')]);
}

/**
 * Fresh nonce for cached pages — fetched by the modal JS on open.
 */
add_action('wp_ajax_nopriv_natura_login_nonce', __NAMESPACE__ . '\\login_nonce_handler');

function login_nonce_handler(): void
{
    wp_send_json_success(['nonce' => wp_create_nonce(LOGIN_NONCE)]);
}

/* ---------------------------------------------------------------------------
 * Modal markup (footer) + JS config
 * ------------------------------------------------------------------------- */

add_action('wp_footer', function () {
    if (is_user_logged_in()) {
        return;
    }

    echo '<script>var natura_login = ' . wp_json_encode([
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce(LOGIN_NONCE),
        'i18n' => [
            'empty' => __('Completează email-ul și parola.', 'sage'),
            'working' => __('Se conectează...', 'sage'),
            'success' => __('Conectat! Se reîncarcă...', 'sage'),
            'error' => __('Eroare la autentificare.', 'sage'),
            'network' => __('Eroare de rețea. Încearcă din nou.', 'sage'),
            'cta' => __('Conectează-te', 'sage'),
        ],
    ]) . ';</script>';

    echo View::make('partials.login-modal', [
        'enable_registration' => 'yes' === get_option('woocommerce_enable_myaccount_registration'),
        'lost_password_url' => wp_lostpassword_url(),
        'register_action_url' => function_exists('wc_get_page_permalink') ? wc_get_page_permalink('myaccount') : home_url('/contul-meu/'),
        'generate_username' => 'yes' === get_option('woocommerce_registration_generate_username'),
        'generate_password' => 'yes' === get_option('woocommerce_registration_generate_password'),
    ])->render();
}, 30);
