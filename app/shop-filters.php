<?php

/**
 * Shop / catalog filter logic.
 * --------------------------------------------------------------------------
 * Implements custom sidebar filtering for the WooCommerce shop archive
 * (and product category archives). Replaces the legacy FE-plugin widget.
 *
 * URL params handled (all optional, all GET):
 *   ?categorie[]=<slug>           — multiple product_cat slugs (OR)
 *   ?format[]=<slug>              — multiple pa_format attribute terms (OR)
 *   ?caracteristici[]=<slug>      — multiple pa_caracteristici terms (OR)
 *   ?min_price=<int>              — min price in major units (RON)
 *   ?max_price=<int>              — max price in major units (RON)
 *   ?in_stock=1                   — limit to in-stock only
 *   ?orderby=<menu_order|popularity|rating|date|price|price-desc>
 *
 * Only fires on the main query for is_shop() / is_product_taxonomy() so it
 * doesn't accidentally filter widgets, sliders, or sub-queries.
 */

namespace App;

use Illuminate\Support\Facades\View;
use WP_Query;

const SHOP_TAXONOMY_FORMAT = 'pa_format';
const SHOP_TAXONOMY_FEATURES = 'pa_caracteristici';

add_action('pre_get_posts', __NAMESPACE__.'\\shop_apply_filters');

function shop_apply_filters(\WP_Query $q): void
{
    if (is_admin() || ! $q->is_main_query()) {
        return;
    }

    if (! function_exists('is_shop') || (! is_shop() && ! is_product_taxonomy())) {
        return;
    }

    [$tax_query, $meta_query] = shop_build_query_clauses($_GET, is_shop());

    if (! empty($tax_query)) {
        $q->set('tax_query', array_merge((array) $q->get('tax_query'), $tax_query));
    }
    if (! empty($meta_query)) {
        $q->set('meta_query', array_merge((array) $q->get('meta_query'), $meta_query));
    }
}

/**
 * Build tax_query / meta_query clauses for a given set of filter params.
 * Shared between the main-query pre_get_posts hook and the AJAX handler.
 *
 * @param  array  $params  Source params (typically $_GET or sanitized AJAX input)
 * @param  bool   $include_cat  Whether to apply the `categorie` filter (skipped on
 *                              product_cat archives — the base term already narrows).
 * @return array{0:array,1:array}  [tax_query, meta_query]
 */
function shop_build_query_clauses(array $params, bool $include_cat = true): array
{
    $tax_query = [];
    $meta_query = [];

    $cat_slugs = shop_param_array('categorie', $params);
    if ($include_cat && ! empty($cat_slugs)) {
        $tax_query[] = [
            'taxonomy' => 'product_cat',
            'field' => 'slug',
            'terms' => $cat_slugs,
            'operator' => 'IN',
        ];
    }

    $format_slugs = shop_param_array('format', $params);
    if (! empty($format_slugs) && taxonomy_exists(SHOP_TAXONOMY_FORMAT)) {
        $tax_query[] = [
            'taxonomy' => SHOP_TAXONOMY_FORMAT,
            'field' => 'slug',
            'terms' => $format_slugs,
            'operator' => 'IN',
        ];
    }

    $feat_slugs = shop_param_array('caracteristici', $params);
    if (! empty($feat_slugs) && taxonomy_exists(SHOP_TAXONOMY_FEATURES)) {
        $tax_query[] = [
            'taxonomy' => SHOP_TAXONOMY_FEATURES,
            'field' => 'slug',
            'terms' => $feat_slugs,
            'operator' => 'IN',
        ];
    }

    [$min, $max] = shop_price_filter_range($params);
    if ($min !== null || $max !== null) {
        $range = shop_price_range();
        $meta_query[] = [
            'key' => '_price',
            'value' => [$min ?? $range[0], $max ?? $range[1]],
            'compare' => 'BETWEEN',
            'type' => 'NUMERIC',
        ];
    }

    if (! empty($params['in_stock'])) {
        $meta_query[] = [
            'key' => '_stock_status',
            'value' => 'instock',
            'compare' => '=',
        ];
    }

    return [$tax_query, $meta_query];
}

