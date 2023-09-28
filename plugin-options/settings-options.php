<?php
/**
 * General settings page
 *
 * @author  Your Inspiration Themes
 * @package AJ WooCommerce Wishlist
 * @version 0.1.26
 */

if ( ! defined( 'AJ_WCWL' ) ) {
	exit;
} // Exit if accessed directly

$aj_wfbt_installed = ( defined( 'AJ_WFBT' ) && AJ_WFBT );
$aj_wfbt_landing = 'https://ajemes.com/themes/plugins/aj-woocommerce-frequently-bought-together/';
$aj_wfbt_thickbox = AJ_WCWL_URL . 'assets/images/landing/aj-wfbt-slider.jpg';
$aj_wfbt_promo = sprintf( __( 'If you want to take advantage of this feature, you could consider purchasing the %s.', 'aj-woocommerce-wishlist' ), '<a href="https://ajemes.com/themes/plugins/aj-woocommerce-frequently-bought-together/">AJ WooCommerce Frequently Bought Together Plugin</a>' );

return apply_filters( 'aj_wcwl_settings_options', array(
	'settings' => array(

		'general_section_start' => array(
			'name' => __( 'General Settings', 'aj-woocommerce-wishlist' ),
			'type' => 'title',
			'desc' => '',
			'id' => 'aj_wcwl_general_settings'
		),

		'enable_ajax_loading' => array(
			'name'      => __( 'Enable AJAX loading', 'aj-woocommerce-wishlist' ),
			'desc'      => __( 'Load any cacheable wishlist item via AJAX', 'aj-woocommerce-wishlist' ),
			'id'        => 'aj_wcwl_ajax_enable',
			'default'   => 'no',
			'type'      => 'aj-field',
			'aj-type' => 'onoff'
		),

		'general_section_end' => array(
			'type' => 'sectionend',
			'id' => 'aj_wcwl_general_settings'
		),


		'aj_wfbt_enable_integration' => array(
			'name'      => __( 'Enable slider in wishlist', 'aj-woocommerce-wishlist' ),
			'desc'      => sprintf( __( 'Enable the slider with linked products on the Wishlist page (<a href="%s" class="thickbox">Example</a>). %s', 'aj-woocommerce-wishlist' ), $aj_wfbt_thickbox,  ( ! ( defined( 'AJ_WFBT' ) && AJ_WFBT ) ) ? $aj_wfbt_promo : '' ),
			'id'        => 'aj_wfbt_enable_integration',
			'default'   => 'yes',
			'type'      => 'aj-field',
			'aj-type' => 'onoff'
		),

		'aj_wfbt_end' => array(
			'type' => 'sectionend',
			'id' => 'aj_wcwl_aj_wfbt'
		)
	)
) );