<?php

/**
 * Theme filters.
 */

namespace App;

/**
 * Add "… Continued" to the excerpt.
 *
 * @return string
 */
add_filter('excerpt_more', function () {
    return sprintf(' &hellip; <a href="%s">%s</a>', get_permalink(), __('Continued', 'sage'));
});

/**
 * Disable WooCommerce default stylesheets (layout, smallscreen, general, blocks).
 */
add_filter('woocommerce_enqueue_styles', '__return_empty_array');

add_action('wp_enqueue_scripts', function () {
    wp_dequeue_style('wc-blocks-style');
    wp_dequeue_style('wc-blocks-vendors-style');
    wp_dequeue_style('wc-block-style');
}, 100);

/**
 * Load Contact Form 7 assets only on pages that actually embed a [contact-form-7]
 * shortcode. CF7 enqueues its CSS + JS globally by default, which is dead weight
 * on the home page, shop, product pages, etc.
 */
add_action('wp_enqueue_scripts', function () {
    $needs_cf7 = false;

    if (is_singular()) {
        $post = get_post();
        if ($post && has_shortcode((string) $post->post_content, 'contact-form-7')) {
            $needs_cf7 = true;
        }
    }

    if ($needs_cf7) {
        return;
    }

    wp_dequeue_style('contact-form-7');
    wp_dequeue_script('contact-form-7');
}, 100);

/**
 * mn-quantity-bundles is only used on single product pages — dequeue its
 * CSS + JS everywhere else. Matches by src path instead of handle name so
 * we don't break if the plugin renames its handles.
 */
add_action('wp_enqueue_scripts', function () {
    if (is_product()) {
        return;
    }

    $needle = '/mn-quantity-bundles/';

    foreach (wp_styles()->registered as $handle => $style) {
        if (is_string($style->src) && str_contains($style->src, $needle)) {
            wp_dequeue_style($handle);
        }
    }

    foreach (wp_scripts()->registered as $handle => $script) {
        if (is_string($script->src) && str_contains($script->src, $needle)) {
            wp_dequeue_script($handle);
        }
    }
}, 100);

/**
 * Trim dead-weight stylesheets on the front page.
 *
 *   - Gutenberg block library: the home template is a Blade view with custom
 *     partials, no core blocks render.
 *   - WooCommerce Product Bundles: bundles don't appear on the home page;
 *     their CSS + JS bundle is ~30–40kB of nothing.
 *
 * Matches WooCommerce Product Bundles assets by src path so the rule survives
 * handle renames in plugin updates.
 */
add_action('wp_enqueue_scripts', function () {
    if (! is_front_page()) {
        return;
    }

    wp_dequeue_style('wp-block-library');
    wp_dequeue_style('wp-block-library-theme');
    wp_dequeue_style('global-styles');

    $needle = '/woocommerce-product-bundles/';

    foreach (wp_styles()->registered as $handle => $style) {
        if (is_string($style->src) && str_contains($style->src, $needle)) {
            wp_dequeue_style($handle);
        }
    }

    foreach (wp_scripts()->registered as $handle => $script) {
        if (is_string($script->src) && str_contains($script->src, $needle)) {
            wp_dequeue_script($handle);
        }
    }
}, 100);

/**
 * Drop jquery-migrate on the home page. It's a back-compat shim for deprecated
 * jQuery 1.x/2.x APIs; nothing that runs on the home relies on them.
 *
 * Strips it from the `jquery` meta-handle's deps at enqueue time so the shim
 * isn't pulled in when jQuery itself is printed.
 */
add_action('wp_enqueue_scripts', function () {
    if (is_admin() || ! is_front_page()) {
        return;
    }

    $registered = wp_scripts()->registered['jquery'] ?? null;
    if ($registered && ! empty($registered->deps)) {
        $registered->deps = array_diff((array) $registered->deps, ['jquery-migrate']);
    }
}, 100);

/**
 * Defer non-critical 3rd-party scripts so they don't block parsing/rendering.
 *
 * `defer` keeps execution order between deferred scripts and runs them after
 * the document is parsed — safe for analytics/tracking code that only fires
 * on user interaction (no UX path depends on them being ready during HTML
 * parse).
 *
 * Matches by src path so plugin handle renames don't break the rule.
 */
add_filter('script_loader_tag', function ($tag, $handle, $src) {
    if (is_admin()) {
        return $tag;
    }

    $defer_needles = [
        '/duracelltomi-google-tag-manager/', // GTM4WP (gtm.js, gtm4wp-woocommerce, form-tracker)
    ];

    $match = false;
    foreach ($defer_needles as $needle) {
        if (is_string($src) && str_contains($src, $needle)) {
            $match = true;
            break;
        }
    }

    if (! $match) {
        return $tag;
    }

    // Already deferred or async — leave it.
    if (str_contains($tag, ' defer') || str_contains($tag, ' async')) {
        return $tag;
    }

    return str_replace('<script ', '<script defer ', $tag);
}, 10, 3);

