<?php
/**
 * Wishlist create header template
 *
 * @author Your Inspiration Themes
 * @package AJ WooCommerce Wishlist
 * @version 0.1.26
 */

if ( ! defined( 'AJ_WCWL' ) ) {
	exit;
} // Exit if accessed directly

/**
 * Template variables:
 *
 * @var $page_title string Page title
 */
?>

<form id="aj-wcwl-form" action="<?php echo esc_url( AJ_WCWL()->get_wishlist_url( 'create' ) ); ?>" method="post">
	<!-- TITLE -->
	<?php
	do_action( 'aj_wcwl_before_wishlist_title' );

	if( ! empty( $page_title ) ) {
		echo apply_filters( 'aj_wcwl_wishlist_title', '<h2>' . esc_html( $page_title ) . '</h2>' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	do_action( 'aj_wcwl_before_wishlist_create' );
	?>