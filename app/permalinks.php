<?php

/**
 * Strip `/product/` and `/product-category/` from WooCommerce URLs.
 *
 * Ported 1:1 from mana-naturii/functions.php (lines ~2742-2859):
 *   - `post_type_link`       — str_replace `/product/` out of permalinks
 *   - `term_link`            — str_replace `/product-category/` out of permalinks
 *   - `parse_request` (p. 1) — catches plain `/slug/` requests early; matches
 *                              product_cat first, then product, using direct
 *                              DB queries; bails on reserved slugs and on
 *                              existing pages/posts.
 *   - `after_switch_theme`   — flushes rewrite rules once on theme activation.
 */

namespace App;

/**
 * 1. Remove product base from URLs.
 */
add_filter('post_type_link', function ($post_link, $post) {
    if ($post instanceof \WP_Post
        && $post->post_type === 'product'
        && $post->post_status === 'publish') {
        return str_replace('/product/', '/', $post_link);
    }

    return $post_link;
}, 10, 2);

/**
 * 2. Remove product-category base from URLs.
 */
add_filter('term_link', function ($url, $term, $taxonomy) {
    if ($taxonomy === 'product_cat') {
        return str_replace('/product-category/', '/', $url);
    }

    return $url;
}, 10, 3);

/**
 * 3. Parse request early to handle products and categories.
 */
add_action('parse_request', function ($wp) {
    if (is_admin()) {
        return;
    }

    $request = $wp->request ?? '';

    // Homepage — nothing to do.
    if (empty($request)) {
        return;
    }

    $parts = explode('/', $request);
    $slug = $parts[0];

    // Skip WordPress / WooCommerce / theme reserved slugs.
    $reserved = [
        'wp-admin', 'wp-content', 'wp-includes', 'wp-json', 'wp-login.php',
        'feed', 'embed', 'cart', 'checkout', 'my-account',
        'cos', 'contul-meu', 'finalizare-comanda',
        'shop', 'magazin', 'blog', 'contact', 'despre-noi', 'about',
        'studii', 'categorie-studiu', 'eticheta-studiu',
    ];
    if (in_array($slug, $reserved, true)) {
        return;
    }

    // Skip anything that looks like a file (has an extension).
    if (preg_match('/\.[a-zA-Z0-9]+$/', $slug)) {
        return;
    }

    // Let WP route pages/posts normally if one matches this slug.
    global $wpdb;
    $existing_page = $wpdb->get_var($wpdb->prepare(
        "SELECT ID FROM {$wpdb->posts} WHERE post_name = %s AND post_type IN ('page', 'post') AND post_status = 'publish' LIMIT 1",
        $slug
    ));
    if ($existing_page) {
        return;
    }

    // Paged category: /category-slug/page/2/
    $paged = 0;
    if (count($parts) >= 3 && $parts[1] === 'page' && is_numeric($parts[2])) {
        $paged = (int) $parts[2];
    }

    // Product category first.
    $term = get_term_by('slug', $slug, 'product_cat');
    if ($term && ! is_wp_error($term)) {
        $wp->query_vars = ['product_cat' => $slug];
        if ($paged > 0) {
            $wp->query_vars['paged'] = $paged;
        }
        $wp->matched_rule = 'mn_product_cat';

        return;
    }

    // Then product — only for single-segment URLs.
    if (count($parts) === 1 || (count($parts) === 2 && empty($parts[1]))) {
        $product_id = $wpdb->get_var($wpdb->prepare(
            "SELECT ID FROM {$wpdb->posts} WHERE post_name = %s AND post_type = 'product' AND post_status = 'publish' LIMIT 1",
            $slug
        ));

        if ($product_id) {
            $wp->query_vars = [
                'product' => $slug,
                'post_type' => 'product',
                'name' => $slug,
            ];
            $wp->matched_rule = 'mn_product';

            return;
        }
    }
}, 1);

/**
 * 4. Flush rewrite rules once on theme activation.
 */
add_action('after_switch_theme', function () {
    flush_rewrite_rules();
});
