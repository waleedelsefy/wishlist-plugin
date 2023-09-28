<?php
/**
 * Lists options page
 *
 * @author  Your Inspiration Themes
 * @package AJ WooCommerce Wishlist
 * @version 0.1.26
 */

if ( ! defined( 'AJ_WCWL' ) ) {
	exit;
} // Exit if accessed directly

$action = isset( $_GET['action'] ) ? $_GET['action'] : false;
$options = array(
	'popular_section_start' => array(
		'type' => 'title',
		'desc' => '',
		'id'   => 'aj_wcwl_popular_settings'
	),

	'popular_section_end' => array(
		'type' => 'sectionend',
		'id'   => 'aj_wcwl_popular_settings'
	),
);

if( ! $action || 'show_users' != $action ){
	$options = aj_wcwl_merge_in_array( $options, array(
		'wishlists' => array(
			'name'      => __( 'Popular products', 'aj-woocommerce-wishlist' ),
			'type'      => 'aj-field',
			'aj-type' => 'list-table',

			'class'                => '',
			'list_table_class'     => 'AJ_WCWL_Popular_Table',
			'list_table_class_dir' => AJ_WCWL_INC . 'tables/class.aj-wcwl-popular-table.php',
			'id'                   => 'popular-filter'
		),
	), 'popular_section_start' );
}
else{
	$product_id = isset( $_GET['product_id'] ) ? intval( $_GET['product_id'] ) : false;
	$product = wc_get_product( $product_id );

	$options = aj_wcwl_merge_in_array( $options, array(
		'wishlists' => array(
			'name'      => $product ? sprintf( __( 'Users that added "%s" to wishlist', 'aj-woocommerce-wishlist' ), $product->get_name() ) : __( 'Users that added product to wishlist', 'aj-woocommerce-wishlist' ),
			'desc'      => sprintf( '<small><a href="%s">%s</a></small>', remove_query_arg( array( 'action', 'product_id' ) ), __( '< Back to popular', 'aj-woocommerce-wishlist' ) ),
			'type'      => 'aj-field',
			'aj-type' => 'list-table',

			'class'                => '',
			'list_table_class'     => 'AJ_WCWL_Users_Popular_Table',
			'list_table_class_dir' => AJ_WCWL_INC . 'tables/class.aj-wcwl-users-popular-table.php',
			'id'                   => 'popular-filter'
		),
	), 'popular_section_start' );
}

return apply_filters( 'aj_wcwl_popular_options', array(
	'popular' => $options
) );