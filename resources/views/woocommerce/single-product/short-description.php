<?php
/**
 * Single product short description
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/short-description.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

global $post;

$short_description = apply_filters( 'woocommerce_short_description', $post->post_excerpt );

if ( ! $short_description ) {
	return;
}

?>
<div class="woocommerce-product-details__short-description">
	<?php echo $short_description; // WPCS: XSS ok. ?>
</div>

<?php if (have_rows('componente_pachete')): ?>
<div class="product_part_of">
    <span class="part_of_label">Conținut:</span>
    <div class="part_of_list">
        <?php while (have_rows('componente_pachete')): the_row();
            $componenta = get_sub_field('componente');
            if ($componenta): ?>
                <div class="part_of_item">
                    <span><?php echo esc_html(get_the_title($componenta->ID)); ?></span>
                </div>
            <?php endif; ?>
        <?php endwhile; ?>
    </div>
</div>
<?php endif; ?>

<?php if (have_rows('informatie_generala')): ?>
    <?php while (have_rows('informatie_generala')): the_row(); ?>

        <?php if (have_rows('beneficii')): ?>
            <div class="benefits_product">
                <?php while (have_rows('beneficii')): the_row(); ?>
                    <h2><?php echo esc_html(get_sub_field('denumire_beneficiu')); ?></h2>
                <?php endwhile; ?>
            </div>
        <?php endif; ?>

    <?php endwhile; ?>
<?php endif; ?>
