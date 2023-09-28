<?php
/**
 * Wishlist search header template
 *
 * @author Your Inspiration Themes
 * @package AJ WooCommerce Wishlist
 * @version 0.1.26
 */

/**
 * Template variables:
 *
 * @var $page_title string Page title
 * @var $pages_links string Pagination links
 * @var $search_string string Searched value
 * @var $search_results array Search results
 * @var $template_part string Template part currently being loaded (search)
 * @var $default_wishlist_title string Default wishlist title
 */

if ( ! defined( 'AJ_WCWL' ) ) {
	exit;
} // Exit if accessed directly
?>

<form id="aj-wcwl-form" action="<?php echo esc_url( AJ_WCWL()->get_wishlist_url( 'search' ) ) ?>" method="post">
	<!-- TITLE -->
	<?php
	do_action( 'aj_wcwl_before_wishlist_title' );

	if( ! empty( $page_title ) ) {
		echo apply_filters( 'aj_wcwl_wishlist_title', '<h2>' . esc_html( $page_title ) . '</h2>' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	do_action( 'aj_wcwl_before_wishlist_search' );
	?>