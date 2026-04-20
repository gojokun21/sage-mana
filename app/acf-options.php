<?php

/**
 * ACF options pages.
 */

namespace App;

add_action('acf/init', function () {
    if (! function_exists('acf_add_options_page')) {
        return;
    }

    acf_add_options_page([
        'page_title' => 'Common Settings',
        'menu_title' => 'Common Settings',
        'menu_slug'  => 'common-settings',
        'capability' => 'edit_posts',
        'redirect'   => false,
        'icon_url'   => 'dashicons-admin-settings',
    ]);

    acf_add_options_sub_page([
        'page_title'  => 'Discount Upsell',
        'menu_title'  => 'Discount Upsell',
        'parent_slug' => 'common-settings',
        'menu_slug'   => 'common-settings-discount-upsell',
        'capability'  => 'edit_posts',
    ]);

    acf_add_options_sub_page([
        'page_title'  => 'Recenzii',
        'menu_title'  => 'Recenzii',
        'parent_slug' => 'common-settings',
        'menu_slug'   => 'common-settings-recenzii',
        'capability'  => 'edit_posts',
    ]);

    acf_add_options_sub_page([
        'page_title'  => 'Testimoniale',
        'menu_title'  => 'Testimoniale',
        'parent_slug' => 'common-settings',
        'menu_slug'   => 'common-settings-testimoniale',
        'capability'  => 'edit_posts',
    ]);

    acf_add_options_page([
        'page_title' => 'Meniu',
        'menu_title' => 'Meniu',
        'menu_slug'  => 'Meniu',
        'capability' => 'edit_posts',
        'redirect'   => false,
        'icon_url'   => 'dashicons-admin-settings',
    ]);
});
