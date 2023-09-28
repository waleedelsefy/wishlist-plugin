<?php
/**
 * AJ Upgrade Class
 * handle notifications and plugin updates.
 *
 * @class   DIDO_Upgrade
 * @package AJ\PluginFramework\Classes
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

if ( ! class_exists( 'DIDO_Upgrade' ) ) {
	/**
	 * DIDO_Upgrade class.
	 */
	class DIDO_Upgrade {
		/**
		 * The single instance of the class.
		 *
		 * @var DIDO_Upgrade
		 */
		private static $instance;

		/**
		 * Singleton implementation.
		 *
		 * @return DIDO_Upgrade
		 */
		public static function instance() {
			return ! is_null( self::$instance ) ? self::$instance : self::$instance = new self();
		}

		/**
		 * DIDO_Upgrade constructor.
		 */
		private function __construct() {
			// Silence is golden.
		}

		/**
		 * Premium products registration.
		 *
		 * @param string $plugin_slug The plugin slug.
		 * @param string $plugin_init The plugin init file.
		 */
		public function register( $plugin_slug, $plugin_init ) {
			if ( ! function_exists( 'AJ_Plugin_Upgrade' ) ) {
				// Try to load AJ_Plugin_Upgrade class.
				aj_plugin_fw_load_update_and_licence_files();
			}

			if ( function_exists( 'AJ_Plugin_Upgrade' ) && is_callable( array( AJ_Plugin_Upgrade(), 'register' ) ) ) {
				AJ_Plugin_Upgrade()->register( $plugin_slug, $plugin_init );
			}
		}
	}
}

if ( ! function_exists( 'DIDO_Upgrade' ) ) {
	/**
	 * Single instance of DIDO_Upgrade
	 *
	 * @return DIDO_Upgrade
	 */
	function DIDO_Upgrade() { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName.FunctionNameInvalid
		return DIDO_Upgrade::instance();
	}
}

DIDO_Upgrade();
