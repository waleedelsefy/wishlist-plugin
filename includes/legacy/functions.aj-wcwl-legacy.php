<?php
/**
 * Legacy Functions & hooks
 *
 * @author Your Inspiration Themes
 * @package AJ WooCommerce Wishlist
 * @version 0.1.26
 */

if ( ! defined( 'AJ_WCWL' ) ) {
	exit;
} // Exit if accessed directly

if( ! function_exists( 'AJ_WCWL_Admin_Init' ) ){
	/**
	 * Deprecated function that used to return admin class single instance
	 *
	 * @return AJ_WCWL_Admin
	 * @since 2.0.0
	 */
	function AJ_WCWL_Admin_Init(){
		_deprecated_function( __FUNCTION__, '3.0.0', 'AJ_WCWL_Admin' );
		return AJ_WCWL_Admin();
	}
}

if( ! function_exists( 'AJ_WCWL_Init' ) ){
	/**
	 * Deprecated function that used to return init class single instance
	 *
	 * @return AJ_WCWL_Frontend
	 * @since 2.0.0
	 */
	function AJ_WCWL_Init(){
		_deprecated_function( __FUNCTION__, '3.0.0', 'AJ_WCWL_Frontend' );
		return AJ_WCWL_Frontend();
	}
}