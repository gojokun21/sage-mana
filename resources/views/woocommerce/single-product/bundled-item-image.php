<?php
/**
 * Bundled Product Image template
 *
 * Override this template by copying it to 'yourtheme/woocommerce/single-product/bundled-item-image.php'.
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

?>
<div class="bundled-item-image-wrapper">
	<?php

	if ( has_post_thumbnail( $product_id ) ) {
		$image_post_id = get_post_thumbnail_id( $product_id );
		$image_alt     = trim( wp_strip_all_tags( get_post_meta( $image_post_id, '_wp_attachment_image_alt', true ) ) );
		$image_title   = esc_attr( get_the_title( $image_post_id ) );
		$image         = get_the_post_thumbnail(
			$product_id,
			'woocommerce_thumbnail',
			array(
				'alt'   => $image_alt ? $image_alt : $image_title,
				'class' => 'bundled-product-thumbnail',
			)
		);
		$product_link  = get_permalink( $product_id );

		$html = '<figure class="bundled_product_image">';
		$html .= '<a href="' . esc_url( $product_link ) . '" class="bundled-image-link" target="_blank">' . $image . '</a>';
		$html .= '</figure>';

	} else {

		$html  = '<figure class="bundled_product_image bundled_product_image--placeholder">';
		$html .= sprintf( '<img class="bundled-product-thumbnail" src="%1$s" alt="%2$s"/>', wc_placeholder_img_src(), __( 'Bundled product placeholder image', 'woocommerce-product-bundles' ) );
		$html .= '</figure>';
	}

	echo apply_filters( 'woocommerce_bundled_product_image_html', $html, $product_id, $bundled_item ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

	?>
</div>
