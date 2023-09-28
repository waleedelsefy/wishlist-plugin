<?php
/**
 * Promotional email settings
 *
 * @author  Your Inspiration Themes
 * @package AJ WooCommerce Wishlist
 * @version 0.1.26
 */

if ( ! defined( 'AJ_WCWL' ) ) {
	exit;
} // Exit if accessed directly

$product_categories = get_terms(
	array(
		'taxonomy' => 'product_cat',
		'hide_empty' => true,
		'number' => 0,
		'fields' => 'id=>name',
	)
);

$back_in_stock_saved_options = get_option( 'woocommerce_aj_wcwl_back_in_stock_settings', array() );
$back_in_stock_exclusions_options = array();

if ( ! empty( $back_in_stock_saved_options['product_exclusions'] ) ) {
	foreach ( $back_in_stock_saved_options['product_exclusions'] as $product_id ) {
		$product = wc_get_product( $product_id );

		if ( ! $product ) {
			continue;
		}

		$back_in_stock_exclusions_options[ $product_id ] = $product->get_formatted_name();
	}
}

$on_sale_item_saved_options = get_option( 'woocommerce_aj_wcwl_on_sale_item_settings', array() );
$on_sale_item_exclusions_options = array();

if ( ! empty( $on_sale_item_saved_options['product_exclusions'] ) ) {
	foreach ( $on_sale_item_saved_options['product_exclusions'] as $product_id ) {
		$product = wc_get_product( $product_id );

		if ( ! $product ) {
			continue;
		}

		$on_sale_item_exclusions_options[ $product_id ] = $product->get_formatted_name();
	}
}

WC()->mailer();

