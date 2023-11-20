<?php
/**
 * AJ Plugin License Class.
 *
 * @class   DIDO_Plugin_Licence
 * @package AJ\PluginFramework\Classes
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

if ( ! class_exists( 'DIDO_Plugin_Licence' ) ) {
	/**
	 * DIDO_Plugin_Licence class.
	 * Set page to manage products.
	 *
	 * @author Andrea Grillo <andrea.grillo@ajemes.com>
	 */
	class DIDO_Plugin_Licence {
		/**
		 * The single instance of the class.
		 *
		 * @var DIDO_Plugin_Licence
		 */
		private static $instance;

		/**
		 * Singleton implementation.
		 *
		 * @return DIDO_Plugin_Licence
		 */
		public static function instance() {
			return ! is_null( self::$instance ) ? self::$instance : self::$instance = new self();
		}

		/**
		 * DIDO_Plugin_Licence constructor.
		 */
		private function __construct() {
			// Silence is golden.
		}

		/**
		 * Premium products registration
		 *
		 * @param string $init       The product identifier.
		 * @param string $secret_key The secret key.
		 * @param string $product_id The product id.
		 *
		 * @return void
		 */
		public function register( $init, $secret_key, $product_id ) {
			if ( ! function_exists( 'AJ_Plugin_Licence' ) ) {
				// Try to load AJ_Plugin_Licence class.
				aj_plugin_fw_load_update_and_licence_files();
			}

			if ( function_exists( 'AJ_Plugin_Licence' ) && is_callable( array( AJ_Plugin_Licence(), 'register' ) ) ) {
				AJ_Plugin_Licence()->register( $init, $secret_key, $product_id );
			}
		}

		/**
		 * Get license activation URL
		 *
		 * @param string $plugin_slug The plugin slug.
		 *
		 * @return string|false
		 * @since  3.0.17
		 */
		public static function get_license_activation_url( $plugin_slug = '' ) {
			return function_exists( 'AJ_Plugin_Licence' ) ? AJ_Plugin_Licence()->get_license_activation_url( $plugin_slug ) : false;
		}

		/**
		 * Retrieve the products
		 *
		 * @return array
		 */
		public function get_products() {
			return function_exists( 'AJ_Plugin_Licence' ) ? AJ_Plugin_Licence()->get_products() : array();
		}
	}
}

if ( ! function_exists( 'DIDO_Plugin_Licence' ) ) {
	/**
	 * Single instance of DIDO_Plugin_Licence
	 *
	 * @return DIDO_Plugin_Licence
	 */
	function DIDO_Plugin_Licence() { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName.FunctionNameInvalid
		return DIDO_Plugin_Licence::instance();
	}
}
