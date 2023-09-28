<?php
/**
 * Wishlist Exception class
 *
 * @author Your Inspiration Themes
 * @package AJ WooCommerce Wishlist
 * @version 0.1.26
 */

if ( ! defined( 'AJ_WCWL' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'AJ_WCWL_Exception' ) ) {
	/**
	 * WooCommerce Wishlist Exception
	 *
	 * @since 1.0.0
	 */
	class AJ_WCWL_Exception extends Exception {
		private $_errorCodes = array(
			0 => 'error',
			1 => 'exists'
		);

		public function getTextualCode() {
			$code = $this->getCode();

			if( array_key_exists( $code, $this->_errorCodes ) ){
				return $this->_errorCodes[ $code ];
			}

			return 'error';
		}
	}
}