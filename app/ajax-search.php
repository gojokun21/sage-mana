<?php

/**
 * WooCommerce AJAX Product Search.
 */

namespace App;

use WP_Query;

/**
 * Localize search script with AJAX URL and nonce.
 */
add_action('wp_enqueue_scripts', function () {
    // The script is already enqueued by Vite, so we just localize it.
    // We need to use a hook that fires after Vite enqueues the script.
    add_action('wp_footer', function () {
        echo '<script>var wc_search_ajax = ' . json_encode([
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('wc_search_nonce'),
        ]) . ';</script>';
    }, 5);
});

/**
 * AJAX search handler.
 */
add_action('wp_ajax_wc_custom_search', __NAMESPACE__ . '\\wc_custom_search');
add_action('wp_ajax_nopriv_wc_custom_search', __NAMESPACE__ . '\\wc_custom_search');

function wc_custom_search()
{
    check_ajax_referer('wc_search_nonce', 'nonce');

    if (! isset($_POST['search'])) {
        wp_die();
    }

    $s = sanitize_text_field($_POST['search']);

    $args = [
        'post_type' => 'product',
        'posts_per_page' => 5,
        'post_status' => 'publish',
        'orderby' => 'title',
        'order' => 'ASC',
        'wc_title_search' => $s,
    ];

    add_filter('posts_where', __NAMESPACE__ . '\\wc_search_only_title', 10, 2);
    $q = new WP_Query($args);
    remove_filter('posts_where', __NAMESPACE__ . '\\wc_search_only_title', 10);

    $total_results = $q->found_posts;

    if ($q->have_posts()) {
        echo '<div role="listbox" aria-label="Rezultate căutare">';

        $index = 0;
        while ($q->have_posts()) {
            $q->the_post();

            $product = wc_get_product(get_the_ID());
            if (! $product) {
                continue;
            }

            $img = get_the_post_thumbnail_url(get_the_ID(), 'woocommerce_thumbnail');
            if (! $img) {
                $img = wc_placeholder_img_src();
            }

            $price = $product->get_price_html();
            $link = esc_url(get_permalink());
            $title = esc_html(get_the_title());
            $img = esc_url($img);

            echo '<a href="' . $link . '" class="wc-search-item" role="option" id="wc-search-item-' . $index . '" data-index="' . $index . '">';
            echo '  <div class="wc-search-thumb"><img src="' . $img . '" alt="' . $title . '"></div>';
            echo '  <div class="wc-search-info"><h4>' . $title . '</h4><span class="price">' . $price . '</span></div>';
            echo '</a>';

            $index++;
        }

        echo '</div>';

        if ($total_results > 5) {
            $search_url = home_url('/?s=' . urlencode($s) . '&post_type=product');
            echo '<a href="' . esc_url($search_url) . '" class="wc-search-view-all">';
            echo 'Afișează toate rezultatele (' . intval($total_results) . ')';
            echo '</a>';
        }
    } else {
        echo '<div class="wc-search-item wc-no-results">Niciun produs găsit</div>';
    }

    wp_reset_postdata();
    wp_die();
}

/**
 * Search synonyms for Romanian/EN product names.
 */
function wc_search_synonyms($term)
{
    $synonyms = [
        'colagen' => ['collagen'],
        'collagen' => ['colagen'],
        'proteina' => ['protein', 'whey'],
        'protein' => ['proteina', 'proteină'],
        'proteină' => ['protein', 'proteina'],
        'vitamine' => ['vitamins', 'vitamin'],
        'vitamin' => ['vitamine', 'vitamina'],
        'vitamina' => ['vitamin', 'vitamine'],
        'magneziu' => ['magnesium'],
        'magnesium' => ['magneziu'],
        'creatina' => ['creatine'],
        'creatine' => ['creatina', 'creatină'],
        'creatină' => ['creatine', 'creatina'],
        'omega' => ['omega-3', 'omega 3'],
    ];

    $term_lower = mb_strtolower($term, 'UTF-8');
    $variations = [$term];

    foreach ($synonyms as $key => $alts) {
        if (mb_strpos($term_lower, $key) !== false) {
            foreach ($alts as $alt) {
                $variations[] = str_ireplace($key, $alt, $term);
            }
        }
    }

    return array_unique($variations);
}

/**
 * Filter to search only in post title with synonym support.
 */
function wc_search_only_title($where, $query)
{
    global $wpdb;

    if (! empty($query->query['wc_title_search'])) {
        $search = $query->query['wc_title_search'];
        $variations = wc_search_synonyms($search);

        $clauses = [];
        foreach ($variations as $v) {
            $like = '%' . $wpdb->esc_like($v) . '%';
            $clauses[] = $wpdb->prepare("{$wpdb->posts}.post_title LIKE %s", $like);
        }

        $where .= ' AND (' . implode(' OR ', $clauses) . ')';
    }

    return $where;
}
