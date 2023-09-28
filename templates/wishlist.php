<?php
/**
 * Wishlist pages template; load template parts basing on the url
 *
 * @author Your Inspiration Themes
 * @package AJ WooCommerce Wishlist
 * @version 0.1.26
 */

/**
 * Template Variables:
 *
 * @var $template_part string Sub-template to load
 * @var $var array Array of attributes that needs to be sent to sub-template
 */

if ( ! defined( 'AJ_WCWL' ) ) {
	exit;
} // Exit if accessed directly
?>


<?php
/**
 * Hook: aj_wcwl_wishlist_before_wishlist_content.
 *
 * @hooked \AJ_WCWL_Frontend::wishlist_header - 10
 */
do_action( 'aj_wcwl_wishlist_before_wishlist_content', $var );
?>

<?php
/**
 * Hook: aj_wcwl_wishlist_main_wishlist_content.
 *
 * @hooked \AJ_WCWL_Frontend::main_wishlist_content - 10
 */
do_action( 'aj_wcwl_wishlist_main_wishlist_content', $var );
?>

<?php
/**
 * Hook: aj_wcwl_wishlist_after_wishlist_content.
 *
 * @hooked \AJ_WCWL_Frontend::wishlist_footer - 10
 */
do_action( 'aj_wcwl_wishlist_after_wishlist_content', $var );
?>