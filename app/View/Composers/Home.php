<?php

namespace App\View\Composers;

use Roots\Acorn\View\Composer;

class Home extends Composer
{
    /**
     * List of views served by this composer.
     *
     * @var array
     */
    protected static $views = [
        'template-home',
    ];

    /**
     * Data passed to the view.
     */
    public function with(): array
    {
        return [
            'top_categories' => $this->topCategories(),
            'popular_packages' => $this->popularPackages(),
            'new_products' => $this->newProducts(),
            'promo_products' => $this->promoProducts(),
        ];
    }

    /**
     * Products tagged as "pachete" via ACF `eticheta_produs`.
     * Newest first, in-stock only, max 10. Cached 10 min.
     *
     * @return \WC_Product[]
     */
    public function popularPackages(): array
    {
        return $this->productsByLabel('pachete', 'natura_home_popular_packages');
    }

    /**
     * Products tagged as "promo" via ACF `eticheta_produs` — bestsellers strip.
     *
     * @return \WC_Product[]
     */
    public function promoProducts(): array
    {
        return $this->productsByLabel('promo', 'natura_home_promo_products');
    }

    /**
     * Newest products, excluding the "pachete" category and out-of-stock items.
     *
     * @return \WC_Product[]
     */
    public function newProducts(int $limit = 10): array
    {
        $cache_key = 'natura_home_new_products';
        $cached = get_transient($cache_key);
        $ids = is_array($cached) ? $cached : null;

        if ($ids === null) {
            $q = new \WP_Query([
                'post_type' => 'product',
                'post_status' => 'publish',
                'posts_per_page' => $limit,
                'orderby' => 'date',
                'order' => 'DESC',
                'fields' => 'ids',
                'no_found_rows' => true,
                'tax_query' => [
                    'relation' => 'AND',
                    [
                        'taxonomy' => 'product_cat',
                        'field' => 'slug',
                        'terms' => 'pachete',
                        'operator' => 'NOT IN',
                    ],
                    [
                        'taxonomy' => 'product_visibility',
                        'field' => 'name',
                        'terms' => 'outofstock',
                        'operator' => 'NOT IN',
                    ],
                ],
            ]);

            $ids = $q->posts ?: [];

            if (! empty($ids)) {
                set_transient($cache_key, $ids, 10 * MINUTE_IN_SECONDS);
            }
        }

        if (empty($ids) || ! function_exists('wc_get_product')) {
            return [];
        }

        return array_values(array_filter(array_map('wc_get_product', $ids)));
    }

    /**
     * Shared helper for ACF `eticheta_produs = <label>` product queries.
     * Returns WC_Product instances, skipping invalid ones.
     *
     * @return \WC_Product[]
     */
    private function productsByLabel(string $label, string $cache_key, int $limit = 10): array
    {
        $cached = get_transient($cache_key);
        $ids = is_array($cached) ? $cached : null;

        if ($ids === null) {
            // Use raw WP_Query to match legacy behavior exactly and avoid
            // wc_get_products() argument quirks around meta_query.
            $q = new \WP_Query([
                'post_type' => 'product',
                'post_status' => 'publish',
                'posts_per_page' => $limit,
                'orderby' => 'date',
                'order' => 'DESC',
                'fields' => 'ids',
                'no_found_rows' => true,
                'meta_query' => [
                    [
                        'key' => 'eticheta_produs',
                        'value' => $label,
                        'compare' => '=',
                    ],
                ],
                'tax_query' => [
                    [
                        'taxonomy' => 'product_visibility',
                        'field' => 'name',
                        'terms' => 'outofstock',
                        'operator' => 'NOT IN',
                    ],
                ],
            ]);

            $ids = $q->posts ?: [];

            // Only cache non-empty results so a first-time hit with no
            // matching products doesn't stay stale for 10 minutes.
            if (! empty($ids)) {
                set_transient($cache_key, $ids, 10 * MINUTE_IN_SECONDS);
            }
        }

        if (empty($ids) || ! function_exists('wc_get_product')) {
            return [];
        }

        return array_values(array_filter(array_map('wc_get_product', $ids)));
    }

    /**
     * Top-level product categories (parent = 0), non-empty, excluding `pachete`.
     * Cached via Transients for 10 minutes; invalidated on category edits below.
     *
     * @return \WP_Term[]
     */
    public function topCategories(): array
    {
        if (! function_exists('get_terms')) {
            return [];
        }

        $cache_key = 'natura_home_top_categories';
        $cached = get_transient($cache_key);

        if (is_array($cached)) {
            return $cached;
        }

        $terms = get_terms([
            'taxonomy' => 'product_cat',
            'hide_empty' => true,
            'parent' => 0,
            'orderby' => 'name',
            'order' => 'ASC',
        ]);

        if (is_wp_error($terms) || empty($terms)) {
            return [];
        }

        $filtered = array_values(array_filter($terms, fn ($t) => $t->slug !== 'pachete'));

        set_transient($cache_key, $filtered, 10 * MINUTE_IN_SECONDS);

        return $filtered;
    }
}
