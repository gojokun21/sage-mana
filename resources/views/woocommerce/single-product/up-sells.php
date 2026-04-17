<?php
/**
 * Single Product Up-Sells
 *
 * Custom: rendered as Swiper slider to match `.related_products_slider`.
 * Init lives in resources/js/single-product.js.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$upsells = is_array( $upsells )
	? array_filter( $upsells, function ( $p ) {
		return $p instanceof WC_Product && $p->is_visible();
	} )
	: array();

if ( ! empty( $upsells ) ) : ?>

	<section class="up-sells upsells products">

		<div class="upsells-header">
			<?php
			$heading = apply_filters( 'woocommerce_product_upsells_products_heading', __( 'You may also like&hellip;', 'woocommerce' ) );

			if ( $heading ) : ?>
				<h2 class="upsells-heading"><?php echo esc_html( $heading ); ?></h2>
			<?php endif; ?>
			<div class="upsells-nav-arrows">
				<div class="swiper-button-prev upsells-prev">
					<img src="<?php echo get_template_directory_uri(); ?>/assets/images/left-nav.svg" alt="">
				</div>
				<div class="swiper-button-next upsells-next">
					<img src="<?php echo get_template_directory_uri(); ?>/assets/images/right-nav.svg" alt="">
				</div>
			</div>
		</div>

		<div class="swiper upsells_products_slider">
			<div class="swiper-wrapper">
				<?php foreach ( $upsells as $upsell ) : ?>
					<?php
					$post_object = get_post( $upsell->get_id() );
					setup_postdata( $GLOBALS['post'] = $post_object ); // phpcs:ignore
					?>
					<div class="swiper-slide upsells-slide">
						<ul class="products upsells-slide__list">
							<?php wc_get_template_part( 'content', 'product' ); ?>
						</ul>
					</div>
				<?php endforeach; ?>
			</div>
			<div class="swiper-pagination upsells-pagination"></div>
		</div>

	</section>

<?php
endif;

wp_reset_postdata();
