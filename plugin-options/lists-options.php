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

return apply_filters( 'aj_wcwl_list_options', array(
	'lists' => array(
		'lists_section_start' => array(
			'type' => 'title',
			'desc' => '',
			'id' => 'aj_wcwl_lists_settings'
		),

		'wishlists' => array(
			'name' => __( 'Wishlists', 'aj-woocommerce-wishlist' ),
			'type' => 'aj-field',
			'aj-type' => 'list-table',

			'class' => '',
			'list_table_class' => 'AJ_WCWL_Admin_Table',
			'list_table_class_dir' => AJ_WCWL_INC . 'tables/class.aj-wcwl-admin-table.php',
			'title' => __( 'Wishlists', 'aj-woocommerce-wishlist' ),
			'search_form' => array(
				'text' => __( 'Search list', 'aj-woocommerce-wishlist' ),
				'input_id' => 'search_list'
			),
			'id' => 'wishlist-filter'
		),

		'lists_section_end' => array(
			'type' => 'sectionend',
			'id' => 'aj_wcwl_lists_settings'
		),

	)
) );
