<?php
/**
 * Customer Reset Password email
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/customer-reset-password.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates\Emails
 * @version 10.4.0
 */

use Automattic\WooCommerce\Utilities\FeaturesUtil;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$email_improvements_enabled = FeaturesUtil::feature_is_enabled( 'email_improvements' );

?>

<?php do_action( 'woocommerce_email_header', $email_heading, $email ); ?>

<?php echo $email_improvements_enabled ? '<div class="email-introduction">' : ''; ?>

<p>Bună <?php echo esc_html( $user_login ); ?>,</p>

<p>A fost solicitată resetarea parolei pentru contul tău de pe Mâna Naturii.</p>

<?php if ( $email_improvements_enabled ) : ?>
	<div class="hr hr-top"></div>
	<p>Nume utilizator: <b><?php echo esc_html( $user_login ); ?></b></p>
	<div class="hr hr-bottom"></div>
<?php else : ?>
	<p>Nume utilizator: <?php echo esc_html( $user_login ); ?></p>
<?php endif; ?>

<p>Dacă tu ai făcut această solicitare, poți seta o parolă nouă folosind linkul de mai jos:</p>

<p>
	<a class="link" href="<?php echo esc_url( add_query_arg( array( 'key' => $reset_key, 'id' => $user_id, 'login' => rawurlencode( $user_login ) ), wc_get_endpoint_url( 'lost-password', '', wc_get_page_permalink( 'myaccount' ) ) ) ); ?>">
		Resetează-ți parola
	</a>
</p>

<p>Dacă nu ai solicitat resetarea parolei, poți ignora acest email. Contul tău va rămâne neschimbat.</p>

<p>Pentru siguranța contului tău, recomandăm să alegi o parolă puternică și să nu o folosești pe alte platforme.</p>

<p>Cu bine,<br>Echipa Mâna Naturii</p>

<?php echo $email_improvements_enabled ? '</div>' : ''; ?>

<?php
/**
 * Show user-defined additional content - this is set in each email's settings.
 */
if ( $additional_content ) {
	echo $email_improvements_enabled ? '<table border="0" cellpadding="0" cellspacing="0" width="100%" role="presentation"><tr><td class="email-additional-content email-additional-content-aligned">' : '';
	echo wp_kses_post( wpautop( wptexturize( $additional_content ) ) );
	echo $email_improvements_enabled ? '</td></tr></table>' : '';
}

do_action( 'woocommerce_email_footer', $email );