/**
 * Map our `orderby` URL value (menu_order, popularity, rating, date, price,
 * price-desc, relevance) to WP_Query orderby/order args. Mirrors WC's
 * `WC_Query::get_catalog_ordering_args()` minus the runtime filters.
 *
 * @return array{orderby:string|array, order:string, meta_key?:string}
 */
function shop_orderby_args(string $orderby): array
{
    switch ($orderby) {
        case 'popularity':
            return ['orderby' => 'meta_value_num', 'order' => 'DESC', 'meta_key' => 'total_sales'];
        case 'rating':
            return ['orderby' => 'meta_value_num', 'order' => 'DESC', 'meta_key' => '_wc_average_rating'];
        case 'date':
            return ['orderby' => 'date', 'order' => 'DESC'];
        case 'price':
            return ['orderby' => 'meta_value_num', 'order' => 'ASC', 'meta_key' => '_price'];
        case 'price-desc':
            return ['orderby' => 'meta_value_num', 'order' => 'DESC', 'meta_key' => '_price'];
        case 'menu_order':
        default:
            return ['orderby' => ['menu_order' => 'ASC', 'title' => 'ASC'], 'order' => 'ASC'];
    }
}

/* ============================================================================
 * AJAX endpoint
 * ============================================================================ */

add_action('wp_enqueue_scripts', function () {
    $is_pachete = str_contains((string) get_page_template_slug(), 'template-pachete');
    if (! function_exists('is_shop') || (! is_shop() && ! is_product_taxonomy() && ! $is_pachete)) {
        return;
    }
    add_action('wp_footer', function () use ($is_pachete) {
        if ($is_pachete) {
            $base_url = get_permalink();
        } else {
            $base_url = function_exists('wc_get_page_id') && is_shop()
                ? get_permalink(wc_get_page_id('shop'))
                : (is_product_taxonomy() ? get_term_link(get_queried_object()) : home_url('/'));
        }

        // Pe arhivele de categorie trimitem taxonomia + slug-ul termenului, ca
        // AJAX-ul (shop_filter_ajax) să scopeze rezultatele în categoria curentă.
        $tax = '';
        $term_slug = '';
        if (! $is_pachete && is_product_taxonomy()) {
            $obj = get_queried_object();
            if ($obj && isset($obj->taxonomy, $obj->slug)) {
                $tax = (string) $obj->taxonomy;
                $term_slug = (string) $obj->slug;
            }
        }

        echo '<script>var natura_shop_filters = '.wp_json_encode([
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('natura_shop_filters_nonce'),
            'base_url' => is_string($base_url) ? $base_url : home_url('/'),
            'context' => $is_pachete ? 'pachete' : '',
            'taxonomy' => $tax,
            'term' => $term_slug,
        ]).';</script>';
    }, 5);
});

add_action('wp_ajax_natura_shop_filter', __NAMESPACE__.'\\shop_filter_ajax');
add_action('wp_ajax_nopriv_natura_shop_filter', __NAMESPACE__.'\\shop_filter_ajax');

