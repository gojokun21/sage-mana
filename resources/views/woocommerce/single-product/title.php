<div class="product_title_wrapper">
<?php
/**
 * Single Product title
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/title.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see        https://woocommerce.com/document/template-structure/
 * @package    WooCommerce\Templates
 * @version    1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Unele titluri sunt stocate cu entități HTML (ex. „&amp;” în loc de „&”), iar
// the_title() le mai escapează o dată → „&amp;amp;”. Decodăm complet (orice număr
// de straturi) la caractere literale, apoi escapăm O SINGURĂ dată. Idempotent.
$mn_title = get_the_title();
$mn_prev  = null;
while ( $mn_title !== $mn_prev ) {
	$mn_prev  = $mn_title;
	$mn_title = html_entity_decode( $mn_title, ENT_QUOTES, 'UTF-8' );
}
echo '<h1 class="single_product_title entry-title">' . esc_html( $mn_title ) . '</h1>';
?>
</div>
