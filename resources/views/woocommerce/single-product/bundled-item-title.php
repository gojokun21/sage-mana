<?php
/**
 * Bundled Item Title template
 *
 * Override this template by copying it to 'yourtheme/woocommerce/single-product/bundled-item-title.php'.
 *
 * On occasion, this template file may need to be updated and you (the theme developer) will need to copy the new files to your theme to maintain compatibility.
 * We try to do this as little as possible, but it does happen.
 * When this occurs the version of the template file will be bumped and the readme will list any important changes.
 *
 * Note: Bundled product properties are accessible via '$bundled_item->product'.
 *
 * @version 8.3.3
 * @package WooCommerce Product Bundles
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( '' === $title ) {
	return;
}

$product_link = get_permalink( $bundled_item->get_product_id() );
?>
<h4 class="bundled_product_title">
	<a href="<?php echo esc_url( $product_link ); ?>" target="_blank" class="bundled-title-link">
		<?php echo esc_html( $bundled_item->get_title() ); ?>
		<svg class="external-link-icon" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
	</a>
</h4>
