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
 * 2b. Prefix posts and post categories with /blog/ so they don't collide
 *     with product/product-category URLs (which now live at /{slug}/).
 *     Both single posts and category archives sit under /blog/{slug}/.
 *     WordPress's rewrite engine matches categories first, then falls
 *     through to posts; wp_unique_post_slug() prevents slug collisions
 *     between a post and a category that share the same name.
 */
add_filter('pre_option_category_base', fn () => 'blog');
add_filter('pre_option_permalink_structure', fn () => '/blog/%postname%/');

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

    /**
     * /blog/{X}/ disambiguation — both category archives and single posts
     * sit at this URL pattern. We can't trust WP's rewrite match here:
     * if the saved `rewrite_rules` option was generated under the old
     * permalink structure (before this theme was activated, or before a
     * flush on production), `/blog/X/` falls through to a generic post
     * rule and query_vars is set to `name=X`. Resolve explicitly and
     * always overwrite query_vars: category → post → 404.
     */
    if ($slug === 'blog' && ! empty($parts[1]) && $parts[1] !== 'page' && $parts[1] !== 'feed') {
        $second = $parts[1];

        // Optional pagination: /blog/{slug}/page/N/
        $blog_paged = 0;
        if (count($parts) >= 4 && $parts[2] === 'page' && is_numeric($parts[3])) {
            $blog_paged = (int) $parts[3];
        }

        // Category — force category_name; don't trust whatever the rewrite
        // engine set, it may have matched the post rule on a stale ruleset.
        $cat_term = get_term_by('slug', $second, 'category');
        if ($cat_term && ! is_wp_error($cat_term)) {
            $wp->query_vars = ['category_name' => $second];
            if ($blog_paged > 0) {
                $wp->query_vars['paged'] = $blog_paged;
            }
            $wp->matched_rule = 'mn_blog_cat';

            return;
        }

        // Otherwise try a published post with this slug.
        global $wpdb;
        $post_id = $wpdb->get_var($wpdb->prepare(
            "SELECT ID FROM {$wpdb->posts} WHERE post_name = %s AND post_type = 'post' AND post_status = 'publish' LIMIT 1",
            $second
        ));

        if ($post_id) {
            $wp->query_vars = [
                'name'      => $second,
                'post_type' => 'post',
            ];
            $wp->matched_rule = 'mn_blog_post';

            return;
        }

        // Neither category nor post — clear the rewrite-set category_name so
        // WP_Query doesn't run a futile term lookup; let the 404 happen cleanly.
        $wp->query_vars = ['error' => '404'];

        return;
    }

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
