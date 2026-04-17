<?php

/**
 * Home template helpers — cache invalidation for the data the Home composer
 * caches via Transients.
 */

namespace App;

/**
 * Invalidate the top-categories transient on any product_cat mutation.
 */
add_action('created_product_cat', __NAMESPACE__ . '\\bust_home_categories_cache');
add_action('edited_product_cat', __NAMESPACE__ . '\\bust_home_categories_cache');
add_action('delete_product_cat', __NAMESPACE__ . '\\bust_home_categories_cache');

function bust_home_categories_cache(): void
{
    delete_transient('natura_home_top_categories');
}

/**
 * Invalidate home product-label transients on product save/delete/stock change.
 * Matches every label-based query we might add (popular_packages, promo, etc.).
 */
add_action('save_post_product', __NAMESPACE__ . '\\bust_home_products_cache');
add_action('deleted_post', __NAMESPACE__ . '\\bust_home_products_cache');
add_action('woocommerce_product_set_stock_status', __NAMESPACE__ . '\\bust_home_products_cache');

function bust_home_products_cache(): void
{
    delete_transient('natura_home_popular_packages');
    delete_transient('natura_home_promo_products');
    delete_transient('natura_home_new_products');
}