function shop_filter_ajax(): void
{
    check_ajax_referer('natura_shop_filters_nonce', 'nonce');

    $params = wp_unslash($_POST);

    $taxonomy = isset($params['taxonomy']) ? sanitize_key((string) $params['taxonomy']) : '';
    $term_slug = isset($params['term']) ? sanitize_title((string) $params['term']) : '';
    $is_taxonomy = ($taxonomy === 'product_cat' && $term_slug !== '');

    $context = isset($params['context']) ? sanitize_key((string) $params['context']) : '';
    $is_pachete = ($context === 'pachete');

    [$tax_query, $meta_query] = shop_build_query_clauses($params, ! $is_taxonomy && ! $is_pachete);

    if ($is_taxonomy) {
        $tax_query[] = [
            'taxonomy' => $taxonomy,
            'field' => 'slug',
            'terms' => $term_slug,
        ];
    }

    if ($is_pachete) {
        $tax_query[] = [
            'taxonomy' => 'product_type',
            'field' => 'slug',
            'terms' => 'bundle',
        ];
    }

    $orderby = isset($params['orderby']) ? sanitize_key((string) $params['orderby']) : 'menu_order';
    $order_args = shop_orderby_args($orderby);

    $paged = isset($params['paged']) ? max(1, (int) $params['paged']) : 1;
    $per_page = (int) apply_filters('loop_shop_per_page', wc_get_default_products_per_row() * wc_get_default_product_rows_per_page());

    $args = array_merge([
        'post_type' => 'product',
        'post_status' => 'publish',
        'posts_per_page' => $per_page,
        'paged' => $paged,
        'tax_query' => $tax_query,
        'meta_query' => $meta_query,
    ], $order_args);

    // WC's visibility filter (hide hidden products + honour catalog visibility).
    $args['tax_query'][] = [
        'taxonomy' => 'product_visibility',
        'field' => 'name',
        'terms' => ['exclude-from-catalog'],
        'operator' => 'NOT IN',
    ];

    $query = new WP_Query($args);

    // Swap globals so the partial (which reads $wp_query, have_posts(), the_post())
    // and WC's content-product template see our query. Restore after rendering.
    global $wp_query;
    $original_query = $wp_query;
    $wp_query = $query; // phpcs:ignore WordPress.WP.GlobalVariablesOverride

    wc_setup_loop([
        'name' => 'shop',
        'is_shortcode' => false,
        'is_paginated' => true,
        'per_page' => $per_page,
        'columns' => wc_get_default_products_per_row(),
        'total' => (int) $query->found_posts,
        'total_pages' => (int) $query->max_num_pages,
        'current_page' => $paged,
    ]);

    // The pagination partial uses paginate_links() which builds links from
    // $_SERVER['REQUEST_URI']. During AJAX that's /wp-admin/admin-ajax.php —
    // temporarily swap it for the archive URL's path so links resolve correctly.
    $base_url = isset($_POST['base_url']) ? esc_url_raw(wp_unslash((string) $_POST['base_url'])) : '';
    $archive_path = $base_url !== '' ? wp_parse_url($base_url, PHP_URL_PATH) : null;
    $original_request_uri = $_SERVER['REQUEST_URI'] ?? null;
    if ($archive_path) {
        $_SERVER['REQUEST_URI'] = $archive_path;
    }

    $grid_html = View::make('partials.shop-loop')->render();

    if ($original_request_uri !== null) {
        $_SERVER['REQUEST_URI'] = $original_request_uri;
    }

    wc_reset_loop();
    wp_reset_postdata();
    $wp_query = $original_query; // phpcs:ignore WordPress.WP.GlobalVariablesOverride

    $found = (int) $query->found_posts;
    $start = $found > 0 ? (($paged - 1) * $per_page) + 1 : 0;
    $end = min($paged * $per_page, $found);

    $count_html = $found > 0
        ? sprintf(
            /* translators: 1: start index, 2: end index, 3: total */
            __('Afișez <strong>%1$d–%2$d</strong> din <strong>%3$d</strong> de rezultate', 'sage'),
            $start, $end, $found
        )
        : __('Niciun rezultat', 'sage');

    wp_send_json_success([
        'html' => $grid_html,
        'count_html' => $count_html,
        'found' => $found,
        'max_pages' => (int) $query->max_num_pages,
        'current_page' => $paged,
    ]);
}


/**
 * Normalize a multi-value param into a clean array of slugs.
 * Reads from $source if provided, else falls back to $_GET (back-compat
 * with templates that don't pass an explicit source).
 *
 * @return string[]
 */
function shop_param_array(string $key, ?array $source = null): array
{
    $src = $source ?? $_GET;
    if (empty($src[$key])) {
        return [];
    }
    $raw = (array) $src[$key];
    $out = [];
    foreach ($raw as $v) {
        $v = sanitize_title((string) $v);
        if ($v !== '') {
            $out[] = $v;
        }
    }

    return array_values(array_unique($out));
}

/**
 * Parse min_price / max_price params, clamp to the catalog's actual range.
 *
 * @return array{0:?int,1:?int}
 */
function shop_price_filter_range(?array $source = null): array
{
    $src = $source ?? $_GET;
    $min = isset($src['min_price']) ? (int) $src['min_price'] : null;
    $max = isset($src['max_price']) ? (int) $src['max_price'] : null;

    if ($min !== null && $min < 0) {
        $min = 0;
    }
    if ($max !== null && $max < 0) {
        $max = 0;
    }
    if ($min !== null && $max !== null && $min > $max) {
        [$min, $max] = [$max, $min];
    }

    return [$min, $max];
}

