<?php
/**
 * Wishlist create template
 *
 * @author  Your Inspiration Themes
 * @package AJ WooCommerce Wishlist
 * @version 0.1.26
 */

if ( ! defined( 'AJ_WCWL' ) ) {
	exit;
} // Exit if accessed directly
?>

<div class="aj-wcwl-wishlist-new">

	<p class="form-row form-row-wide">
		<input name="wishlist_name" type="text" class="wishlist-name input-text" placeholder="<?php echo esc_html( apply_filters( 'aj_wcwl_new_list_title_text', __( 'Name your list', 'aj-woocommerce-wishlist' ) ) ); ?>" required="required"/>
	</p>

	<p class="form-row form-row-wide wishlist-privacy-radio">
		<label>
			<input type="radio" checked="checked" name="wishlist_visibility" class="wishlist-visiblity" value="0"/>
			<?php echo aj_wcwl_get_privacy_label( 0, true ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</label>
		<label>
			<input type="radio" name="wishlist_visibility" class="wishlist-visiblity" value="1"/>
			<?php echo aj_wcwl_get_privacy_label( 1, true ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</label>
		<label>
			<input type="radio" name="wishlist_visibility" class="wishlist-visiblity" value="2"/>
			<?php echo aj_wcwl_get_privacy_label( 2, true ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</label>
	</p>
    <input class="create-wishlist-button" type="submit" name="create_wishlist" value="<?php echo esc_attr( apply_filters( 'aj_wcwl_create_wishlist_button_label', __( 'Create wishlist', 'aj-woocommerce-wishlist' ) ) ); ?>"/>

	<?php wp_nonce_field( 'aj_wcwl_create_action', 'aj_wcwl_create' ); ?>

</div>