return apply_filters(
	'aj_wcwl_promotion_email_options',
	array(
		'promotion_email' => array(

			'promotion_email_start' => array(
				'name' => __( '"Promotional" email', 'aj-woocommerce-wishlist' ),
				'type' => 'title',
				'desc' => '',
				'id' => 'aj_wcwl_promotional_email',
			),

			'promotion_email_mail_type' => array(
				'name'      => __( 'Email type', 'aj-woocommerce-wishlist' ),
				'desc'      => __( 'Choose which type of email to send', 'aj-woocommerce-wishlist' ),
				'id'        => 'woocommerce_aj_wcwl_promotion_mail_settings[email_type]',
				'default'   => 'html',
				'type'      => 'aj-field',
				'aj-type' => 'select',
				'class'     => 'wc-enhanced-select',
				'options'   => array(
					'plain'     => __( 'Plain', 'aj-woocommerce-wishlist' ),
					'html'      => __( 'HTML', 'aj-woocommerce-wishlist' ),
					'multipart' => __( 'Multipart', 'aj-woocommerce-wishlist' ),
				),
			),

			'promotion_email_mail_heading' => array(
				'name'      => __( 'Email heading', 'aj-woocommerce-wishlist' ),
				'desc'      => __( 'Enter the title for the email notification. Leave blank to use the default heading: "<i>There is a deal for you!</i>"', 'aj-woocommerce-wishlist' ),
				'id'        => 'woocommerce_aj_wcwl_promotion_mail_settings[heading]',
				'default'   => '',
				'type'      => 'text',
			),

			'promotion_email_mail_subject' => array(
				'name'      => __( 'Email subject', 'aj-woocommerce-wishlist' ),
				'desc'      => __( 'Enter the mail subject line. Leave blank to use the default subject: "<i>A product of your wishlist is on sale</i>"', 'aj-woocommerce-wishlist' ),
				'id'        => 'woocommerce_aj_wcwl_promotion_mail_settings[subject]',
				'default'   => '',
				'type'      => 'text',
			),

			'promotion_email_html_content' => array(
				'name'      => __( 'Email HTML content', 'aj-woocommerce-wishlist' ),
				'desc'      => __( 'This field lets you modify the main content of the HTML email. You can use the following placeholders: <code>{user_name}</code> <code>{user_email}</code> <code>{user_first_name}</code> <code>{user_last_name}</code> <code>{product_image}</code> <code>{product_name}</code> <code>{product_price}</code> <code>{coupon_code}</code> <code>{coupon_amount}</code> <code>{product_url}</code> <code>{add_to_cart_url}</code>', 'aj-woocommerce-wishlist' ),
				'id'        => 'woocommerce_aj_wcwl_promotion_mail_settings[content_html]',
				'default'   => class_exists('AJ_WCWL_Promotion_Email') ? AJ_WCWL_Promotion_Email::get_default_content( 'html' ) : '',
				'type'      => 'aj-field',
				'aj-type' => 'textarea',
				'deps'      => array(
					'id' => 'woocommerce_aj_wcwl_promotion_mail_settings[email_type]',
					'value' => 'html,multipart',
				),
			),

			'promotion_email_text_content' => array(
				'name'      => __( 'Email plain content', 'aj-woocommerce-wishlist' ),
				'desc'      => __( 'This field lets you modify the main content of the text email. You can use the following placeholders: <code>{user_name}</code> <code>{user_email}</code> <code>{user_first_name}</code> <code>{user_last_name}</code> <code>{product_name}</code> <code>{product_price}</code> <code>{coupon_code}</code> <code>{coupon_amount}</code> <code>{coupon_value}</code> <code>{product_url}</code> <code>{add_to_cart_url}</code>', 'aj-woocommerce-wishlist' ),
				'id'        => 'woocommerce_aj_wcwl_promotion_mail_settings[content_text]',
				'default'   => class_exists('AJ_WCWL_Promotion_Email') ? AJ_WCWL_Promotion_Email::get_default_content( 'plain' ) : '',
				'type'      => 'aj-field',
				'aj-type' => 'textarea',
			),

			'promotion_email_end' => array(
				'type' => 'sectionend',
				'id' => 'aj_wcwl_promotional_email',
			),

			'back_in_stock_email_start' => array(
				'name' => __( '"Back in stock" email', 'aj-woocommerce-wishlist' ),
				'type' => 'title',
				'desc' => '',
				'id' => 'aj_wcwl_back_in_stock_email',
			),

			'back_in_stock_email_enable' => array(
				'name'      => __( 'Enable "Back in stock" email', 'aj-woocommerce-wishlist' ),
				'desc'      => __( 'Enable this email to send notifications to your customers whenever a product in their wishlist is back in stock', 'aj-woocommerce-wishlist' ),
				'id'        => 'woocommerce_aj_wcwl_back_in_stock_settings[enabled]',
				'default'   => 'no',
				'type'      => 'aj-field',
				'aj-type' => 'onoff',
			),

			'back_in_stock_email_product_exclusions' => array(
				'name'      => __( 'Product exclusions', 'aj-woocommerce-wishlist' ),
				'desc'      => __( 'Select products that shouldn\'t trigger the "back in stock" notifications', 'aj-woocommerce-wishlist' ),
				'id'        => 'woocommerce_aj_wcwl_back_in_stock_settings[product_exclusions]',
				'type'      => 'aj-field',
				'aj-type' => 'select',
				'multiple'  => true,
				'class'     => 'wc-product-search',
				'options'   => $back_in_stock_exclusions_options,
			),

			'back_in_stock_email_category_exclusions' => array(
				'name'      => __( 'Category exclusions', 'aj-woocommerce-wishlist' ),
				'desc'      => __( 'Select the product categories that should not trigger the "back in stock" notification', 'aj-woocommerce-wishlist' ),
				'id'        => 'woocommerce_aj_wcwl_back_in_stock_settings[category_exclusions]',
				'type'      => 'aj-field',
				'aj-type' => 'select',
				'multiple'  => true,
				'class'     => 'wc-enhanced-select',
				'options'   => $product_categories,
			),

			'back_in_stock_email_mail_type' => array(
				'name'      => __( 'Email type', 'aj-woocommerce-wishlist' ),
				'desc'      => __( 'Choose which type of email to send', 'aj-woocommerce-wishlist' ),
				'id'        => 'woocommerce_aj_wcwl_back_in_stock_settings[email_type]',
				'default'   => 'html',
				'type'      => 'aj-field',
				'aj-type' => 'select',
				'class'     => 'wc-enhanced-select',
				'options'   => array(
					'plain'     => __( 'Plain', 'aj-woocommerce-wishlist' ),
					'html'      => __( 'HTML', 'aj-woocommerce-wishlist' ),
					'multipart' => __( 'Multipart', 'aj-woocommerce-wishlist' ),
				),
			),

			'back_in_stock_email_mail_heading' => array(
				'name'      => __( 'Email heading', 'aj-woocommerce-wishlist' ),
				'desc'      => __( 'Enter the title of the email notification. Leave blank to use the default heading: "<i>An item of your wishlist is back in stock!</i>"', 'aj-woocommerce-wishlist' ),
				'id'        => 'woocommerce_aj_wcwl_back_in_stock_settings[heading]',
				'default'   => '',
				'type'      => 'text',
			),

			'back_in_stock_email_mail_subject' => array(
				'name'      => __( 'Email subject', 'aj-woocommerce-wishlist' ),
				'desc'      => __( 'Enter the mail subject line. Leave blank to use the default subject: "<i>An item of your wishlist is back in stock!</i>"', 'aj-woocommerce-wishlist' ),
				'id'        => 'woocommerce_aj_wcwl_back_in_stock_settings[subject]',
				'default'   => '',
				'type'      => 'text',
			),

			'back_in_stock_email_html_content' => array(
				'name'      => __( 'Email HTML content', 'aj-woocommerce-wishlist' ),
				'desc'      => __( 'This field lets you modify the main content of the HTML email. You can use the following placeholders: <code>{user_name}</code> <code>{user_email}</code> <code>{user_first_name}</code> <code>{user_last_name}</code> <code>{products_table}</code> <code>{unsubscribe_link}</code>', 'aj-woocommerce-wishlist' ),
				'id'        => 'woocommerce_aj_wcwl_back_in_stock_settings[content_html]',
				'default'   => class_exists('AJ_WCWL_Back_In_Stock_Email') ? AJ_WCWL_Back_In_Stock_Email::get_default_content( 'html' ) : '',
				'type'      => 'aj-field',
				'aj-type' => 'textarea',
			),

			'back_in_stock_email_text_subject' => array(
				'name'      => __( 'Email plain content', 'aj-woocommerce-wishlist' ),
				'desc'      => __( 'This field lets you modify the main content of the text email. You can use the following placeholders: <code>{user_name}</code> <code>{user_email}</code> <code>{user_first_name}</code> <code>{user_last_name}</code> <code>{products_list}</code> <code>{unsubscribe_url}</code>', 'aj-woocommerce-wishlist' ),
				'id'        => 'woocommerce_aj_wcwl_back_in_stock_settings[content_text]',
				'default'   => class_exists('AJ_WCWL_Back_In_Stock_Email') ? AJ_WCWL_Back_In_Stock_Email::get_default_content( 'plain' ) : '',
				'type'      => 'aj-field',
				'aj-type' => 'textarea',
			),

			'back_in_stock_email_end' => array(
				'type' => 'sectionend',
				'id' => 'aj_wcwl_back_in_stock_email',
			),

			'on_sale_item_email_start' => array(
				'name' => __( '"On sale item" email', 'aj-woocommerce-wishlist' ),
				'type' => 'title',
				'desc' => '',
				'id' => 'aj_wcwl_on_sale_item_email',
			),

			'on_sale_item_email_enable' => array(
				'name'      => __( 'Enable "On sale item" email', 'aj-woocommerce-wishlist' ),
				'desc'      => __( 'Enable this email to send notifications to your customers whenever a product in their wishlist is on sale', 'aj-woocommerce-wishlist' ),
				'id'        => 'woocommerce_aj_wcwl_on_sale_item_settings[enabled]',
				'default'   => 'no',
				'type'      => 'aj-field',
				'aj-type' => 'onoff',
			),

			'on_sale_item_email_product_exclusions' => array(
				'name'      => __( 'Product exclusions', 'aj-woocommerce-wishlist' ),
				'desc'      => __( 'Select products that should not trigger the "on sale item" notifications', 'aj-woocommerce-wishlist' ),
				'id'        => 'woocommerce_aj_wcwl_on_sale_item_settings[product_exclusions]',
				'type'      => 'aj-field',
				'aj-type' => 'select',
				'multiple'  => true,
				'class'     => 'wc-product-search',
				'options'   => $on_sale_item_exclusions_options,
			),

			'on_sale_item_email_category_exclusions' => array(
				'name'      => __( 'Category exclusions', 'aj-woocommerce-wishlist' ),
				'desc'      => __( 'Select product categories that should not trigger the "on sale item" notification', 'aj-woocommerce-wishlist' ),
				'id'        => 'woocommerce_aj_wcwl_on_sale_item_settings[category_exclusions]',
				'type'      => 'aj-field',
				'aj-type' => 'select',
				'multiple'  => true,
				'class'     => 'wc-enhanced-select',
				'options'   => $product_categories,
			),

			'on_sale_item_email_mail_type' => array(
				'name'      => __( 'Email type', 'aj-woocommerce-wishlist' ),
				'desc'      => __( 'Choose which type of email to send', 'aj-woocommerce-wishlist' ),
				'id'        => 'woocommerce_aj_wcwl_on_sale_item_settings[email_type]',
				'default'   => 'html',
				'type'      => 'aj-field',
				'aj-type' => 'select',
				'class'     => 'wc-enhanced-select',
				'options'   => array(
					'plain'     => __( 'Plain', 'aj-woocommerce-wishlist' ),
					'html'      => __( 'HTML', 'aj-woocommerce-wishlist' ),
					'multipart' => __( 'Multipart', 'aj-woocommerce-wishlist' ),
				),
			),

			'on_sale_item_email_mail_heading' => array(
				'name'      => __( 'Email heading', 'aj-woocommerce-wishlist' ),
				'desc'      => __( 'Enter the title for the email notification. Leave blank to use the default heading: "<i>An item of your wishlist is on sale!</i>"', 'aj-woocommerce-wishlist' ),
				'id'        => 'woocommerce_aj_wcwl_on_sale_item_settings[heading]',
				'default'   => '',
				'type'      => 'text',
			),

			'on_sale_item_email_mail_subject' => array(
				'name'      => __( 'Email subject', 'aj-woocommerce-wishlist' ),
				'desc'      => __( 'Enter the mail subject line. Leave blank to use the default subject: "<i>An item of your wishlist is on sale!</i>"', 'aj-woocommerce-wishlist' ),
				'id'        => 'woocommerce_aj_wcwl_on_sale_item_settings[subject]',
				'default'   => '',
				'type'      => 'text',
			),

			'on_sale_item_email_html_content' => array(
				'name'      => __( 'Email HTML content', 'aj-woocommerce-wishlist' ),
				'desc'      => __( 'This field lets you modify the main content of the HTML email. You can use the following placeholders: <code>{user_name}</code> <code>{user_email}</code> <code>{user_first_name}</code> <code>{user_last_name}</code> <code>{products_table}</code> <code>{unsubscribe_link}</code>', 'aj-woocommerce-wishlist' ),
				'id'        => 'woocommerce_aj_wcwl_on_sale_item_settings[content_html]',
				'default'   => class_exists( 'AJ_WCWL_On_Sale_Item_Email' ) ? AJ_WCWL_On_Sale_Item_Email::get_default_content( 'html' ) : '',
				'type'      => 'aj-field',
				'aj-type' => 'textarea',
			),

			'on_sale_item_email_text_subject' => array(
				'name'      => __( 'Email plain content', 'aj-woocommerce-wishlist' ),
				'desc'      => __( 'This field lets you modify the main content of the text email. You can use the following placeholders: <code>{user_name}</code> <code>{user_email}</code> <code>{user_first_name}</code> <code>{user_last_name}</code> <code>{products_list}</code> <code>{unsubscribe_url}</code>', 'aj-woocommerce-wishlist' ),
				'id'        => 'woocommerce_aj_wcwl_on_sale_item_settings[content_text]',
				'default'   => class_exists( 'AJ_WCWL_On_Sale_Item_Email' ) ? AJ_WCWL_On_Sale_Item_Email::get_default_content( 'plain' ) : '',
				'type'      => 'aj-field',
				'aj-type' => 'textarea',
			),

			'on_sale_item_email_end' => array(
				'type' => 'sectionend',
				'id' => 'aj_wcwl_on_sale_item_email',
			),
		),
	)
);
