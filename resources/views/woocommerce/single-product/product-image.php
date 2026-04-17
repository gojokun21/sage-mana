<?php
/**
 * Single Product Image
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/product-image.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 10.5.0
 */

use Automattic\WooCommerce\Enums\ProductType;

defined( 'ABSPATH' ) || exit;

// Note: `wc_get_gallery_image_html` was added in WC 3.3.2 and did not exist prior. This check protects against theme overrides being used on older versions of WC.
if ( ! function_exists( 'wc_get_gallery_image_html' ) ) {
	return;
}

global $product;

$columns           = apply_filters( 'woocommerce_product_thumbnails_columns', 4 );
$post_thumbnail_id = $product->get_image_id();
$wrapper_classes   = apply_filters(
	'woocommerce_single_product_image_gallery_classes',
	array(
		'woocommerce-product-gallery',
		'woocommerce-product-gallery--' . ( $post_thumbnail_id ? 'with-images' : 'without-images' ),
		'woocommerce-product-gallery--columns-' . absint( $columns ),
		'images',
	)
);
?>
<div class="<?php echo esc_attr( implode( ' ', array_map( 'sanitize_html_class', $wrapper_classes ) ) ); ?>" data-columns="<?php echo esc_attr( $columns ); ?>">
    <div class="woocommerce-product-gallery__wrapper gallery-layout">

        <?php
        if ( $post_thumbnail_id ) :
            $attachment_ids = $product->get_gallery_image_ids();
            array_unshift( $attachment_ids, $post_thumbnail_id );
            ?>

            <div class="product-thumbs-swiper-container">
                <div class="swiper product-thumbs-swiper">
                    <div class="swiper-wrapper">
                        <?php foreach ( $attachment_ids as $attachment_id ) :
                            $thumb_url = wp_get_attachment_image_url( $attachment_id, 'full' );
                            ?>
                            <div class="swiper-slide">
                                <img src="<?php echo esc_url( $thumb_url ); ?>" alt="" />
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <div class="product-main-swiper-container">
                <div class="swiper_bg">
                <div class="swiper product-main-swiper">
                    <div class="swiper-wrapper">
                        <?php foreach ( $attachment_ids as $attachment_id ) :
                            $image_url = wp_get_attachment_image_url( $attachment_id, 'full' );
                            ?>
                            <div class="swiper-slide">
                                <img src="<?php echo esc_url( $image_url ); ?>" alt="" />
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <div class="border_image"></div>
            <?php if (have_rows('informatie_generala')): ?>
            <div class="doses_container">
                <?php while (have_rows('informatie_generala')): the_row(); 
                    $doze = get_sub_field('numarul_de_doze');
                    $doza_zilnica = get_sub_field('doza_zilnica');
                    $cantitatea = isset($doza_zilnica['cantitatea']) ? $doza_zilnica['cantitatea'] : '';
                    $tipul_dozei = isset($doza_zilnica['tipul_dozei']) ? $doza_zilnica['tipul_dozei'] : '';
                    if ($doze || $cantitatea): ?>
                <?php if ($doze): ?>
                <div class="doses_item">
                    <div class="doses_icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <path d="M17 14C17.2652 14 17.5196 13.8946 17.7071 13.7071C17.8946 13.5196 18 13.2652 18 13C18 12.7348 17.8946 12.4804 17.7071 12.2929C17.5196 12.1054 17.2652 12 17 12C16.7348 12 16.4804 12.1054 16.2929 12.2929C16.1054 12.4804 16 12.7348 16 13C16 13.2652 16.1054 13.5196 16.2929 13.7071C16.4804 13.8946 16.7348 14 17 14ZM17 18C17.2652 18 17.5196 17.8946 17.7071 17.7071C17.8946 17.5196 18 17.2652 18 17C18 16.7348 17.8946 16.4804 17.7071 16.2929C17.5196 16.1054 17.2652 16 17 16C16.7348 16 16.4804 16.1054 16.2929 16.2929C16.1054 16.4804 16 16.7348 16 17C16 17.2652 16.1054 17.5196 16.2929 17.7071C16.4804 17.8946 16.7348 18 17 18ZM13 13C13 13.2652 12.8946 13.5196 12.7071 13.7071C12.5196 13.8946 12.2652 14 12 14C11.7348 14 11.4804 13.8946 11.2929 13.7071C11.1054 13.5196 11 13.2652 11 13C11 12.7348 11.1054 12.4804 11.2929 12.2929C11.4804 12.1054 11.7348 12 12 12C12.2652 12 12.5196 12.1054 12.7071 12.2929C12.8946 12.4804 13 12.7348 13 13ZM13 17C13 17.2652 12.8946 17.5196 12.7071 17.7071C12.5196 17.8946 12.2652 18 12 18C11.7348 18 11.4804 17.8946 11.2929 17.7071C11.1054 17.5196 11 17.2652 11 17C11 16.7348 11.1054 16.4804 11.2929 16.2929C11.4804 16.1054 11.7348 16 12 16C12.2652 16 12.5196 16.1054 12.7071 16.2929C12.8946 16.4804 13 16.7348 13 17ZM7 14C7.26522 14 7.51957 13.8946 7.70711 13.7071C7.89464 13.5196 8 13.2652 8 13C8 12.7348 7.89464 12.4804 7.70711 12.2929C7.51957 12.1054 7.26522 12 7 12C6.73478 12 6.48043 12.1054 6.29289 12.2929C6.10536 12.4804 6 12.7348 6 13C6 13.2652 6.10536 13.5196 6.29289 13.7071C6.48043 13.8946 6.73478 14 7 14ZM7 18C7.26522 18 7.51957 17.8946 7.70711 17.7071C7.89464 17.5196 8 17.2652 8 17C8 16.7348 7.89464 16.4804 7.70711 16.2929C7.51957 16.1054 7.26522 16 7 16C6.73478 16 6.48043 16.1054 6.29289 16.2929C6.10536 16.4804 6 16.7348 6 17C6 17.2652 6.10536 17.5196 6.29289 17.7071C6.48043 17.8946 6.73478 18 7 18Z" fill="#09706B"/>
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M6.99998 1.75C7.19889 1.75 7.38965 1.82902 7.53031 1.96967C7.67096 2.11032 7.74998 2.30109 7.74998 2.5V3.263C8.41198 3.25 9.14098 3.25 9.94298 3.25H14.056C14.859 3.25 15.588 3.25 16.25 3.263V2.5C16.25 2.30109 16.329 2.11032 16.4696 1.96967C16.6103 1.82902 16.8011 1.75 17 1.75C17.1989 1.75 17.3897 1.82902 17.5303 1.96967C17.671 2.11032 17.75 2.30109 17.75 2.5V3.327C18.01 3.347 18.2563 3.37233 18.489 3.403C19.661 3.561 20.61 3.893 21.359 4.641C22.107 5.39 22.439 6.339 22.597 7.511C22.75 8.651 22.75 10.106 22.75 11.944V14.056C22.75 15.894 22.75 17.35 22.597 18.489C22.439 19.661 22.107 20.61 21.359 21.359C20.61 22.107 19.661 22.439 18.489 22.597C17.349 22.75 15.894 22.75 14.056 22.75H9.94498C8.10698 22.75 6.65098 22.75 5.51198 22.597C4.33998 22.439 3.39098 22.107 2.64198 21.359C1.89398 20.61 1.56198 19.661 1.40398 18.489C1.25098 17.349 1.25098 15.894 1.25098 14.056V11.944C1.25098 10.106 1.25098 8.65 1.40398 7.511C1.56198 6.339 1.89398 5.39 2.64198 4.641C3.39098 3.893 4.33998 3.561 5.51198 3.403C5.74531 3.37233 5.99164 3.347 6.25098 3.327V2.5C6.25098 2.30126 6.32986 2.11065 6.47029 1.97002C6.61073 1.8294 6.80124 1.75026 6.99998 1.75ZM5.70998 4.89C4.70498 5.025 4.12498 5.279 3.70198 5.702C3.27898 6.125 3.02498 6.705 2.88998 7.71C2.86731 7.88 2.84798 8.05967 2.83198 8.249H21.168C21.152 8.05967 21.1326 7.87967 21.11 7.709C20.975 6.704 20.721 6.124 20.298 5.701C19.875 5.278 19.295 5.024 18.289 4.889C17.262 4.751 15.907 4.749 14 4.749H9.99998C8.09298 4.749 6.73898 4.752 5.70998 4.89ZM2.74998 12C2.74998 11.146 2.74998 10.403 2.76298 9.75H21.237C21.25 10.403 21.25 11.146 21.25 12V14C21.25 15.907 21.248 17.262 21.11 18.29C20.975 19.295 20.721 19.875 20.298 20.298C19.875 20.721 19.295 20.975 18.289 21.11C17.262 21.248 15.907 21.25 14 21.25H9.99998C8.09298 21.25 6.73898 21.248 5.70998 21.11C4.70498 20.975 4.12498 20.721 3.70198 20.298C3.27898 19.875 3.02498 19.295 2.88998 18.289C2.75198 17.262 2.74998 15.907 2.74998 14V12Z" fill="#09706B"/>
                    </svg>
                    </div>
                    <p><span><?php echo esc_html($doze); ?></span> doze</p>
                </div>
                <?php endif; ?>
                <?php if ($cantitatea): ?>
                <div class="doses_item">
                    <div class="doses_icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M14.1322 3.46C13.4315 2.75927 12.5996 2.20341 11.6841 1.82418C10.7685 1.44495 9.78723 1.24976 8.79624 1.24976C7.80526 1.24976 6.82397 1.44495 5.90842 1.82418C4.99287 2.20341 4.16098 2.75927 3.46024 3.46C2.75951 4.16073 2.20366 4.99262 1.82442 5.90818C1.44519 6.82373 1.25 7.80501 1.25 8.796C1.25 9.78699 1.44519 10.7683 1.82442 11.6838C2.20366 12.5994 2.75951 13.4313 3.46024 14.132L9.86824 20.54C11.2834 21.9552 13.2029 22.7502 15.2042 22.7502C17.2056 22.7502 19.125 21.9552 20.5402 20.54C21.9554 19.1248 22.7505 17.2054 22.7505 15.204C22.7505 13.2026 21.9554 11.2832 20.5402 9.868L14.1322 3.46ZM4.52124 4.52C5.65508 3.38626 7.19283 2.74933 8.79624 2.74933C10.3997 2.74933 11.9374 3.38626 13.0712 4.52L15.9692 7.418L15.9582 7.453C15.8682 7.735 15.7152 8.151 15.4682 8.663C14.9742 9.687 14.1032 11.101 12.6022 12.603C11.1012 14.103 9.68724 14.975 8.66224 15.468C8.15024 15.715 7.73524 15.868 7.45324 15.958L7.41824 15.968L4.52124 13.072C3.95972 12.5106 3.51429 11.844 3.21039 11.1104C2.90649 10.3768 2.75008 9.59055 2.75008 8.7965C2.75008 8.00245 2.90649 7.21617 3.21039 6.48258C3.51429 5.74898 3.95972 5.08143 4.52124 4.52ZM8.59024 17.14L10.9282 19.48C12.0623 20.6139 13.6004 21.2509 15.2041 21.2508C16.8078 21.2507 18.3458 20.6136 19.4797 19.4795C20.6137 18.3454 21.2507 16.8074 21.2506 15.2036C21.2505 13.5999 20.6133 12.0619 19.4792 10.928L17.1402 8.59C17.0522 8.80867 16.9456 9.05 16.8202 9.314C16.2602 10.476 15.2942 12.032 13.6622 13.663C12.0322 15.294 10.4762 16.26 9.31424 16.82C9.05024 16.946 8.80891 17.0527 8.59024 17.14Z" fill="#09706B"/>
                    </svg>
                    </div>
                    <p><span><?php echo esc_html($cantitatea); ?></span> <?php echo esc_html($tipul_dozei); ?> pe zi</p>
                </div>
                <?php endif; ?>
                <?php endif; ?>
                <?php endwhile; ?>
            </div>
            <?php endif; ?>
                </div>
                <?php $calitate = get_field('calitatea_produsului'); ?>
                <?php if ($calitate && is_array($calitate)) : ?>
                <div class="quality_container">
                    <?php if (in_array('potrivit_pentru_vegani', $calitate)) : ?>
                    <div class="quality_item">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/single/1.svg" alt="Potrivit pentru Vegani">
                    </div>
                    <?php endif; ?>
                    <?php if (in_array('fara_alergeni', $calitate)) : ?>
                    <div class="quality_item">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/single/2.svg" alt="Fără alergeni">
                    </div>
                    <?php endif; ?>
                    <?php if (in_array('fara_zahar_adaugat', $calitate)) : ?>
                    <div class="quality_item">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/single/3.svg" alt="Fără zahăr adăugat ">
                    </div>
                    <?php endif; ?>
                    <?php if (in_array('fara_ogm', $calitate)) : ?>
                    <div class="quality_item">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/single/4.svg" alt="Fără organisme modificate genetic">
                    </div>
                    <?php endif; ?>
                    <?php if (in_array('standard_haccp', $calitate)) : ?>
                    <div class="quality_item">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/single/5.svg" alt="Conform standardului HACCP">
                    </div>
                    <?php endif; ?>
                    <?php if (in_array('certificat_gmp', $calitate)) : ?>
                    <div class="quality_item">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/single/6.svg" alt="Certificat GMP">
                    </div>
                    <?php endif; ?>
                    <?php if (in_array('certificat_iso_22000', $calitate)) : ?>
                    <div class="quality_item">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/single/7.svg" alt="Certificat ISO 22000">
                    </div>
                    <?php endif; ?>
                    <?php if (in_array('calitate_europeana', $calitate)) : ?>
                    <div class="quality_item">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/single/8.svg" alt="Calitate Europeană">
                    </div>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </div>

        <?php else : ?>

            <div class="product-main-swiper-container">
                <div class="swiper product-main-swiper">
                    <div class="swiper-wrapper">
                        <div class="swiper-slide">
                            <img src="<?php echo esc_url( wc_placeholder_img_src() ); ?>" alt="Placeholder" />
                        </div>
                    </div>
                </div>
            </div>

        <?php endif; ?>

    </div>


</div>
