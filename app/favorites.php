<?php

/**
 * Favorites (wishlist) — in-theme implementation.
 *
 * Storage:
 *   - logged-in users: user meta `_natura_favorites` (array<int>)
 *   - guests: cookie `natura_favorites` (JSON array<int>, 30 days)
 *
 * On login, the guest cookie is merged into user meta and cleared.
 *
 * Public API:
 *   - \App\get_favorites(): int[]
 *   - \App\is_favorite(int $id): bool
 *   - \App\toggle_favorite(int $id): array{in:bool,count:int}
 *   - \App\favorite_button(int|null $id = null): string
 *   - \App\favorites_header_badge(): string
 *
 * Shortcodes:
 *   - [natura_favorites_count]  — header icon + counter
 *   - [natura_favorites_list]   — grid of the user's favorite products
 *
 * Legacy aliases (keep existing markup working):
 *   - cf_favorite_button()           global wrapper
 *   - [cf_favorites_count] shortcode alias
 */

namespace App;

use Illuminate\Support\Facades\View;

const FAVORITES_META_KEY = '_natura_favorites';
const FAVORITES_COOKIE = 'natura_favorites';
const FAVORITES_COOKIE_TTL = 30 * DAY_IN_SECONDS;

/* ---------------------------------------------------------------------------
 * Storage
 * ------------------------------------------------------------------------- */

function get_favorites(): array
{
    if (is_user_logged_in()) {
        $ids = get_user_meta(get_current_user_id(), FAVORITES_META_KEY, true);
        $ids = is_array($ids) ? $ids : [];
    } else {
        $raw = $_COOKIE[FAVORITES_COOKIE] ?? '';
        $ids = $raw ? json_decode(wp_unslash($raw), true) : [];
        $ids = is_array($ids) ? $ids : [];
    }

    return array_values(array_unique(array_filter(array_map('absint', $ids))));
}

function save_favorites(array $ids): void
{
    $ids = array_values(array_unique(array_filter(array_map('absint', $ids))));

    if (is_user_logged_in()) {
        update_user_meta(get_current_user_id(), FAVORITES_META_KEY, $ids);
        return;
    }

    $value = wp_json_encode($ids);
    $expires = time() + FAVORITES_COOKIE_TTL;
    setcookie(FAVORITES_COOKIE, $value, [
        'expires' => $expires,
        'path' => COOKIEPATH ?: '/',
        'domain' => COOKIE_DOMAIN,
        'secure' => is_ssl(),
        'httponly' => false,
        'samesite' => 'Lax',
    ]);
    $_COOKIE[FAVORITES_COOKIE] = $value;
}

function is_favorite(int $product_id): bool
{
    return in_array($product_id, get_favorites(), true);
}

function favorites_count(): int
{
    return count(get_favorites());
}

/**
 * @return array{in:bool,count:int,ids:int[]}
 */
function toggle_favorite(int $product_id): array
{
    $ids = get_favorites();

    if (in_array($product_id, $ids, true)) {
        $ids = array_values(array_diff($ids, [$product_id]));
        $in = false;
    } else {
        $ids[] = $product_id;
        $in = true;
    }

    save_favorites($ids);

    return ['in' => $in, 'count' => count($ids), 'ids' => $ids];
}

/* ---------------------------------------------------------------------------
 * Cookie → user-meta merge on login
 * ------------------------------------------------------------------------- */

add_action('wp_login', function (string $user_login, \WP_User $user): void {
    if (empty($_COOKIE[FAVORITES_COOKIE])) {
        return;
    }

    $guest = json_decode(wp_unslash($_COOKIE[FAVORITES_COOKIE]), true);
    if (! is_array($guest) || empty($guest)) {
        return;
    }

    $existing = get_user_meta($user->ID, FAVORITES_META_KEY, true);
    $existing = is_array($existing) ? $existing : [];

    $merged = array_values(array_unique(array_filter(array_map('absint', array_merge($existing, $guest)))));
    update_user_meta($user->ID, FAVORITES_META_KEY, $merged);

    setcookie(FAVORITES_COOKIE, '', [
        'expires' => time() - HOUR_IN_SECONDS,
        'path' => COOKIEPATH ?: '/',
        'domain' => COOKIE_DOMAIN,
    ]);
    unset($_COOKIE[FAVORITES_COOKIE]);
}, 10, 2);

/* ---------------------------------------------------------------------------
 * AJAX
 * ------------------------------------------------------------------------- */

add_action('wp_enqueue_scripts', function () {
    add_action('wp_footer', function () {
        echo '<script>var natura_favorites = ' . wp_json_encode([
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('natura_favorites_nonce'),
            'ids' => get_favorites(),
            'i18n' => [
                'added' => __('Adăugat la favorite', 'sage'),
                'removed' => __('Eliminat din favorite', 'sage'),
                'error' => __('A apărut o eroare. Încearcă din nou.', 'sage'),
            ],
        ]) . ';</script>';
    }, 5);
});

add_action('wp_ajax_natura_favorites', __NAMESPACE__ . '\\favorites_handler');
add_action('wp_ajax_nopriv_natura_favorites', __NAMESPACE__ . '\\favorites_handler');

function favorites_handler(): void
{
    check_ajax_referer('natura_favorites_nonce', 'nonce');

    $op = isset($_POST['op']) ? sanitize_key(wp_unslash($_POST['op'])) : 'toggle';
    $product_id = isset($_POST['product_id']) ? absint($_POST['product_id']) : 0;

    try {
        if ($op === 'toggle') {
            if (! $product_id || ! get_post($product_id)) {
                wp_send_json_error(['message' => __('Produs invalid', 'sage')]);
            }

            $result = toggle_favorite($product_id);

            wp_send_json_success([
                'in' => $result['in'],
                'count' => $result['count'],
                'ids' => $result['ids'],
                'product_id' => $product_id,
            ]);
        }

        // op=get (default fallthrough)
        $ids = get_favorites();
        wp_send_json_success([
            'count' => count($ids),
            'ids' => $ids,
        ]);
    } catch (\Throwable $e) {
        wp_send_json_error(['message' => $e->getMessage()]);
    }
}

