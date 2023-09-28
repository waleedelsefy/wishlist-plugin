<?php
/**
 * Customer "on sale item" email
 *
 * @author  Your Inspiration Themes
 * @package AJ WooCommerce Wishlist
 * @version 0.1.26
 */

/**
 * Template variables:
 *
 * @var $email_heading string Email heading string
 * @var $email         \WC_Email Email object
 * @var $email_content string Email content (HTML)
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly ?>

<?php do_action( 'woocommerce_email_header', $email_heading, $email ); ?>

	<p>
		<?php echo  wp_kses_post( $email_content ); ?>
	</p>

<?php do_action( 'woocommerce_email_footer', $email ); ?>
