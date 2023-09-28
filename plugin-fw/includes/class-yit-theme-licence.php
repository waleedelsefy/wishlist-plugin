<?php
/**
 * AJ Theme License Class.
 *
 * @class   DIDO_Theme_Licence
 * @package AJ\PluginFramework\Classes
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

if ( ! class_exists( 'DIDO_Theme_Licence' ) ) {
	/**
	 * DIDO_Theme_Licence class.
	 *
	 * @author Andrea Grillo <andrea.grillo@ajemes.com>
	 */
	class DIDO_Theme_Licence {
		/**
		 * The single instance of the class.
		 *
		 * @var DIDO_Theme_Licence
		 */
		private static $instance;

		/**
		 * Singleton implementation.
		 *
		 * @return DIDO_Theme_Licence
		 */
		public static function instance() {
			return ! is_null( self::$instance ) ? self::$instance : self::$instance = new self();
		}

		/**
		 * DIDO_Theme_Licence constructor.
		 */
		private function __construct() {
			// Silence is golden.
		}

		/**
		 * Premium products registration.
		 *
		 * @param string $init       The product init identifier.
		 * @param string $secret_key The secret key.
		 * @param string $product_id The product ID.
		 *
		 * @return void
		 * @since    1.0
		 * @author   Andrea Grillo <andrea.grillo@ajemes.com>
		 */
		public function register( $init, $secret_key, $product_id ) {
			if ( ! function_exists( 'AJ_Theme_Licence' ) ) {
				// Try to load AJ_Theme_Licence class.
				aj_plugin_fw_load_update_and_licence_files();
			}

			if ( function_exists( 'AJ_Theme_Licence' ) && is_callable( array( AJ_Theme_Licence(), 'register' ) ) ) {
				AJ_Theme_Licence()->register( $init, $secret_key, $product_id );
			}
		}
	}
}

if ( ! function_exists( 'DIDO_Theme_Licence' ) ) {
	/**
	 * Single instance of DIDO_Theme_Licence
	 *
	 * @return DIDO_Theme_Licence
	 */
	function DIDO_Theme_Licence() { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName.FunctionNameInvalid
		return DIDO_Theme_Licence::instance();
	}
}
