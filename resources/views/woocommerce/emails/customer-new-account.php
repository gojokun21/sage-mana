<?php
/**
 * Customer new account email
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/customer-new-account.php.
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

defined( 'ABSPATH' ) || exit;

$email_improvements_enabled = FeaturesUtil::feature_is_enabled( 'email_improvements' );

/**
 * Fires to output the email header.
 *
 * @hooked WC_Emails::email_header()
 *
 * @since 3.7.0
 */
do_action( 'woocommerce_email_header', $email_heading, $email ); ?>

<p>Bună <?php echo esc_html( $user_login ); ?>,</p>

<p>Ne bucurăm să fim alături de tine în grija pentru sănătatea ta. 💚</p>

<p>Contul tău pe Mâna Naturii a fost creat cu succes.</p>

<p><strong>Datele contului tău:</strong></p>
<p>Nume utilizator: <strong><?php echo esc_html( $user_login ); ?></strong></p>

<?php if ( $password_generated && $set_password_url ) : ?>
<p>👉 Pentru siguranța contului tău, te rugăm să îți setezi parola folosind linkul de mai jos:</p>
<p><a href="<?php echo esc_attr( $set_password_url ); ?>" style="display: inline-block; padding: 10px 20px; background-color: #4CAF50; color: #ffffff; text-decoration: none; border-radius: 5px;">Setează parola</a></p>
<?php endif; ?>

<p><strong>Din contul tău vei putea:</strong></p>
<ul>
	<li>să urmărești comenzile plasate</li>
	<li>să gestionezi datele personale</li>
	<li>să accesezi ofertele și beneficiile disponibile</li>
</ul>

<p>👉 Accesează contul tău aici:</p>
<p><a href="<?php echo esc_attr( wc_get_page_permalink( 'myaccount' ) ); ?>" style="display: inline-block; padding: 10px 20px; background-color: #4CAF50; color: #ffffff; text-decoration: none; border-radius: 5px;">Contul meu</a></p>

<p>Dacă ai întrebări sau ai nevoie de ajutor, suntem aici pentru tine.</p>

<p>Cu drag,<br>Echipa Mâna Naturii</p>

<?php
/**
 * Show user-defined additional content - this is set in each email's settings.
 */
if ( $additional_content ) {
	echo wp_kses_post( wpautop( wptexturize( $additional_content ) ) );
}

/**
 * Fires to output the email footer.
 *
 * @hooked WC_Emails::email_footer()
 *
 * @since 3.7.0
 */
do_action( 'woocommerce_email_footer', $email );
