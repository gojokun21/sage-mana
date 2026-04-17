<?php
/**
 * Single Product tabs
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/tabs/tabs.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.8.0
 */

if (!defined('ABSPATH')) exit;

$product_tabs = apply_filters('woocommerce_product_tabs', array());

if (empty($product_tabs)) return;
?>

<div class="custom-product-tabs">

    <h2 class="product_h2 text-center fw-semibold">Informații despre produs</h2>

    <div class="custom-tabs-nav">
        <?php foreach ($product_tabs as $key => $tab) : ?>
            <button class="custom-tab-btn" data-tab="<?php echo esc_attr($key); ?>">
                <?php echo esc_html($tab['title']); ?>
            </button>
        <?php endforeach; ?>
    </div>

    <div class="custom-tabs-content">
        <?php foreach ($product_tabs as $key => $tab) : ?>
            <div class="custom-tab-accordion-item">
                <button class="custom-accordion-btn" data-tab="<?php echo esc_attr($key); ?>">
                    <?php echo esc_html($tab['title']); ?>
                    <svg class="accordion-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M201.4 374.6c12.5 12.5 32.8 12.5 45.3 0l160-160c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L224 306.7 86.6 169.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3l160 160z"/></svg>
                </button>
                <div class="custom-tab-panel" id="tab-<?php echo esc_attr($key); ?>">
                    <?php
                    if (isset($tab['callback'])) {
                        call_user_func($tab['callback'], $key, $tab);
                    }
                    ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php
// Customer reviews slider (same ACF options source as home page).
if (function_exists('Roots\\view')) {
    echo \Roots\view('partials.home.reviews')->render();
}
?>

