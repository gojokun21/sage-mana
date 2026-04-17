<?php
/**
 * Product quantity inputs — canonical .qty-stepper output.
 *
 * Overrides WooCommerce's default template so every call to
 * `woocommerce_quantity_input()` (cart, single product, grouped, variations…)
 * emits the same visual component. Styling lives in resources/css/qty-stepper.css;
 * +/- behavior in resources/js/qty-stepper.js.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 10.1.0
 */

defined('ABSPATH') || exit;

if ($max_value && $min_value === $max_value) {
    ?>
    <div class="quantity hidden">
        <input type="hidden" name="<?php echo esc_attr($input_name); ?>" value="<?php echo esc_attr($min_value); ?>">
    </div>
    <?php
    return;
}
?>
<div class="quantity qty-stepper" data-qty-stepper>
    <?php
    /**
     * Preserve WC extensibility: let plugins (Min/Max Quantities, Subscriptions,
     * Measurement Price Calculator, etc.) inject markup before/after the input.
     */
    do_action('woocommerce_before_quantity_input_field');
    ?>

    <button type="button" class="qty-stepper__btn" data-qty-ctrl="dec" aria-label="<?php esc_attr_e('Scade cantitatea', 'sage'); ?>">&minus;</button>

    <input
        type="number"
        id="<?php echo esc_attr($input_id); ?>"
        class="qty-stepper__input input-text qty text"
        step="<?php echo esc_attr($step); ?>"
        min="<?php echo esc_attr($min_value); ?>"
        max="<?php echo esc_attr(0 < $max_value ? $max_value : ''); ?>"
        name="<?php echo esc_attr($input_name); ?>"
        value="<?php echo esc_attr($input_value); ?>"
        title="<?php echo esc_attr_x('Qty', 'Product quantity input tooltip', 'woocommerce'); ?>"
        size="4"
        readonly
        inputmode="<?php echo esc_attr($inputmode); ?>"
        autocomplete="off" />

    <button type="button" class="qty-stepper__btn" data-qty-ctrl="inc" aria-label="<?php esc_attr_e('Crește cantitatea', 'sage'); ?>">+</button>

    <?php do_action('woocommerce_after_quantity_input_field'); ?>
</div>
