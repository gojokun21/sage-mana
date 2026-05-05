<?php
/**
 * Bundle add-to-cart button template
 *
 * Override this template by copying it to 'yourtheme/woocommerce/single-product/add-to-cart/bundle-button.php'.
 *
 * On occasion, this template file may need to be updated and you (the theme developer) will need to copy the new files to your theme to maintain compatibility.
 * We try to do this as little as possible, but it does happen.
 * When this occurs the version of the template file will be bumped and the readme will list any important changes.
 *
 * @version 6.21.0
 * @package WooCommerce Product Bundles
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $product;

// Get product image
$image_id = $product->get_image_id();
$product_image = $image_id ? wp_get_attachment_image_url( $image_id, 'medium' ) : wc_placeholder_img_src( 'medium' );

// Get short description for packaging info
$short_desc = wp_strip_all_tags( $product->get_short_description() );
$short_desc = mb_substr( $short_desc, 0, 100 );

$mn_purchasable = $product->is_purchasable() && $product->is_in_stock();
$mn_brand       = \App\resolve_product_brand( $product );
$mn_category    = \App\resolve_product_category( $product );

?>
<button type="submit" name="add-to-cart" value="<?php echo esc_attr( $product->get_id() ); ?>"
		class="<?php echo isset( $button_class ) ? esc_attr( $button_class ) : 'single_add_to_cart_button bundle_add_to_cart_button button alt'; ?> mn-atc-btn <?php echo ! $mn_purchasable ? 'mn-hidden' : ''; ?>"
		data-product_id="<?php echo esc_attr( $product->get_id() ); ?>"
		data-product_sku="<?php echo esc_attr( $product->get_sku() ); ?>"
		data-product_name="<?php echo esc_attr( $product->get_name() ); ?>"
		data-product_price="<?php echo esc_attr( (string) wc_format_decimal( $product->get_price(), wc_get_price_decimals() ) ); ?>"
		data-product_brand="<?php echo esc_attr( $mn_brand ); ?>"
		<?php if ( $mn_category ) : ?>data-product_category="<?php echo esc_attr( $mn_category ); ?>"<?php endif; ?>
		data-product_url="<?php echo esc_url( get_permalink( $product->get_id() ) ); ?>"
		data-product_img="<?php echo esc_url( $product_image ); ?>"
		data-product_packaging="<?php echo esc_attr( $short_desc ); ?>">
	<?php echo esc_html( $product->single_add_to_cart_text() ); ?>
</button>

<span class="single_add_to_cart_button button alt btn_unavailable mn-unavail-btn <?php echo $mn_purchasable ? 'mn-hidden' : ''; ?>"
      data-product_id="<?php echo esc_attr( $product->get_id() ); ?>">
    Pachet indisponibil
</span>
