<?php
/**
 * Single Product Price
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/price.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product;

?>
<ul class="product-trust-strip" aria-label="<?php esc_attr_e( 'Garanții livrare și plată', 'sage' ); ?>">
	<li>
		<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
			<path d="M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h2"/>
			<path d="M15 18H9"/>
			<path d="M19 18h2a1 1 0 0 0 1-1v-3.65a1 1 0 0 0-.22-.624l-3.48-4.35A1 1 0 0 0 17.52 8H14"/>
			<circle cx="17" cy="18" r="2"/>
			<circle cx="7" cy="18" r="2"/>
		</svg>
		<span><?php esc_html_e( 'Livrare 24-48h', 'sage' ); ?></span>
	</li>
	<li>
		<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
			<path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/>
			<path d="M3 3v5h5"/>
		</svg>
		<span><?php esc_html_e( '14 zile retur', 'sage' ); ?></span>
	</li>
	<li>
		<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
			<rect width="18" height="11" x="3" y="11" rx="2" ry="2"/>
			<path d="M7 11V7a5 5 0 0 1 10 0v4"/>
		</svg>
		<span><?php esc_html_e( 'Plată 100% sigură', 'sage' ); ?></span>
	</li>
</ul>
<?php
// Date pentru afișarea reducerii (Black Friday / sale): preț întreg vs. preț activ.
$mn_regular = (float) $product->get_regular_price();
$mn_active  = (float) $product->get_price();
$mn_on_sale = $product->is_on_sale() && $mn_regular > 0 && $mn_active > 0 && $mn_active < $mn_regular;
$mn_pct     = $mn_on_sale ? (int) round( ( ( $mn_regular - $mn_active ) / $mn_regular ) * 100 ) : 0;

$mn_price_class = apply_filters( 'woocommerce_product_price_class', 'price price_custom' );
if ( $mn_on_sale ) {
	$mn_price_class .= ' is-onsale';
}
?>
<p class="<?php echo esc_attr( $mn_price_class ); ?>">
	<?php echo $product->get_price_html(); ?>
	<?php if ( $mn_on_sale && $mn_pct > 0 ) : ?>
		<span class="price_custom__badge">-<?php echo esc_html( $mn_pct ); ?>%</span>
	<?php endif; ?>
</p>
