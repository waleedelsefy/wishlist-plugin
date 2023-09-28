<?php
/**
 * Wishlist create popup
 *
 * @author  Your Inspiration Themes
 * @package AJ WooCommerce Wishlist
 * @version 0.1.26
 */

/**
 * Template variables:
 *
 * @var $heading_icon string Heading icon HTML tag
 */

if ( ! defined( 'AJ_WCWL' ) ) {
	exit;
} // Exit if accessed directly
?>

<div id="create_new_wishlist">
	<form method="post" action="<?php echo esc_url( AJ_WCWL()->get_wishlist_url( 'create' ) ); ?>">
		<?php do_action( 'aj_wcwl_before_wishlist_create' ); ?>

		<div class="aj-wcwl-popup-content">
			<?php if ( apply_filters( 'aj_wcwl_show_popup_heading_icon_instead_of_title', ! empty( $heading_icon ), $heading_icon ) ) : ?>
				<p class="heading-icon">
					<?php echo apply_filters( 'aj_wcwl_popup_heading_icon_class', $heading_icon ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</p>
			<?php else : ?>
				<h3><?php esc_html_e( 'Create a new wishlist', 'aj-woocommerce-wishlist' ); ?></h3>
			<?php endif; ?>

			<p class="popup-description">
				<?php esc_html_e( 'Create a wishlist', 'aj-woocommerce-wishlist' ); ?>
			</p>

			<?php aj_wcwl_get_template_part( 'create' ); ?>
		</div>

		<?php do_action( 'aj_wcwl_after_wishlist_create' ); ?>
	</form>

</div>