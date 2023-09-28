<?php
/**
 * Add to Wishlist settings
 *
 * @author  Your Inspiration Themes
 * @package AJ WooCommerce Wishlist
 * @version 0.1.26
 */

if ( ! defined( 'AJ_WCWL' ) ) {
	exit;
} // Exit if accessed directly

return apply_filters( 'aj_wcwl_add_to_wishlist_options', array(
	'add_to_wishlist' => array(

		'general_section_start' => array(
			'name' => __( 'General Settings', 'aj-woocommerce-wishlist' ),
			'type' => 'title',
			'desc' => '',
			'id' => 'aj_wcwl_general_settings'
		),

		'after_add_to_wishlist_behaviour' => array(
			'name'      => __( 'After product is added to wishlist', 'aj-woocommerce-wishlist' ),
			'desc'      => __( 'Choose the look of the Wishlist button when the product has already been added to a wishlist', 'aj-woocommerce-wishlist' ),
			'id'        => 'aj_wcwl_after_add_to_wishlist_behaviour',
			'options'   => array_merge(
				array(
					'add'    => __( 'Show "Add to wishlist" button', 'aj-woocommerce-wishlist' ),
					'view'   => __( 'Show "View wishlist" link', 'aj-woocommerce-wishlist' ),
					'remove' => __( 'Show "Remove from list" link', 'aj-woocommerce-wishlist' ),
				)
			) ,
			'default'   => 'view',
			'type'      => 'aj-field',
			'aj-type' => 'radio'
		),

		'general_section_end' => array(
			'type' => 'sectionend',
			'id' => 'aj_wcwl_general_settings'
		),

		'shop_page_section_start' => array(
			'name' => __( 'Loop settings', 'aj-woocommerce-wishlist' ),
			'type' => 'title',
			'desc' => __( 'Loop options will be visible on Shop page, category pages, product shortcodes, products sliders, and all the other places where the WooCommerce products\' loop is used', 'aj-woocommerce-wishlist' ),
			'id' => 'aj_wcwl_shop_page_settings'
		),

		'show_on_loop' => array(
			'name'      => __( 'Show "Add to wishlist" in loop', 'aj-woocommerce-wishlist' ),
			'desc'      => __( 'Enable the "Add to wishlist" feature in WooCommerce products\' loop', 'aj-woocommerce-wishlist' ),
			'id'        => 'aj_wcwl_show_on_loop',
			'default'   => 'no',
			'type'      => 'aj-field',
			'aj-type' => 'onoff'
		),

		'loop_position' => array(
			'name'      => __( 'Position of "Add to wishlist" in loop', 'aj-woocommerce-wishlist' ),
			'desc'      => __( 'Choose where to show "Add to wishlist" button or link in WooCommerce products\' loop. <span class="addon">Copy this shortcode <span class="code"><code>[aj_wcwl_add_to_wishlist]</code></span> and paste it where you want to show the "Add to wishlist" link or button</span>', 'aj-woocommerce-wishlist' ),
			'id'        => 'aj_wcwl_loop_position',
			'default'   => 'after_add_to_cart',
			'type'      => 'aj-field',
			'aj-type' => 'select',
			'class'     => 'wc-enhanced-select',
			'options'   => array(
				'before_image' => __( 'On top of the image', 'aj-woocommerce-wishlist' ),
				'before_add_to_cart' => __( 'Before "Add to cart" button', 'aj-woocommerce-wishlist' ),
				'after_add_to_cart' => __( 'After "Add to cart" button', 'aj-woocommerce-wishlist' ),
				'shortcode' => __( 'Use shortcode', 'aj-woocommerce-wishlist' )
			),
			'deps'      => array(
				'id'    => 'aj_wcwl_show_on_loop',
				'value' => 'yes'
			)
		),

		'shop_page_section_end' => array(
			'type' => 'sectionend',
			'id' => 'aj_wcwl_shop_page_settings'
		),

		'product_page_section_start' => array(
			'name' => __( 'Product page settings', 'aj-woocommerce-wishlist' ),
			'type' => 'title',
			'desc' => '',
			'id' => 'aj_wcwl_product_page_settings'
		),

		'add_to_wishlist_position' => array(
			'name'      => __( 'Position of "Add to wishlist" on product page', 'aj-woocommerce-wishlist' ),
			'desc'      => __( 'Choose where to show "Add to wishlist" button or link on the product page. <span class="addon">Copy this shortcode <span class="code"><code>[aj_wcwl_add_to_wishlist]</code></span> and paste it where you want to show the "Add to wishlist" link or button</span>', 'aj-woocommerce-wishlist' ),
			'id'        => 'aj_wcwl_button_position',
			'default'   => 'after_add_to_cart',
			'type'      => 'aj-field',
			'aj-type' => 'select',
			'class'     => 'wc-enhanced-select',
			'options'   => array(
				'add-to-cart' => __( 'After "Add to cart"', 'aj-woocommerce-wishlist' ),
				'thumbnails'  => __( 'After thumbnails', 'aj-woocommerce-wishlist' ),
				'summary'     => __( 'After summary', 'aj-woocommerce-wishlist' ),
				'shortcode'   => __( 'Use shortcode', 'aj-woocommerce-wishlist' )
			),
		),

		'product_page_section_end' => array(
			'type' => 'sectionend',
			'id' => 'aj_wcwl_product_page_settings'
		),

		'text_section_start' => array(
			'name' => __( 'Text customization', 'aj-woocommerce-wishlist' ),
			'type' => 'title',
			'desc' => '',
			'id' => 'aj_wcwl_text_section_settings'
		),

		'add_to_wishlist_text' => array(
			'name'    => __( '"Add to wishlist" text', 'aj-woocommerce-wishlist' ),
			'desc'    => __( 'Enter a text for "Add to wishlist" button', 'aj-woocommerce-wishlist' ),
			'id'      => 'aj_wcwl_add_to_wishlist_text',
			'default' => __( 'Add to wishlist', 'aj-woocommerce-wishlist' ),
			'type'    => 'text',
		),

		'product_added_text' => array(
			'name'    => __( '"Product added" text', 'aj-woocommerce-wishlist' ),
			'desc'    => __( 'Enter the text of the message displayed when the user adds a product to the wishlist', 'aj-woocommerce-wishlist' ),
			'id'      => 'aj_wcwl_product_added_text',
			'default' => __( 'Product added!', 'aj-woocommerce-wishlist' ),
			'type'    => 'text',
		),

		'browse_wishlist_text' => array(
			'name'    => __( '"Browse wishlist" text', 'aj-woocommerce-wishlist' ),
			'desc'    => __( 'Enter a text for the "Browse wishlist" link on the product page', 'aj-woocommerce-wishlist' ),
			'id'      => 'aj_wcwl_browse_wishlist_text',
			'default' => __( 'Browse wishlist', 'aj-woocommerce-wishlist' ),
			'type'    => 'text',
		),

		'already_in_wishlist_text' => array(
			'name'    => __( '"Product already in wishlist" text', 'aj-woocommerce-wishlist' ),
			'desc'    => __( 'Enter the text for the message displayed when the user views a product that is already in the wishlist', 'aj-woocommerce-wishlist' ),
			'id'      => 'aj_wcwl_already_in_wishlist_text',
			'default' => __( 'The product is already in your wishlist!', 'aj-woocommerce-wishlist' ),
			'type'    => 'text',
		),

		'text_section_end' => array(
			'type' => 'sectionend',
			'id' => 'aj_wcwl_text_section_settings'
		),

		'style_section_start' => array(
			'name' => __( 'Style & Color customization', 'aj-woocommerce-wishlist' ),
			'type' => 'title',
			'desc' => '',
			'id' => 'aj_wcwl_style_section_settings'
		),

		'use_buttons' => array(
			'name'      => __( 'Style of "Add to wishlist"', 'aj-woocommerce-wishlist' ),
			'desc'      => __( 'Choose if you want to show a textual "Add to wishlist" link or a button', 'aj-woocommerce-wishlist' ),
			'id'        => 'aj_wcwl_add_to_wishlist_style',
			'options'   => array(
				'link'           => __( 'Textual (anchor)', 'aj-woocommerce-wishlist' ),
				'button_default' => __( 'Button with theme style', 'aj-woocommerce-wishlist' ),
				'button_custom'  => __( 'Button with custom style', 'aj-woocommerce-wishlist' )
			),
			'default'   => 'link',
			'type'      => 'aj-field',
			'aj-type' => 'radio'
		),

		'add_to_wishlist_colors' => array(
			'name'         => __( '"Add to wishlist" button style', 'aj-woocommerce-wishlist' ),
			'id'           => 'aj_wcwl_color_add_to_wishlist',
			'type'         => 'aj-field',
			'aj-type'    => 'multi-colorpicker',
			'colorpickers' => array(
				array(
					'desc' => __( 'Choose colors for the "Add to wishlist" button', 'aj-woocommerce-wishlist' ),
					array(
						'name' => __( 'Background', 'aj-woocommerce-wishlist' ),
						'id'   => 'background',
						'default' => '#333333'
					),
					array(
						'name' => __( 'Text', 'aj-woocommerce-wishlist' ),
						'id'   => 'text',
						'default' => '#FFFFFF'
					),
					array(
						'name' => __( 'Border', 'aj-woocommerce-wishlist' ),
						'id'   => 'border',
						'default' => '#333333'
					),
				),
				array(
					'desc' => __( 'Choose colors for the "Add to wishlist" button on hover state', 'aj-woocommerce-wishlist' ),
					array(
						'name' => __( 'Background Hover', 'aj-woocommerce-wishlist' ),
						'id'   => 'background_hover',
						'default' => '#333333'
					),
					array(
						'name' => __( 'Text Hover', 'aj-woocommerce-wishlist' ),
						'id'   => 'text_hover',
						'default' => '#FFFFFF'
					),
					array(
						'name' => __( 'Border Hover', 'aj-woocommerce-wishlist' ),
						'id'   => 'border_hover',
						'default' => '#333333'
					),
				)
			),
			'deps' => array(
				'id'    => 'aj_wcwl_add_to_wishlist_style',
				'value' => 'button_custom'
			)
		),

		'rounded_buttons_radius' => array(
			'name'      => __( 'Border radius', 'aj-woocommerce-wishlist' ),
			'desc'      => __( 'Choose radius for the "Add to wishlist" button', 'aj-woocommerce-wishlist' ),
			'id'        => 'aj_wcwl_rounded_corners_radius',
			'default'   => 16,
			'type'      => 'aj-field',
			'aj-type' => 'slider',
			'min'       => 1,
			'max'       => 100,
			'deps' => array(
				'id'    => 'aj_wcwl_add_to_wishlist_style',
				'value' => 'button_custom'
			)
		),

		'add_to_wishlist_icon' => array(
			'name'      => __( '"Add to wishlist" icon', 'aj-woocommerce-wishlist' ),
			'desc'      => __( 'Select an icon for the "Add to wishlist" button (optional)', 'aj-woocommerce-wishlist' ),
			'id'        => 'aj_wcwl_add_to_wishlist_icon',
			'default'   => apply_filters( 'aj_wcwl_add_to_wishlist_std_icon', 'fa-heart-o', 'aj_wcwl_add_to_wishlist_icon' ),
			'type'      => 'aj-field',
			'class'     => 'icon-select',
			'aj-type' => 'select',
			'options'   => aj_wcwl_get_plugin_icons()
		),

		'add_to_wishlist_custom_icon' => array(
			'name'      => __( '"Add to wishlist" custom icon', 'aj-woocommerce-wishlist' ),
			'desc'      => __( 'Upload an icon you\'d like to use for "Add to wishlist" button (suggested 32px x 32px)', 'aj-woocommerce-wishlist' ),
			'id'        => 'aj_wcwl_add_to_wishlist_custom_icon',
			'default'   => '',
			'type'      => 'aj-field',
			'aj-type' => 'upload',
			'deps'      => array(
				'id'    => 'aj_wcwl_add_to_wishlist_icon',
				'value' => 'custom'
			)
		),

		'added_to_wishlist_icon' => array(
			'name'      => __( '"Added to wishlist" icon', 'aj-woocommerce-wishlist' ),
			'desc'      => __( 'Select an icon for the "Added to wishlist" button (optional)', 'aj-woocommerce-wishlist' ),
			'id'        => 'aj_wcwl_added_to_wishlist_icon',
			'default'   => apply_filters( 'aj_wcwl_add_to_wishlist_std_icon', 'fa-heart', 'aj_wcwl_added_to_wishlist_icon' ),
			'type'      => 'aj-field',
			'class'     => 'icon-select',
			'aj-type' => 'select',
			'options'   => aj_wcwl_get_plugin_icons( __( 'Same used for Add to wishlist', 'aj-woocommerce-wishlist' ) )
		),

		'added_to_wishlist_custom_icon' => array(
			'name'      => __( '"Added to wishlist" custom icon', 'aj-woocommerce-wishlist' ),
			'desc'      => __( 'Upload an icon you\'d like to use for "Add to wishlist" button (suggested 32px x 32px)', 'aj-woocommerce-wishlist' ),
			'id'        => 'aj_wcwl_added_to_wishlist_custom_icon',
			'default'   => '',
			'type'      => 'aj-field',
			'aj-type' => 'upload',
			'deps'      => array(
				'id'    => 'aj_wcwl_added_to_wishlist_icon',
				'value' => 'custom'
			)
		),

		'custom_css' => array(
			'name'     => __( 'Custom CSS', 'aj-woocommerce-wishlist' ),
			'desc'     => __( 'Enter custom CSS to be applied to Wishlist elements (optional)', 'aj-woocommerce-wishlist' ),
			'id'       => 'aj_wcwl_custom_css',
			'default'  => '',
			'type'     => 'aj-field',
			'aj-type' => 'textarea',
		),

		'style_section_end' => array(
			'type' => 'sectionend',
			'id' => 'aj_wcwl_style_section_settings'
		),

	),
) );