/* ---------------------------------------------------------------------------
 * View helpers
 * ------------------------------------------------------------------------- */

function favorite_button(?int $id = null): string
{
    if ($id === null) {
        global $product;
        if (is_a($product, \WC_Product::class)) {
            $id = $product->get_id();
        }
    }

    if (! $id) {
        return '';
    }

    $active = is_favorite((int) $id);
    $label_add = esc_attr__('Adaugă la favorite', 'sage');
    $label_remove = esc_attr__('Elimină din favorite', 'sage');

    ob_start();
    ?>
    <button type="button"
        class="natura-fav-btn<?php echo $active ? ' is-active' : ''; ?>"
        data-product-id="<?php echo (int) $id; ?>"
        aria-pressed="<?php echo $active ? 'true' : 'false'; ?>"
        aria-label="<?php echo $active ? $label_remove : $label_add; ?>">
        <span class="natura-fav-icon">
            <svg class="natura-fav-icon-empty" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" aria-hidden="true"><path fill="currentColor" d="M225.8 468.2l-2.5-2.3L48.1 303.2C17.4 274.7 0 234.7 0 192.8l0-3.3c0-70.4 50-130.8 119.2-144C158.6 37.9 198.9 47 231 69.6c9 6.4 17.4 13.8 25 22.3c4.2-4.8 8.7-9.2 13.5-13.3c3.7-3.2 7.5-6.2 11.5-9c0 0 0 0 0 0C313.1 47 353.4 37.9 392.8 45.4C462 58.6 512 119.1 512 189.5l0 3.3c0 41.9-17.4 81.9-48.1 110.4L288.7 465.9l-2.5 2.3c-8.2 7.6-19 11.9-30.2 11.9s-22-4.2-30.2-11.9zM239.1 145c-.4-.3-.7-.7-1-1.1l-17.8-20c-23.1-25.9-58-37.7-92-31.2C81.6 101.5 48 142.1 48 189.5l0 3.3c0 28.5 11.9 55.8 32.8 75.2L256 430.7 431.2 268c20.9-19.4 32.8-46.7 32.8-75.2l0-3.3c0-47.3-33.6-88-80.1-96.9c-34-6.5-69 5.4-92 31.2l-17.8 20c-.3 .4-.7 .7-1 1.1c-4.5 4.5-10.6 7-16.9 7s-12.4-2.5-16.9-7z"/></svg>
            <svg class="natura-fav-icon-filled" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" aria-hidden="true"><path fill="currentColor" d="M47.6 300.4L228.3 469.1c7.5 7 17.4 10.9 27.7 10.9s20.2-3.9 27.7-10.9L464.4 300.4c30.4-28.3 47.6-68 47.6-109.5l0-5.8c0-69.9-50.5-129.5-119.4-141C347 36.5 300.6 51.4 268 84L256 96 244 84c-32.6-32.6-79-47.5-124.6-39.9C50.5 55.6 0 115.2 0 185.1l0 5.8c0 41.5 17.2 81.2 47.6 109.5z"/></svg>
        </span>
    </button>
    <?php
    return (string) ob_get_clean();
}

function favorites_header_badge(): string
{
    $count = favorites_count();
    $url = favorites_page_url();

    ob_start();
    ?>
    <a href="<?php echo esc_url($url); ?>" class="favorite_item" aria-label="<?php esc_attr_e('Favoritele mele', 'sage'); ?>">
        <span class="fav_icon_wrapper">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <path d="M12 21s-7.5-4.35-9.5-9.04C1.2 8.16 3.4 4.5 7.13 4.5c2.02 0 3.57 1.03 4.87 2.73 1.3-1.7 2.85-2.73 4.87-2.73 3.73 0 5.93 3.66 4.63 7.46C19.5 16.65 12 21 12 21Z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/>
            </svg>
            <span class="cf-fav-count" data-favorites-count><?php echo (int) $count; ?></span>
        </span>
        <span><?php esc_html_e('Favorite', 'sage'); ?></span>
    </a>
    <?php
    return (string) ob_get_clean();
}

/**
 * Returns the URL of the favorites page. Filterable; defaults to /favorite/.
 */
function favorites_page_url(): string
{
    return (string) apply_filters('natura_favorites_page_url', home_url('/favorite/'));
}

/* ---------------------------------------------------------------------------
 * Shortcodes
 * ------------------------------------------------------------------------- */

add_shortcode('natura_favorites_count', fn () => favorites_header_badge());
add_shortcode('cf_favorites_count', fn () => favorites_header_badge()); // legacy alias

add_shortcode('natura_favorites_list', __NAMESPACE__ . '\\render_favorites_list');

function render_favorites_list($atts = []): string
{
    $atts = shortcode_atts([
        'columns' => 3,
        'empty_text' => __('Nu ai niciun produs favorit. Adaugă produse apăsând pe inimioară.', 'sage'),
    ], $atts, 'natura_favorites_list');

    return View::make('partials.favorites-list', [
        'ids' => get_favorites(),
        'columns' => max(1, min(4, (int) $atts['columns'])),
        'empty_text' => (string) $atts['empty_text'],
    ])->render();
}

/* ---------------------------------------------------------------------------
 * Legacy global aliases
 * ------------------------------------------------------------------------- */

if (! function_exists('cf_favorite_button')) {
    function cf_favorite_button(?int $id = null): void
    {
        echo \App\favorite_button($id);
    }
}
