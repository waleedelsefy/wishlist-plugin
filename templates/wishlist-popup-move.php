<?php
/**
 * Wishlist move popup
 *
 * @author  Your Inspiration Themes
 * @package AJ WooCommerce Wishlist
 * @version 0.1.26
 */

/**
 * Template variables:
 *
 * @var $wishlist					  \AJ_WCWL_Wishlist Wishlist
 * @var $move_to_another_wishlist_url string Url to use as action for wishlist form
 * @var $users_wishlists              \AJ_WCWL_Wishlist[] User wishlists
 * @var $wishlist_token               string Wishlist token
 * @var $heading_icon                 string Heading icon HTML tag
 */

if ( ! defined( 'AJ_WCWL' ) ) {
	exit;
} // Exit if accessed directly
?>

<div id="move_to_another_wishlist">
	<form action="<?php echo esc_attr( $move_to_another_wishlist_url ); ?>" method="post" class="wishlist-move-to-another-wishlist-popup">
		<div class="aj-wcwl-popup-content">
			<?php if ( apply_filters( 'aj_wcwl_show_popup_heading_icon_instead_of_title', ! empty( $heading_icon ), $heading_icon ) ): ?>
				<p class="heading-icon">
					<?php echo apply_filters( 'aj_wcwl_popup_heading_icon_class', $heading_icon ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</p>
				<p class="popup-description">
					<?php esc_html_e( 'Move to another wishlist', 'aj-woocommerce-wishlist' ); ?>
				</p>
			<?php else: ?>
				<h3><?php esc_html_e( 'Move to another wishlist', 'aj-woocommerce-wishlist' ); ?></h3>
			<?php endif; ?>

			<p class="form-row">
				<?php printf( __( 'This item is already in the <b>%s wishlist</b>.<br/>You can move it in another list:', 'aj-woocommerce-wishlist' ), $wishlist->get_formatted_name() ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</p>

			<p class="form-row form-row-wide">
				<select name="new_wishlist_id" class="change-wishlist">
					<?php
					foreach ( $users_wishlists as $wl ):
						if ( $wl->get_token() === $wishlist_token ) {
							continue;
						}
						?>
						<option value="<?php echo esc_attr( $wl->get_id() ); ?>">
							<?php echo esc_html( sprintf( '%s - %s', $wl->get_formatted_name(), $wl->get_formatted_privacy() ) ); ?>
						</option>
					<?php
					endforeach;
					?>
				</select>
			</p>
		</div>
		<div class="aj-wcwl-popup-footer">
			<button class="move-to-another-wishlist-button move-to-another-wishlist-button-popup wishlist-submit button alt">
				<?php echo esc_html( apply_filters( 'aj_wcwl_move_to_another_wishlist_text', __( 'Move', 'aj-woocommerce-wishlist' ) ) ); ?>
			</button>
		</div>
	</form>
</div>