/**
 * Add theme classes to the loop add-to-cart button.
 */
add_filter('woocommerce_loop_add_to_cart_args', function ($args, $product) {
    $classes = isset($args['class']) ? explode(' ', $args['class']) : [];
    $classes[] = 'btn-primary';

    if (!$product->is_purchasable() || !$product->is_in_stock()) {
        $classes[] = 'btn-unavailable';
    }

    $args['class'] = implode(' ', array_unique(array_filter($classes)));

    return $args;
}, 10, 2);

/**
 * Customize the out-of-stock button label in the shop loop.
 */
add_filter('woocommerce_product_add_to_cart_text', function ($text, $product) {
    if (!$product->is_in_stock()) {
        return $product->get_type() === 'bundle' ? 'Pachet indisponibil' : 'Stoc epuizat';
    }

    return $text;
}, 10, 2);

/**
 * Refresh the header cart count via WooCommerce AJAX fragments.
 * Keeps .count__cart in the shopping-cart link in sync after add-to-cart
 * without a page reload.
 */
add_filter('woocommerce_add_to_cart_fragments', function ($fragments) {
    if (!function_exists('WC') || !WC()->cart) {
        return $fragments;
    }

    $fragments['.shopping-cart .count__cart'] = sprintf(
        '<div class="count__cart">%d</div>',
        WC()->cart->get_cart_contents_count()
    );

    return $fragments;
});

/**
 * Prepend a cart icon to the loop add-to-cart button for purchasable, in-stock products.
 */
add_filter('woocommerce_loop_add_to_cart_link', function ($html, $product) {
    if (!$product->is_purchasable() || !$product->is_in_stock()) {
        return $html;
    }

    $icon = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">'
        . '<path d="M2 3l.265.088c1.32.44 1.98.66 2.357 1.184.377.524.378 1.22.378 2.611V9.5c0 2.828 0 4.243.879 5.121.878.879 2.293.879 5.121.879h8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>'
        . '<path d="M5 6h11.45c2.055 0 3.083 0 3.528.674.444.675.04 1.619-.77 3.508l-.429 1c-.378.882-.567 1.322-.942 1.57-.376.248-.856.248-1.815.248H5" stroke="currentColor" stroke-width="1.5"/>'
        . '<circle cx="7.5" cy="19.5" r="1.5" stroke="currentColor" stroke-width="1.5"/>'
        . '<circle cx="16.5" cy="19.5" r="1.5" stroke="currentColor" stroke-width="1.5"/>'
        . '</svg>';

    return preg_replace('/(<a\b[^>]*>)/', '$1' . $icon . ' ', $html, 1);
}, 10, 2);

/**
 * Disable comments on blog posts (post_type = 'post').
 * Keeps WooCommerce reviews (product post type) untouched.
 */
add_filter('comments_open', function ($open, $post_id) {
    return get_post_type($post_id) === 'post' ? false : $open;
}, 10, 2);

add_filter('pings_open', function ($open, $post_id) {
    return get_post_type($post_id) === 'post' ? false : $open;
}, 10, 2);

// Hide existing comments + the count from the admin bar / archive UI.
add_filter('comments_array', function ($comments, $post_id) {
    return get_post_type($post_id) === 'post' ? [] : $comments;
}, 10, 2);

/**
 * Show 13 products per page on shop and product archives.
 */
add_filter('loop_shop_per_page', fn () => 15, 20);

/**
 * On the shop and category archives, list simple/variable products first and
 * bundle products at the end, while preserving the active sort within each
 * group. Bundles are detected via the WooCommerce `product_type` taxonomy.
 */
add_filter('posts_clauses', function ($clauses, $query) {
    if (is_admin() || !$query->is_main_query()) {
        return $clauses;
    }

    if (!is_shop() && !is_product_taxonomy()) {
        return $clauses;
    }

    $term = get_term_by('slug', 'bundle', 'product_type');
    if (!$term) {
        return $clauses;
    }

    global $wpdb;

    $clauses['join'] .= $wpdb->prepare(
        " LEFT JOIN {$wpdb->term_relationships} AS sage_bundle_tr
            ON {$wpdb->posts}.ID = sage_bundle_tr.object_id
           AND sage_bundle_tr.term_taxonomy_id = %d ",
        $term->term_taxonomy_id
    );

    $bundle_flag = '(CASE WHEN sage_bundle_tr.object_id IS NULL THEN 0 ELSE 1 END)';
    $clauses['orderby'] = $bundle_flag . ' ASC'
        . (!empty($clauses['orderby']) ? ', ' . $clauses['orderby'] : '');
    $clauses['groupby'] = "{$wpdb->posts}.ID";

    return $clauses;
}, 10, 2);
