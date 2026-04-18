<?php

/**
 * Theme setup.
 */

namespace App;

use Illuminate\Support\Facades\Vite;

/**
 * Inject styles into the block editor.
 *
 * @return array
 */
add_filter('block_editor_settings_all', function ($settings) {
    $style = Vite::asset('resources/css/editor.css');

    $settings['styles'][] = [
        'css' => "@import url('{$style}')",
    ];

    return $settings;
});

/**
 * Inject scripts into the block editor.
 *
 * @return void
 */
add_action('admin_head', function () {
    if (! get_current_screen()?->is_block_editor()) {
        return;
    }

    if (! Vite::isRunningHot()) {
        $dependencies = json_decode(Vite::content('editor.deps.json'));

        foreach ($dependencies as $dependency) {
            if (! wp_script_is($dependency)) {
                wp_enqueue_script($dependency);
            }
        }
    }
    echo Vite::withEntryPoints([
        'resources/js/editor.js',
    ])->toHtml();
});

/**
 * Use the generated theme.json file.
 *
 * @return string
 */
add_filter('theme_file_path', function ($path, $file) {
    return $file === 'theme.json'
        ? public_path('build/assets/theme.json')
        : $path;
}, 10, 2);

/**
 * Disable on-demand block asset loading.
 *
 * @link https://core.trac.wordpress.org/ticket/61965
 */
add_filter('should_load_separate_core_block_assets', '__return_false');

/**
 * Register the initial theme setup.
 *
 * @return void
 */
add_action('after_setup_theme', function () {
    /**
     * Disable full-site editing support.
     *
     * @link https://wptavern.com/gutenberg-10-5-embeds-pdfs-adds-verse-block-color-options-and-introduces-new-patterns
     */
    remove_theme_support('block-templates');

    /**
     * Register the navigation menus.
     *
     * @link https://developer.wordpress.org/reference/functions/register_nav_menus/
     */
    register_nav_menus([
        'primary_navigation' => __('Primary Navigation', 'sage'),
    ]);

    /**
     * Disable the default block patterns.
     *
     * @link https://developer.wordpress.org/block-editor/developers/themes/theme-support/#disabling-the-default-block-patterns
     */
    remove_theme_support('core-block-patterns');

    /**
     * Enable plugins to manage the document title.
     *
     * @link https://developer.wordpress.org/reference/functions/add_theme_support/#title-tag
     */
    add_theme_support('title-tag');

    /**
     * Enable post thumbnail support.
     *
     * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
     */
    add_theme_support('post-thumbnails');

    /**
     * Enable responsive embed support.
     *
     * @link https://developer.wordpress.org/block-editor/how-to-guides/themes/theme-support/#responsive-embedded-content
     */
    add_theme_support('responsive-embeds');

    /**
     * Enable HTML5 markup support.
     *
     * @link https://developer.wordpress.org/reference/functions/add_theme_support/#html5
     */
    add_theme_support('html5', [
        'caption',
        'comment-form',
        'comment-list',
        'gallery',
        'search-form',
        'script',
        'style',
    ]);

    /**
     * Enable selective refresh for widgets in customizer.
     *
     * @link https://developer.wordpress.org/reference/functions/add_theme_support/#customize-selective-refresh-widgets
     */
    add_theme_support('customize-selective-refresh-widgets');

    /**
     * Enable WooCommerce support.
     *
     * @link https://woocommerce.com/document/declaring-woocommerce-support-in-themes/
     */
    add_theme_support('woocommerce');
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');

    /**
     * Intermediate image size that fills the srcset gap between `medium`
     * (300w) and `medium_large` (768w). Without it, Retina mobile (DPR=2)
     * wanting ~400px picks the 768 variant (smallest candidate >= 400) —
     * way oversized for 120–200px product cards and ~190px testimonial
     * thumbs.
     *
     * Width 500, no hard crop, unconstrained height → scales to preserve
     * aspect ratio. Works across squares (1000x1000 product → 500x500),
     * portraits (768x1004 testimonial → 500x652), and landscapes
     * (882x600 review banner → 500x340). WP keeps it in srcset whenever
     * the aspect matches the base image.
     *
     * NOTE: After deploy, run `wp media regenerate --yes` so existing
     * attachments pick up this size.
     */
    add_image_size('natura-mid', 500, 9999, false);
}, 20);

/**
 * Register the theme sidebars.
 *
 * @return void
 */
add_action('widgets_init', function () {
    $config = [
        'before_widget' => '<section class="widget %1$s %2$s">',
        'after_widget' => '</section>',
        'before_title' => '<h3>',
        'after_title' => '</h3>',
    ];

    register_sidebar([
        'name' => __('Primary', 'sage'),
        'id' => 'sidebar-primary',
    ] + $config);

    register_sidebar([
        'name' => __('Footer', 'sage'),
        'id' => 'sidebar-footer',
    ] + $config);
});

/**
 * Per-page CSS bundle resolver.
 *
 * Returns the list of Vite inputs for the current request: always the
 * universal app.css + app.js, plus any page-specific bundle that matches.
 * Called from layouts/app.blade.php so the page-specific CSS ships only
 * where it's actually used, keeping the home-page critical CSS payload
 * small (the biggest win for LCP on a mostly-CSS-bound first paint).
 *
 * Conditionals intentionally broad (OR-ed checks) so a page template
 * AND a front-page/cart/etc. detection can both trigger — we'd rather
 * ship a bundle once when unneeded than miss it and render unstyled.
 */
function page_bundles(): array
{
    $bundles = ['resources/css/app.css', 'resources/js/app.js'];

    // Sage/Acorn stores the template slug in a format that doesn't always
    // match `is_page_template('views/template-X.blade.php')` across versions
    // (it can be `template-X.blade.php`, `views/template-X.blade.php`, etc.).
    // A substring check on the slug is robust to that — matches any of the
    // shapes WP returns. Follows the same pattern as app/favorites.php:178.
    $template_slug = (string) get_page_template_slug();

    $template_has = static fn (string $needle): bool
        => $template_slug !== '' && str_contains($template_slug, $needle);

    // Home: either set as static front page, or any page using the Home template.
    if (is_front_page() || $template_has('template-home')) {
        $bundles[] = 'resources/css/home-bundle.css';
    }

    // Cart + Checkout pages. WC's helpers return false on non-WC contexts
    // so the function_exists guard is just for admin / REST safety.
    if (function_exists('is_cart') && (is_cart() || is_checkout())) {
        $bundles[] = 'resources/css/cart-bundle.css';
    }

    // My Account (all sub-pages: dashboard, orders, addresses, login/register
    // shown to logged-out users, lost-password flow, etc.).
    if (function_exists('is_account_page') && is_account_page()) {
        $bundles[] = 'resources/css/account-bundle.css';
    }

    if ($template_has('template-about')) {
        $bundles[] = 'resources/css/about-bundle.css';
    }

    if ($template_has('template-contact')) {
        $bundles[] = 'resources/css/contact-bundle.css';
    }

    // Blog: any post type = 'post' view, archives, categories, tags, blog template.
    if (is_singular('post') || is_home() || is_archive() || is_category() || is_tag()
        || $template_has('template-blog')) {
        $bundles[] = 'resources/css/blog-bundle.css';
    }

    if (is_404()) {
        $bundles[] = 'resources/css/page-404-bundle.css';
    }

    return $bundles;
}