/**
 * Top-level (parent = 0) product categories with non-zero count, excluding
 * the package-bundles category `pachete` (own page) and any opted-out via
 * `sage_shop_excluded_category_slugs` filter. Cached 10 min.
 *
 * @return array<int, array{slug:string, name:string, count:int}>
 */
function shop_category_terms_with_count(): array
{
    $cache_key = 'natura_shop_categories';
    $cached = get_transient($cache_key);
    if (is_array($cached)) {
        return $cached;
    }

    $terms = get_terms([
        'taxonomy' => 'product_cat',
        'hide_empty' => true,
        'orderby' => 'count',
        'order' => 'DESC',
    ]);
    if (is_wp_error($terms) || empty($terms)) {
        return [];
    }

    $excluded = (array) apply_filters('sage_shop_excluded_category_slugs', ['pachete']);

    $out = [];
    foreach ($terms as $t) {
        if (in_array($t->slug, $excluded, true)) {
            continue;
        }
        $out[] = [
            'slug' => $t->slug,
            'name' => $t->name,
            'count' => (int) $t->count,
        ];
    }

    set_transient($cache_key, $out, 10 * MINUTE_IN_SECONDS);

    return $out;
}

/**
 * Terms of a product attribute taxonomy (e.g. `pa_format`, `pa_caracteristici`).
 * Returns [] if the taxonomy doesn't exist — sidebar template skips the block.
 *
 * @return array<int, array{slug:string, name:string, count:int}>
 */
function shop_attribute_terms(string $taxonomy): array
{
    if (! taxonomy_exists($taxonomy)) {
        return [];
    }

    $cache_key = 'natura_shop_attr_'.$taxonomy;
    $cached = get_transient($cache_key);
    if (is_array($cached)) {
        return $cached;
    }

    $terms = get_terms([
        'taxonomy' => $taxonomy,
        'hide_empty' => true,
        'orderby' => 'count',
        'order' => 'DESC',
    ]);
    if (is_wp_error($terms) || empty($terms)) {
        return [];
    }

    $out = [];
    foreach ($terms as $t) {
        $out[] = [
            'slug' => $t->slug,
            'name' => $t->name,
            'count' => (int) $t->count,
        ];
    }

    set_transient($cache_key, $out, 10 * MINUTE_IN_SECONDS);

    return $out;
}

/**
 * Min/max price across all published in-stock products. Cached 10 min.
 * Falls back to [0, 1000] if no data.
 *
 * @return array{0:int,1:int}
 */
function shop_price_range(): array
{
    $cache_key = 'natura_shop_price_range';
    $cached = get_transient($cache_key);
    if (is_array($cached) && count($cached) === 2) {
        return $cached;
    }

    global $wpdb;
    $row = $wpdb->get_row(
        "SELECT MIN(CAST(meta_value AS DECIMAL(10,2))) AS min_p,
                MAX(CAST(meta_value AS DECIMAL(10,2))) AS max_p
         FROM {$wpdb->postmeta} pm
         INNER JOIN {$wpdb->posts} p ON p.ID = pm.post_id
         WHERE pm.meta_key = '_price'
           AND p.post_status = 'publish'
           AND p.post_type IN ('product','product_variation')
           AND pm.meta_value != ''",
        ARRAY_A
    );

    if (! $row || $row['min_p'] === null) {
        $range = [0, 1000];
    } else {
        $range = [(int) floor((float) $row['min_p']), (int) ceil((float) $row['max_p'])];
    }

    set_transient($cache_key, $range, 10 * MINUTE_IN_SECONDS);

    return $range;
}

/**
 * Invalidate shop filter caches when categories or products change.
 */
add_action('edited_product_cat', __NAMESPACE__.'\\shop_flush_caches');
add_action('create_product_cat', __NAMESPACE__.'\\shop_flush_caches');
add_action('delete_product_cat', __NAMESPACE__.'\\shop_flush_caches');
add_action('save_post_product', __NAMESPACE__.'\\shop_flush_caches');

function shop_flush_caches(): void
{
    delete_transient('natura_shop_categories');
    delete_transient('natura_shop_price_range');
    delete_transient('natura_shop_attr_'.SHOP_TAXONOMY_FORMAT);
    delete_transient('natura_shop_attr_'.SHOP_TAXONOMY_FEATURES);
}
