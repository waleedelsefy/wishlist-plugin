<?php
/**
 * Admin ask estimate email
 *
 * @author  Your Inspiration Themes
 * @package AJ WooCommerce Wishlist
 * @version 0.1.26
 */

/**
 * Template variables:
 *
 * @var $wishlist_data       \AJ_WCWL_Wishlist
 * @var $email_heading       string
 * @var $email               \WC_Email
 * @var $user_formatted_name string
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

echo "=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=\n";
echo esc_html( wp_strip_all_tags( $email_heading ) );
echo "\n=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=\n\n";

// translators: 1. Customer full name.
echo sprintf( esc_html__( 'You have received an estimate request from %s. The request is the following:', 'aj-woocommerce-wishlist' ), esc_html( $user_formatted_name ) ) . "\n\n";

echo "\n----------------------------------------\n\n";

do_action( 'woocommerce_email_before_wishlist_table', $wishlist_data );

// translators: 1. Wishlist token.
echo sprintf( esc_html__( 'Wishlist: %s', 'aj-woocommerce-wishlist' ), esc_html( $wishlist_data->get_token() ) ) . "\n";
// translators: 1. Url to wishlist page.
echo sprintf( esc_html__( 'Wishlist link: %s', 'aj-woocommerce-wishlist' ), esc_html( $wishlist_data->get_url() ) ) . "\n";

echo "\n";

if ( $wishlist_data->has_items() ) :
	foreach ( $wishlist_data->get_items() as $item ) :
		$product = $item->get_product();
		echo esc_html( wp_strip_all_tags( $product->get_title() ) ) . ' | ';
		echo esc_html( $item->get_quantity() ) . ' | ';
		echo esc_html( wp_strip_all_tags( wc_price( $item->get_product_price() ) ) );
		echo "\n";
	endforeach;
endif;

echo "\n----------------------------------------\n\n";

if ( ! empty( $additional_notes ) ) :
	echo "\n" . esc_html__( 'Additional info:', 'aj-woocommerce-wishlist' ) . "\n\n";

	echo esc_html( wp_strip_all_tags( $additional_notes ) ) . "\n";

	echo "\n----------------------------------------\n\n";
endif;

if ( ! empty( $additional_data ) ) :
	echo "\n" . esc_html__( 'Additional data:', 'aj-woocommerce-wishlist' ) . "\n\n";

	foreach ( $additional_data as $key => $value ) :

		$key   = strip_tags( ucwords( str_replace( array( '_', '-' ), ' ', $key ) ) );
		$value = strip_tags( $value );

		echo esc_html( wp_strip_all_tags( "{$key}: {$value}" ) ) . "\n";

	endforeach;

	echo "\n----------------------------------------\n\n";
endif;

do_action( 'aj_wcwl_email_after_wishlist_table', $wishlist_data );

echo esc_html__( 'Customer details', 'aj-woocommerce-wishlist' ) . "\n\n";

echo esc_html__( 'Email:', 'aj-woocommerce-wishlist' );
echo esc_html( wp_strip_all_tags( $email->reply_email ) ) . "\n";

echo "\n----------------------------------------\n\n";

echo wp_kses_post( apply_filters( 'woocommerce_email_footer_text', get_option( 'woocommerce_email_footer_text' ) ) );
