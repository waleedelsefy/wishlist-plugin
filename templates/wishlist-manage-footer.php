<?php
/**
 * Wishlist manage footer template
 *
 * @author Your Inspiration Themes
 * @package AJ WooCommerce Wishlist
 * @version 0.1.26
 */

/**
 * Template variables:
 *
 * @var $page_title            string Page title
 * @var $template_part         string Template part currently being loaded (manage)
 * @var $user_wishlists        AJ_WCWL_Wishlist[] Array of user wishlists
 * @var $show_number_of_items  bool Whether to show number of items or not
 * @var $show_date_of_creation bool Whether to show date of creation or not
 * @var $show_download_as_pdf  bool Whether to show download button or not
 * @var $show_rename_wishlist  bool Whether to show rename button or not
 * @var $show_delete_wishlist  bool Whether to show delete button or not
 */

if ( ! defined( 'AJ_WCWL' ) ) {
	exit;
} // Exit if accessed directly
?>

	<?php wp_nonce_field( 'aj_wcwl_manage_action', 'aj_wcwl_manage' ); ?>
	<?php do_action( 'aj_wcwl_after_wishlist_manage' ); ?>

</form>