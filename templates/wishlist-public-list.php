<?php
/**
 * List of public wishlists
 *
 * @author Your Inspiration Themes
 * @package AJ WooCommerce Wishlist
 * @version 2.2.9
 */

/**
 * Template variables:
 *
 * @var $wishlists \AJ_WCWL_Wishlist[] User wishlists
 */

if ( ! defined( 'AJ_WCWL' ) ) {
	exit;
} // Exit if accessed directly
?>
<div class="aj-wcwl-public-lists">
	<?php
	if ( ! empty( $wishlists ) ) :
		foreach ( $wishlists as $wishlist ) :
			?>
			<p>
				<a href="<?php echo esc_url( $wishlist->get_url() ); ?>">
					<?php echo esc_html( $wishlist->get_formatted_name() ); ?>
				</a>
			</p>
			<?php
		endforeach;
	endif;
	?>
</div>
