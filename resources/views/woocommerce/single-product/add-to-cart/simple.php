<?php
/**
 * Simple product add to cart.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 10.2.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

if ( ! $product ) {
    return;
}

$in_stock     = $product->is_in_stock();
$purchasable  = $product->is_purchasable();
$can_buy      = $purchasable && $in_stock;

// Always surface stock status (in-stock, low-stock, out-of-stock).
echo wc_get_stock_html( $product );

?>
<?php do_action( 'woocommerce_before_add_to_cart_form' ); ?>

<div class="woocommerce-ajax-add-to-cart">
    <?php
    do_action( 'woocommerce_before_add_to_cart_button' );
    do_action( 'woocommerce_before_add_to_cart_quantity' );
    ?>

    <?php if ( $can_buy ) : ?>
        <div class="mn-qty-wrap">
            <?php
            woocommerce_quantity_input( array(
                'min_value'   => $product->get_min_purchase_quantity(),
                'max_value'   => $product->get_max_purchase_quantity(),
                'input_value' => 1,
            ) );
            ?>
        </div>
    <?php endif; ?>

    <?php do_action( 'woocommerce_after_add_to_cart_quantity' ); ?>

    <?php if ( $can_buy ) : ?>
        <a href="<?php echo esc_url( $product->add_to_cart_url() ); ?>"
           data-quantity="1"
           data-product_id="<?php echo esc_attr( $product->get_id() ); ?>"
           data-product_sku="<?php echo esc_attr( $product->get_sku() ); ?>"
           data-product_name="<?php echo esc_attr( $product->get_name() ); ?>"
           data-product_img="<?php echo esc_url( wp_get_attachment_image_url( $product->get_image_id(), 'medium' ) ); ?>"
           data-product_url="<?php echo esc_url( get_permalink( $product->get_id() ) ); ?>"
           data-product_packaging="<?php echo esc_attr( wp_strip_all_tags( $product->get_short_description() ) ); ?>"
           class="button product_type_simple add_to_cart_button ajax_add_to_cart mn-atc-btn">
            <?php echo esc_html( $product->single_add_to_cart_text() ); ?>
        </a>
    <?php else : ?>
        <span class="btn-primary btn-unavailable mn-unavail-btn"
              data-product_id="<?php echo esc_attr( $product->get_id() ); ?>"
              aria-disabled="true">
            <?php esc_html_e( 'Stoc epuizat', 'sage' ); ?>
        </span>
    <?php endif; ?>

    <?php do_action( 'woocommerce_after_add_to_cart_button' ); ?>
</div>

<?php do_action( 'woocommerce_after_add_to_cart_form' ); ?>
